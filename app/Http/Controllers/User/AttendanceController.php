<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    // Menampilkan rekap absensi karyawan (User)
    public function index(Request $request)
    {
        $dateParam = $request->query('date');
        $selectedDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        $actualToday = Carbon::today();
        $isActualToday = $selectedDate->isSameDay($actualToday);
        
        if (!auth()->user()->hasRole(['superadmin', 'admin'])) {
            $userAttendances = Attendance::where('user_id', auth()->id())
                ->whereMonth('date', $selectedDate->month)
                ->whereYear('date', $selectedDate->year)
                ->orderBy('date', 'asc')
                ->get();
                
            $recentAttendances = Attendance::where('user_id', auth()->id())
                ->orderBy('date', 'desc')
                ->take(5)
                ->get();

            $selectedAttendance = Attendance::where('user_id', auth()->id())
                ->whereDate('date', $selectedDate->toDateString())
                ->first();

            $todayAttendance = Attendance::where('user_id', auth()->id())
                ->whereDate('date', $actualToday->toDateString())
                ->first();
                
            $totalSeconds = 0;
            foreach ($userAttendances as $att) {
                if ($att->time_in && $att->time_out) {
                    $in = Carbon::parse($att->time_in);
                    $out = Carbon::parse($att->time_out);
                    $totalSeconds += $in->diffInSeconds($out, true);
                }
            }
            $totalJamKerja = round($totalSeconds / 3600, 1);
            
            $workLocation = auth()->user()->employee->work_location ?? 'Kantor';

            $jamMasuk = \App\Models\Setting::get('jam_masuk', '08:00');
            $jamKeluar = \App\Models\Setting::get('jam_keluar', '16:00');
            $toleransi = \App\Models\Setting::get('toleransi_terlambat', '0');

            return view('user.attendance.user', compact('userAttendances', 'todayAttendance', 'selectedAttendance', 'recentAttendances', 'totalJamKerja', 'workLocation', 'selectedDate', 'isActualToday', 'actualToday', 'jamMasuk', 'jamKeluar', 'toleransi'));
        }

        $karyawanUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        });

        $departmentId = $request->query('department_id');
        if ($departmentId) {
            $karyawanUsers->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        $totalKaryawan = $karyawanUsers->count();
        $karyawanIds = $karyawanUsers->pluck('id');

        $attendancesToday = Attendance::with(['user.employee.department'])
            ->whereIn('user_id', $karyawanIds)
            ->whereDate('date', $selectedDate->toDateString())
            ->get();

        $adminMonthAttendances = Attendance::whereIn('user_id', $karyawanIds)
            ->whereMonth('date', $selectedDate->month)
            ->whereYear('date', $selectedDate->year)
            ->get();

        $hadir = $attendancesToday->whereIn('status', ['present', 'late'])->count();
        $izinSakit = $attendancesToday->whereIn('status', ['on_leave'])->count();
        $alpa = $attendancesToday->where('status', 'absent')->count();

        $alpa += ($totalKaryawan - $attendancesToday->count());
        $hadirPercentage = $totalKaryawan > 0 ? round(($hadir / $totalKaryawan) * 100) : 0;

        $departments = Department::all();

        return view('user.attendance.index', compact(
            'totalKaryawan',
            'hadir',
            'hadirPercentage',
            'izinSakit',
            'alpa',
            'attendancesToday',
            'adminMonthAttendances',
            'departments',
            'selectedDate',
            'actualToday',
            'isActualToday',
            'departmentId'
        ));
    }

    // Clock In (absen masuk + verifikasi lokasi/foto jika remote)
    public function clockIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string'
        ]);

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if ($attendance && $attendance->time_in) {
            return back()->with('error', 'Anda sudah melakukan Clock In hari ini.');
        }

        $workLocation = auth()->user()->employee->work_location ?? 'Kantor';
        $photoPath = null;
        
        if ($workLocation == 'Remote') {
            if (!$request->photo) {
                return back()->with('error', 'Foto wajib dilampirkan untuk absensi karyawan Remote.');
            }
            $image_parts = explode(";base64,", $request->photo);
            if (count($image_parts) >= 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'attendance_in_' . auth()->id() . '_' . time() . '.' . $image_type;
                \Illuminate\Support\Facades\Storage::disk('public')->put('attendances/' . $fileName, $image_base64);
                $photoPath = 'attendances/' . $fileName;
            }
        }

        $jamMasuk = \App\Models\Setting::get('jam_masuk', '08:00');
        $toleransi = (int) \App\Models\Setting::get('toleransi_terlambat', 0);
        $batasWaktu = Carbon::parse($jamMasuk)->addMinutes($toleransi)->format('H:i');
        
        $status = Carbon::now()->format('H:i') > $batasWaktu ? 'late' : 'present';
        
        $locationName = $workLocation == 'Remote' ? 'Remote Location' : 'Kantor Pusat';

        Attendance::updateOrCreate(
            ['user_id' => auth()->id(), 'date' => $today],
            [
                'time_in' => $now, 
                'status' => $status, 
                'location' => $locationName,
                'lat_in' => $request->latitude,
                'long_in' => $request->longitude,
                'photo_in' => $photoPath
            ]
        );

        auth()->user()->notify(new \App\Notifications\AttendanceNotification("Anda berhasil Clock In pada {$now}"));

        return back()->with('success', 'Berhasil Clock In.');
    }

    // Clock Out (absen keluar)
    public function clockOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'nullable|string'
        ]);

        $today = Carbon::today()->toDateString();
        $now = Carbon::now()->toTimeString();

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
            return back()->with('error', 'Anda belum melakukan Clock In.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'Anda sudah melakukan Clock Out hari ini.');
        }

        $workLocation = auth()->user()->employee->work_location ?? 'Kantor';
        $photoPath = null;
        
        if ($workLocation == 'Remote') {
            if (!$request->photo) {
                return back()->with('error', 'Foto wajib dilampirkan untuk absensi karyawan Remote.');
            }
            $image_parts = explode(";base64,", $request->photo);
            if (count($image_parts) >= 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'attendance_out_' . auth()->id() . '_' . time() . '.' . $image_type;
                \Illuminate\Support\Facades\Storage::disk('public')->put('attendances/' . $fileName, $image_base64);
                $photoPath = 'attendances/' . $fileName;
            }
        }

        $attendance->update([
            'time_out' => $now,
            'lat_out' => $request->latitude,
            'long_out' => $request->longitude,
            'photo_out' => $photoPath
        ]);

        auth()->user()->notify(new \App\Notifications\AttendanceNotification("Anda berhasil Clock Out pada {$now}"));

        return back()->with('success', 'Berhasil Clock Out.');
    }

    // Export rekap absensi
    public function export(Request $request)
    {
        $departmentId = $request->query('department_id');
        $filename = 'rekap_absensi_' . Carbon::now()->format('Y_m_d_His') . '.xlsx';
        
        return Excel::download(new AttendanceExport($departmentId), $filename);
    }

    // Reset absensi hari ini milik pengguna sendiri (untuk pengujian ulang)
    public function resetToday()
    {
        Attendance::where('user_id', auth()->id())
            ->whereDate('date', Carbon::today()->toDateString())
            ->delete();

        return back()->with('success', 'Presensi hari ini telah berhasil direset. Anda dapat melakukan Clock-In kembali.');
    }
}
