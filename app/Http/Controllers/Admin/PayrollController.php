<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Department;
use App\Models\Position;
use App\Exports\PayrollExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    // Menampilkan daftar payroll
    public function index(Request $request)
    {
        $currentMonth = now()->format('F');
        $currentYear = now()->year;
        
        if (!auth()->user()->hasRole(['superadmin'])) {
            $userPayrolls = Payroll::with(['user.employee.department', 'user.employee.position'])
                ->where('user_id', auth()->id())
                ->orderBy('year', 'desc')
                ->orderByRaw("FIELD(month, 'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January')")
                ->get();

            $latestPayroll = $userPayrolls->first();
            $chartPayrolls = $userPayrolls->take(6)->reverse()->values();

            return view('admin.payrolls.user', compact('userPayrolls', 'latestPayroll', 'chartPayrolls', 'currentMonth', 'currentYear'));
        }

        $query = Payroll::with(['user.employee.department'])
            ->where('month', $currentMonth)
            ->where('year', $currentYear);
            
        if ($request->filled('department_id')) {
            $query->whereHas('user.employee', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        
        $payrolls = $query->paginate(10)->withQueryString();
        $departments = Department::all();
        $positions = Position::all();
        
        $totalPayroll = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->sum('net_salary');
            
        $totalKaryawan = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->count();
            
        $belumDibayar = Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'draft')
            ->count();
            
        if ($totalKaryawan == 0) {
            $statusPembayaran = 'Belum Ada';
        } else if ($belumDibayar == 0) {
            $statusPembayaran = 'Selesai';
        } else if ($belumDibayar < $totalKaryawan) {
            $statusPembayaran = 'Diproses';
        } else {
            $statusPembayaran = 'Belum Ada';
        }

        return view('admin.payrolls.index', compact(
            'payrolls', 'departments', 'positions', 'totalPayroll', 'totalKaryawan', 'belumDibayar', 'statusPembayaran', 'currentMonth'
        ));
    }

    // Update data payroll & gaji bersih
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'basic_salary' => 'required|numeric',
            'premium' => 'required|numeric',
            'total_allowance' => 'required|numeric',
            'total_reward' => 'required|numeric',
        ]);

        $grossSalary = $request->basic_salary + $request->premium + $request->total_allowance + $request->total_reward;
        $jhtDeduction = $grossSalary * 0.02;
        $netSalary = $grossSalary - $jhtDeduction;

        $payroll->update([
            'basic_salary' => $request->basic_salary,
            'premium' => $request->premium,
            'total_allowance' => $request->total_allowance,
            'total_reward' => $request->total_reward,
            'jht_deduction' => $jhtDeduction,
            'net_salary' => $netSalary,
        ]);

        if ($payroll->user && $payroll->user->employee) {
            $payroll->user->employee->update([
                'basic_salary' => $request->basic_salary,
                'premium' => $request->premium,
            ]);
        }

        return redirect()->route('admin.payroll.index')->with('success', 'Gaji karyawan berhasil ditambahkan/diperbarui.');
    }

    // Proses status payroll dari draft ke paid
    public function process(Request $request)
    {
        $currentMonth = now()->format('F');
        $currentYear = now()->year;

        Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'draft')
            ->update(['status' => 'paid']);

        return redirect()->route('admin.payroll.index')->with('success', 'Semua gaji bulan ini berhasil diproses (Selesai).');
    }

    // Export laporan gaji ke Excel/PDF
    public function export(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable',
            'position_id' => 'nullable',
            'month' => 'required',
            'year' => 'required',
            'format' => 'required|in:excel,pdf'
        ]);

        if ($request->format == 'excel') {
            return Excel::download(new PayrollExport($request->department_id, $request->position_id, $request->month, $request->year), 'laporan_gaji_'.$request->month.'_'.$request->year.'.xlsx');
        } else {
            $query = Payroll::with(['user.employee.department', 'user.employee.position'])
                ->where('month', $request->month)
                ->where('year', $request->year);

            $departmentName = null;
            if ($request->filled('department_id')) {
                $query->whereHas('user.employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
                $departmentName = Department::find($request->department_id)->name ?? 'Semua';
            }

            $positionName = null;
            if ($request->filled('position_id')) {
                $query->whereHas('user.employee', function($q) use ($request) {
                    $q->where('position_id', $request->position_id);
                });
                $positionName = Position::find($request->position_id)->name ?? 'Semua';
            }

            $payrolls = $query->get();

            $pdf = Pdf::loadView('payrolls.export-pdf', [
                'payrolls' => $payrolls,
                'month' => $request->month,
                'year' => $request->year,
                'departmentName' => $departmentName,
                'positionName' => $positionName
            ])->setPaper('a4', 'landscape');

            return $pdf->download('laporan_gaji_'.$request->month.'_'.$request->year.'.pdf');
        }
    }

    // Download slip gaji PDF
    public function downloadPayslip(Payroll $payroll)
    {
        abort_if(!auth()->user()->hasRole(['superadmin', 'admin']) && auth()->id() !== $payroll->user_id, 403, 'Anda tidak diizinkan mengakses slip gaji ini.');

        $payroll->load(['user.employee.department', 'user.employee.position', 'user.employee.jobType']);

        $pdf = Pdf::loadView('payrolls.payslip-pdf', compact('payroll'))->setPaper('a4', 'portrait');

        return $pdf->download('Slip_Gaji_'.$payroll->month.'_'.$payroll->year.'_'.str_replace(' ', '_', $payroll->user->name).'.pdf');
    }
}
