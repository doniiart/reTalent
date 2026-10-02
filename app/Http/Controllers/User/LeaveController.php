<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;

class LeaveController extends Controller
{
    // Menampilkan daftar pengajuan cuti pribadi (User)
    public function index(Request $request)
    {
        $currentYear = Carbon::now()->year;

        if (!auth()->user()->hasRole(['superadmin', 'admin'])) {
            $userLeaves = Leave::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            $quota = auth()->user()->employee->department->annual_leave_quota ?? 12;
            
            $usedQuota = Leave::where('user_id', auth()->id())
                ->where('leave_type', 'tahunan')
                ->whereIn('status', ['approved', 'pending'])
                ->whereYear('start_date', $currentYear)
                ->get()
                ->sum(function($leave) {
                    $start = Carbon::parse($leave->start_date);
                    $end = Carbon::parse($leave->end_date);
                    return $start->diffInDays($end) + 1;
                });
            
            $remainingQuota = max(0, $quota - $usedQuota);
            
            $upcomingLeaves = Leave::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->where('start_date', '>=', Carbon::today())
                ->orderBy('start_date', 'asc')
                ->take(3)
                ->get();

            return view('user.leave.user', compact('userLeaves', 'quota', 'usedQuota', 'remainingQuota', 'upcomingLeaves'));
        }

        $leavesThisYear = Leave::whereYear('start_date', $currentYear)->get();
        $totalPengajuan = $leavesThisYear->count();
        $cutiDisetujui = $leavesThisYear->where('status', 'approved')->count();
        $cutiMenunggu = $leavesThisYear->where('status', 'pending')->count();
        $totalKaryawan = User::has('employee')->count();

        $disetujuiPercentage = $totalPengajuan > 0 ? round(($cutiDisetujui / $totalPengajuan) * 100) : 0;

        $departmentId = $request->query('department_id');
        $search = $request->query('search');

        $query = User::has('employee')->with(['employee.department', 'leaves' => function($q) use ($currentYear) {
            $q->whereYear('start_date', $currentYear)->orderBy('start_date', 'desc');
        }]);

        if ($departmentId) {
            $query->whereHas('employee', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $employees = $query->paginate(10)->withQueryString();

        $departments = Department::all();

        return view('user.leave.index', compact(
            'totalPengajuan',
            'cutiDisetujui',
            'cutiMenunggu',
            'totalKaryawan',
            'disetujuiPercentage',
            'employees',
            'departments',
            'departmentId',
            'search'
        ));
    }

    // Update jatah cuti
    public function updateQuota(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'annual_leave_quota' => 'required|integer|min:0'
        ]);

        $department = Department::findOrFail($request->department_id);
        $department->annual_leave_quota = $request->annual_leave_quota;
        $department->save();

        return back()->with('success', 'Jatah cuti departemen berhasil diperbarui.');
    }

    // Export laporan cuti
    public function export(Request $request)
    {
        $departmentId = $request->query('department_id');
        $month = $request->query('month');
        $year = $request->query('year');
        $employeeName = $request->query('employee_name');
        $format = $request->query('format');

        $filename = 'laporan_cuti_' . Carbon::now()->format('Y_m_d_His');

        if ($format === 'pdf') {
            $export = new \App\Exports\LeaveExport($departmentId, $month, $year, $employeeName);
            $leaves = $export->collection();
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('leave.export-pdf', compact('leaves', 'month', 'year'))
                ->setPaper('a4', 'landscape');
            return $pdf->download($filename . '.pdf');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LeaveExport($departmentId, $month, $year, $employeeName), $filename . '.xlsx');
    }

    // Form pengajuan cuti baru
    public function create()
    {
        $currentYear = Carbon::now()->year;
        
        $userLeaves = Leave::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $quota = auth()->user()->employee->department->annual_leave_quota ?? 12;
        
        $usedQuota = Leave::where('user_id', auth()->id())
            ->where('leave_type', 'tahunan')
            ->whereIn('status', ['approved', 'pending'])
            ->whereYear('start_date', $currentYear)
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });
        
        $remainingQuota = max(0, $quota - $usedQuota);
        
        return view('user.leave.create', compact('userLeaves', 'quota', 'remainingQuota'));
    }

    // Kirim pengajuan cuti baru & notifikasi ke atasan
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:tahunan,sakit,khusus',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;

        if ($request->leave_type == 'tahunan') {
            $quota = auth()->user()->employee->department->annual_leave_quota ?? 12;
            $usedQuota = Leave::where('user_id', auth()->id())
                ->where('leave_type', 'tahunan')
                ->whereIn('status', ['approved', 'pending'])
                ->whereYear('start_date', Carbon::now()->year)
                ->get()
                ->sum(function($leave) {
                    return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
                });
            
            if ($usedQuota + $days > $quota) {
                return back()->with('error', 'Sisa kuota cuti tahunan Anda tidak mencukupi.');
            }
        }

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('leaves', 'public');
        }

        Leave::create([
            'user_id' => auth()->id(),
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $path,
            'status' => 'pending'
        ]);

        $days = \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1;
        $leaveTypeStr = ucfirst($request->leave_type ?? 'Cuti');

        $managers = \App\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'superadmin']);
        })->get();

        foreach ($managers as $manager) {
            $manager->notify(new \App\Notifications\NewLeaveRequestNotification(
                "Terdapat pengajuan {$leaveTypeStr} baru dari " . auth()->user()->name . " ({$days} Hari)."
            ));
        }

        return redirect()->route('user.leave.index')->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    // Update status pengajuan cuti
    public function updateStatus(Request $request, Leave $leave)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $leave->update(['status' => $request->status]);

        return back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    }
}
