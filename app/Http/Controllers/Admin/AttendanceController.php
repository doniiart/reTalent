<?php

namespace App\Http\Controllers\Admin;

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
    // Menampilkan rekap absensi karyawan seluruh departemen
    public function index(Request $request)
    {
        $dateParam = $request->query('date');
        $selectedDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        $actualToday = Carbon::today();
        $isActualToday = $selectedDate->isSameDay($actualToday);

        $karyawanUsers = User::whereHas('employee');

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

        return view('admin.attendance.index', compact(
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

    // Tampilan absensi pribadi milik admin sendiri
    public function personal(Request $request)
    {
        $dateParam = $request->query('date');
        $selectedDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        $actualToday = Carbon::today();
        $isActualToday = $selectedDate->isSameDay($actualToday);
        
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

        return view('admin.attendance.user', compact('userAttendances', 'todayAttendance', 'selectedAttendance', 'recentAttendances', 'totalJamKerja', 'workLocation', 'selectedDate', 'isActualToday', 'actualToday', 'jamMasuk', 'jamKeluar', 'toleransi'));
    }

    // Proses Clock In admin sendiri
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

    // Proses Clock Out admin sendiri
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

    // Export rekap absensi ke Excel atau PDF (bulanan atau harian)
    public function export(Request $request)
    {
        $type = $request->query('type', 'monthly');
        $format = $request->query('format', 'excel');
        $departmentId = $request->query('department_id');

        // Export Harian (per tanggal)
        if ($type === 'daily') {
            $date = $request->query('date', Carbon::today()->toDateString());
            $fileNameBase = 'Rekap_Absensi_Harian_' . str_replace('-', '', $date);

            if ($format === 'pdf') {
                $query = Attendance::with(['user.employee.department'])
                            ->whereDate('date', $date);
                if ($departmentId) {
                    $query->whereHas('user.employee', function($q) use ($departmentId) {
                        $q->where('department_id', $departmentId);
                    });
                }
                $attendances = $query->orderBy('time_in', 'asc')->get();
                $departmentName = $departmentId ? \App\Models\Department::find($departmentId)->name ?? null : null;

                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.attendance_daily_pdf', compact('attendances', 'date', 'departmentName'));
                return $pdf->download($fileNameBase . '.pdf');
            }

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\AttendanceExport(null, null, $departmentId, $date),
                $fileNameBase . '.xlsx'
            );
        }

        // Export Bulanan (default)
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);
        $fileNameBase = 'Rekap_Absensi_' . $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT);
        
        if ($format === 'pdf') {
            $query = Attendance::with(['user.employee.department'])
                        ->whereYear('date', $year)
                        ->whereMonth('date', $month);
            if ($departmentId) {
                $query->whereHas('user.employee', function($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            }
            $attendances = $query->orderBy('date', 'desc')->get();
            $monthName = Carbon::createFromDate($year, $month, 1)->isoFormat('MMMM YYYY');
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.attendance_pdf', compact('attendances', 'monthName'));
            return $pdf->download($fileNameBase . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceExport($year, $month, $departmentId), $fileNameBase . '.xlsx');
    }

    // Import data absensi
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AttendanceImport, $request->file('file'));
            return back()->with('success', 'Data absensi berhasil di-import.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
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
