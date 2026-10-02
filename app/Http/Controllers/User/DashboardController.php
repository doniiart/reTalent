<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard karyawan (User)
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole(['superadmin', 'admin'])) {
            $totalEmployees = Employee::count();
            $newEmployeesThisMonth = Employee::whereMonth('created_at', Carbon::now()->month)
                                             ->whereYear('created_at', Carbon::now()->year)
                                             ->count();
            
            $today = Carbon::today();
            $attendanceToday = Attendance::whereDate('date', $today)->whereIn('status', ['present', 'late', 'hadir'])->count();
            $nonSuperadminCount = \App\Models\User::whereDoesntHave('roles', function($q) { $q->where('name', 'superadmin'); })->count() ?: $totalEmployees;
            $attendancePercentage = $nonSuperadminCount > 0 ? round(($attendanceToday / $nonSuperadminCount) * 100, 1) : 0;
            
            $pendingTasks = \App\Models\Leave::where('status', 'pending')->count();
            
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $weeklyDays = [];
            $weeklyHadir = [];
            $weeklyIzin = [];
            $dayNameIndo = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 0 => 'Min'];

            for ($i = 0; $i < 6; $i++) {
                $d = $startOfWeek->copy()->addDays($i);
                $weeklyDays[] = $dayNameIndo[$d->dayOfWeek];
                $weeklyHadir[] = Attendance::whereDate('date', $d)->whereIn('status', ['present', 'late', 'hadir'])->count();
                $weeklyIzin[] = Attendance::whereDate('date', $d)->whereIn('status', ['on_leave', 'izin', 'sakit'])->count() + \App\Models\Leave::where('status', 'approved')->whereDate('start_date', '<=', $d)->whereDate('end_date', '>=', $d)->count();
            }
            
            $statusTetap = Employee::whereIn('employment_status', ['Tetap', 'tetap'])->count() ?: 0;
            $statusKontrak = Employee::whereIn('employment_status', ['Kontrak', 'kontrak'])->count() ?: 0;
            $statusMagang = Employee::whereIn('employment_status', ['Magang', 'magang', 'intership', 'internship'])->count() ?: 0;
            if ($statusTetap == 0 && $statusKontrak == 0 && $statusMagang == 0 && $totalEmployees > 0) {
                $statusTetap = $totalEmployees;
            }
            
            $recentRequests = \App\Models\Leave::with('user.employee.position')->orderBy('created_at', 'desc')->limit(5)->get();
            $announcements = \App\Models\Announcement::with(['user', 'department'])->where('is_active', true)->orderBy('created_at', 'desc')->limit(5)->get();

            return view('user.dashboard', compact(
                'totalEmployees', 'newEmployeesThisMonth',
                'attendanceToday', 'attendancePercentage',
                'pendingTasks',
                'weeklyDays', 'weeklyHadir', 'weeklyIzin',
                'statusTetap', 'statusKontrak', 'statusMagang',
                'recentRequests', 'announcements'
            ));
        }

        // Tampilan dashboard personal Karyawan
        $employee = Employee::where('user_id', $user->id)->first();
        
        if (!$employee) {
            return view('user.dashboard');
        }

        // Hitung sisa cuti sesuai kuota departemen, kehadiran bulan ini, & aktivitas terbaru
        $currentYear = Carbon::now()->year;
        $quota = $employee->department->annual_leave_quota ?? 12;
        $usedQuota = \App\Models\Leave::where('user_id', $user->id)
            ->where('leave_type', 'tahunan')
            ->whereIn('status', ['approved', 'pending'])
            ->whereYear('start_date', $currentYear)
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });
        $sisaCuti = max(0, $quota - $usedQuota);

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $totalWorkDays = 0;
        for ($d = $startOfMonth->copy(); $d->lte($endOfMonth); $d->addDay()) {
            if (!$d->isSunday()) {
                $totalWorkDays++;
            }
        } 
        $attendedDays = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->whereIn('status', ['present', 'late', 'hadir'])
            ->count();
        $attendancePercentage = $totalWorkDays > 0 ? round(($attendedDays / $totalWorkDays) * 100, 1) : 0;

        $performanceScore = 8.5;

        $activities = \App\Models\ActivityLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        $recentActivities = $activities->map(function($act) {
            $type = 'info';
            if ($act->status == 'success') $type = 'success';
            if ($act->status == 'failed' || $act->status == 'error') $type = 'warning';
            return [
                'type' => $type,
                'title' => $act->activity,
                'time' => $act->created_at->diffForHumans()
            ];
        })->toArray();

        if (count($recentActivities) == 0) {
            $recentActivities = [
                ['type' => 'success', 'title' => 'Sistem Aktif', 'time' => 'Baru saja'],
            ];
        }

        $userLeaves = \App\Models\Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $latestPayroll = \App\Models\Payroll::where('user_id', $user->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
            
        $payrollDate = 'Belum Ada';
        if ($latestPayroll) {
            if (is_numeric($latestPayroll->month)) {
                $payrollDate = Carbon::create($latestPayroll->year, $latestPayroll->month, 1)->translatedFormat('F Y');
            } else {
                $payrollDate = ucfirst($latestPayroll->month) . ' ' . $latestPayroll->year;
            }
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $chartHeights = [];
        $chartDays = [];
        $chartHours = [];
        $chartTooltips = [];
        $dayNameIndo = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 0 => 'Min'];

        for ($i = 0; $i < 6; $i++) {
            $d = $startOfWeek->copy()->addDays($i);
            $dateStr = $d->format('Y-m-d');
            $chartDays[] = $dayNameIndo[$d->dayOfWeek];
            
            $att = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $dateStr)
                ->first();
            
            if ($att && $att->time_in) {
                if ($att->time_out) {
                    $workMinutes = Carbon::parse($att->time_in)->diffInMinutes(Carbon::parse($att->time_out));
                } elseif ($d->isToday()) {
                    $workMinutes = Carbon::parse($att->time_in)->diffInMinutes(Carbon::now());
                } else {
                    $workMinutes = 480;
                }
                $workHours = round($workMinutes / 60, 1);
                $height = min(100, round(($workHours / 8) * 100));
                $chartHeights[] = max(15, $height);
                $chartHours[] = $workHours . ' Jam';
                $chartTooltips[] = ($att->status == 'late' ? 'Terlambat' : 'Tepat Waktu') . ' (' . $workHours . ' Jam)';
            } else {
                $leave = \App\Models\Leave::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', $dateStr)
                    ->whereDate('end_date', '>=', $dateStr)
                    ->first();

                if ($leave) {
                    $chartHeights[] = 100;
                    $chartHours[] = 'Cuti';
                    $chartTooltips[] = 'Cuti ' . ucfirst($leave->leave_type);
                } else {
                    $chartHeights[] = 0;
                    $chartHours[] = '0 Jam';
                    $chartTooltips[] = $d->isFuture() ? 'Belum Ada Presensi' : 'Tidak Hadir';
                }
            }
        }

        $announcements = \App\Models\Announcement::with(['user', 'department'])
            ->where('is_active', true)
            ->where(function ($q) use ($employee) {
                $q->whereNull('department_id');
                if ($employee && $employee->department_id) {
                    $q->orWhere('department_id', $employee->department_id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)->get();

        return view('user.dashboard.user', compact(
            'employee', 'sisaCuti', 'attendedDays', 'totalWorkDays', 
            'attendancePercentage', 'performanceScore', 'recentActivities', 'userLeaves',
            'payrollDate', 'chartDays', 'chartHeights', 'chartHours', 'chartTooltips', 'announcements'
        ));
    }
}
