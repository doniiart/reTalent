<?php

namespace App\Http\Controllers\SuperAdmin;

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
    // Menampilkan daftar payroll (index)
    public function index(Request $request)
    {
        $currentMonth = now()->format('F');
        $currentYear = now()->year;
        
        // Jika bukan superadmin, tampilkan hanya payroll milik user sendiri
        if (!auth()->user()->hasRole(['superadmin'])) {
            $userPayrolls = Payroll::with(['user.employee.department', 'user.employee.position'])
                ->where('user_id', auth()->id())
                ->orderBy('year', 'desc')
                ->orderByRaw("FIELD(month, 'December', 'November', 'October', 'September', 'August', 'July', 'June', 'May', 'April', 'March', 'February', 'January')")
                ->get();

            $latestPayroll = $userPayrolls->first();
            $chartPayrolls = $userPayrolls->take(6)->reverse()->values(); // Data grafik 6 bulan terakhir

            return view('superadmin.payrolls.user', compact('userPayrolls', 'latestPayroll', 'chartPayrolls', 'currentMonth', 'currentYear'));
        }

        // Query payroll bulan & tahun ini, filter departemen jika ada
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
        
        // Hitung ringkasan: total gaji, jumlah karyawan, belum dibayar
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
            
        // Tentukan status pembayaran
        if ($totalKaryawan == 0) {
            $statusPembayaran = 'Belum Ada';
        } else if ($belumDibayar == 0) {
            $statusPembayaran = 'Selesai';
        } else if ($belumDibayar < $totalKaryawan) {
            $statusPembayaran = 'Diproses';
        } else {
            $statusPembayaran = 'Belum Ada';
        }

        return view('superadmin.payrolls.index', compact(
            'payrolls', 'departments', 'positions', 'totalPayroll', 'totalKaryawan', 'belumDibayar', 'statusPembayaran', 'currentMonth'
        ));
    }

    // Update data payroll & hitung gaji bersih
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'basic_salary' => 'required|numeric',
            'premium' => 'required|numeric',
            'total_allowance' => 'required|numeric',
            'total_reward' => 'required|numeric',
        ]);

        // Hitung gaji kotor, potongan JHT (2%), dan gaji bersih
        $grossSalary = $request->basic_salary + $request->premium + $request->total_allowance + $request->total_reward;
        $jhtDeduction = $grossSalary * 0.02;
        $netSalary = $grossSalary - $jhtDeduction;

        // Simpan ke tabel payroll
        $payroll->update([
            'basic_salary' => $request->basic_salary,
            'premium' => $request->premium,
            'total_allowance' => $request->total_allowance,
            'total_reward' => $request->total_reward,
            'jht_deduction' => $jhtDeduction,
            'net_salary' => $netSalary,
        ]);

        // Sinkronkan gaji pokok & premi ke tabel employee
        if ($payroll->user && $payroll->user->employee) {
            $payroll->user->employee->update([
                'basic_salary' => $request->basic_salary,
                'premium' => $request->premium,
    
            ]);
        }

        return redirect()->route('superadmin.payroll.index')->with('success', 'Gaji karyawan berhasil ditambahkan/diperbarui.');
    }

    // Proses semua payroll draft bulan ini menjadi paid
    public function process(Request $request)
    {
        $currentMonth = now()->format('F');
        $currentYear = now()->year;

        Payroll::where('month', $currentMonth)
            ->where('year', $currentYear)
            ->where('status', 'draft')
            ->update(['status' => 'paid']);

        return redirect()->route('superadmin.payroll.index')->with('success', 'Semua gaji bulan ini berhasil diproses (Selesai).');
    }

    // Export laporan payroll ke Excel atau PDF
    public function export(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable',
            'position_id' => 'nullable',
            'month' => 'required',
            'year' => 'required',
            'format' => 'required|in:excel,pdf'
        ]);

        // Export ke Excel
        if ($request->format == 'excel') {
            return Excel::download(new PayrollExport($request->department_id, $request->position_id, $request->month, $request->year), 'laporan_gaji_'.$request->month.'_'.$request->year.'.xlsx');
        }

        // Export ke PDF — filter departemen & posisi jika ada
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

    // Download slip gaji individual (hanya pemilik, admin, atau superadmin)
    public function downloadPayslip(Payroll $payroll)
    {
        abort_if(!auth()->user()->hasRole(['superadmin', 'admin']) && auth()->id() !== $payroll->user_id, 403, 'Anda tidak diizinkan mengakses slip gaji ini.');

        $payroll->load(['user.employee.department', 'user.employee.position', 'user.employee.jobType']);

        $pdf = Pdf::loadView('payrolls.payslip-pdf', compact('payroll'))->setPaper('a4', 'portrait');

        return $pdf->download('Slip_Gaji_'.$payroll->month.'_'.$payroll->year.'_'.str_replace(' ', '_', $payroll->user->name).'.pdf');
    }
}
