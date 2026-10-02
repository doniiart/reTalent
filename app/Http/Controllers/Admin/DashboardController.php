<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard Admin atau Karyawan
    public function index()
    {
        $user = Auth::user();

        // Dashboard untuk Admin & SuperAdmin
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

            return view('admin.dashboard', compact(
                'totalEmployees', 'newEmployeesThisMonth',
                'attendanceToday', 'attendancePercentage',
                'pendingTasks',
                'weeklyDays', 'weeklyHadir', 'weeklyIzin',
                'statusTetap', 'statusKontrak', 'statusMagang',
                'recentRequests', 'announcements'
            ));
        }

        // Dashboard untuk Karyawan biasa (User)
        $employee = Employee::where('user_id', $user->id)->first();
        
        if (!$employee) {
            return view('admin.dashboard');
        }

        $leaveTaken = \App\Models\Leave::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('start_date', Carbon::now()->year)
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });
        $sisaCuti = 12 - $leaveTaken;

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

        $dbLeaves = \App\Models\Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $leaveRequests = $dbLeaves->map(function($leave) {
            $dur = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            $range = Carbon::parse($leave->start_date)->translatedFormat('d M') . ' - ' . Carbon::parse($leave->end_date)->translatedFormat('d M Y');
            if ($leave->start_date == $leave->end_date) {
                $range = Carbon::parse($leave->start_date)->translatedFormat('d M Y');
            }
            return (object)[
                'type' => 'Cuti',
                'date_range' => $range,
                'duration' => $dur . ' Hari',
                'reason' => $leave->reason,
                'status' => strtoupper($leave->status)
            ];
        })->toArray();
        
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
        $dayNameIndo = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 0 => 'Min'];
        for ($i = 0; $i < 6; $i++) {
            $d = $startOfWeek->copy()->addDays($i);
            $chartDays[] = $dayNameIndo[$d->dayOfWeek];
            
            $att = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('date', $d->format('Y-m-d'))
                ->first();
            
            if ($att && in_array(strtolower($att->status), ['present', 'late', 'hadir'])) {
                $chartHeights[] = 100;
            } else {
                $chartHeights[] = 0;
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

        return view('admin.dashboard.user', compact(
            'employee', 'sisaCuti', 'attendedDays', 'totalWorkDays', 
            'attendancePercentage', 'performanceScore', 'recentActivities', 'leaveRequests',
            'payrollDate', 'chartDays', 'chartHeights', 'announcements'
        ));
    }
}
