<?php

namespace App\Exports;

use App\Models\Leave;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class LeaveExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $department_id;
    protected $month;
    protected $year;
    protected $employee_name;

    public function __construct($department_id, $month, $year, $employee_name)
    {
        $this->department_id = $department_id;
        $this->month = $month;
        $this->year = $year;
        $this->employee_name = $employee_name;
    }

    public function collection()
    {
        $query = Leave::with(['user.employee.department']);

        if ($this->department_id) {
            $query->whereHas('user.employee', function($q) {
                $q->where('department_id', $this->department_id);
            });
        }

        if ($this->month) {
            $query->whereMonth('start_date', $this->month);
        }

        if ($this->year) {
            $query->whereYear('start_date', $this->year);
        }

        if ($this->employee_name) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->employee_name . '%');
            });
        }

        return $query->orderBy('start_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Departemen',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
            'Alasan',
            'Status'
        ];
    }

    public function map($leave): array
    {
        $statusLabel = [
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        $duration = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

        return [
            $leave->user->name ?? '-',
            $leave->user->employee->department->name ?? '-',
            $leave->start_date->format('d M Y'),
            $leave->end_date->format('d M Y'),
            $duration,
            $leave->reason,
            $statusLabel[$leave->status] ?? $leave->status
        ];
    }
}
