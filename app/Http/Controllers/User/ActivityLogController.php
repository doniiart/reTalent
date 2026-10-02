<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    // Menampilkan daftar log aktivitas dengan filter & statistik untuk User
    public function index(Request $request)
    {
        $staffId = $request->query('staff_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $module = $request->query('module');

        $query = ActivityLog::with('user.employee.position')->latest();

        if ($staffId) {
            $query->where('user_id', $staffId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($module) {
            $query->where('module', $module);
        }

        $logs = $query->paginate(15)->withQueryString();

        $todayActivities = ActivityLog::whereDate('created_at', Carbon::today())->count();
        $yesterdayActivities = ActivityLog::whereDate('created_at', Carbon::yesterday())->count();
        $percentChange = $yesterdayActivities > 0 ? round((($todayActivities - $yesterdayActivities) / $yesterdayActivities) * 100) : ($todayActivities > 0 ? 100 : 0);

        $activeSessions = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'superadmin']);
        })->whereHas('activityLogs', function($q) {
            $q->whereDate('created_at', Carbon::today());
        })->with(['employee.position', 'activityLogs' => function($q) {
            $q->latest();
        }])->get()->filter(function($user) {
            $latestLog = $user->activityLogs->first();
            return $latestLog && $latestLog->activity !== 'User Logout';
        })->take(5);

        $staffs = User::has('employee')->get();
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $departments = \App\Models\Department::all();

        return view('user.monitoring.index', compact(
            'logs', 
            'todayActivities', 
            'percentChange',
            'activeSessions',
            'staffs',
            'modules',
            'departments',
            'staffId',
            'startDate',
            'endDate',
            'module'
        ));
    }

    // Export log audit ke format PDF
    public function export(Request $request)
    {
        $departmentId = $request->query('department_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $module = $request->query('module');

        $query = ActivityLog::with('user.employee.department', 'user.employee.position')->latest();

        if ($departmentId) {
            $query->whereHas('user.employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($module) {
            $query->where('module', $module);
        }

        $logs = $query->get();
        $filename = 'laporan_log_audit_' . Carbon::now()->format('Y_m_d_His');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('monitoring.export-pdf', compact('logs', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }
}
