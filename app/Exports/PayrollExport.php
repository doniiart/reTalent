<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PayrollExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $department_id;
    protected $position_id;
    protected $month;
    protected $year;

    public function __construct($department_id, $position_id, $month, $year)
    {
        $this->department_id = $department_id;
        $this->position_id = $position_id;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $query = Payroll::with(['user.employee.department', 'user.employee.position'])
            ->where('month', $this->month)
            ->where('year', $this->year);

        if ($this->department_id) {
            $query->whereHas('user.employee', function($q) {
                $q->where('department_id', $this->department_id);
            });
        }

        if ($this->position_id) {
            $query->whereHas('user.employee', function($q) {
                $q->where('position_id', $this->position_id);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Karyawan',
            'Departemen',
            'Jabatan',
            'Bulan',
            'Tahun',
            'Gaji Pokok',
            'Premi',
            'Total Tunjangan',
            'Total Reward',
            'Potongan JHT',
            'Gaji Bersih',
            'Status Pembayaran'
        ];
    }

    public function map($payroll): array
    {
        return [
            $payroll->user->nip ?? '-',
            $payroll->user->name ?? '-',
            $payroll->user->employee->department->name ?? '-',
            $payroll->user->employee->position->name ?? '-',
            $payroll->month,
            $payroll->year,
            $payroll->basic_salary,
            $payroll->premium,
            $payroll->total_allowance,
            $payroll->total_reward,
            $payroll->jht_deduction,
            $payroll->net_salary,
            $payroll->status == 'paid' ? 'Selesai' : 'Draft',
        ];
    }
}
