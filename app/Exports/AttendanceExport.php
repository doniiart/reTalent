<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $year;
    protected $month;
    protected $department_id;
    protected $date;

    public function __construct($year = null, $month = null, $department_id = null, $date = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->department_id = $department_id;
        $this->date = $date;
    }

    public function collection()
    {
        $query = Attendance::with(['user.employee.department']);

        // Jika filter per tanggal spesifik (harian)
        if ($this->date) {
            $query->whereDate('date', $this->date);
        } else {
            if ($this->year) {
                $query->whereYear('date', $this->year);
            }
            if ($this->month) {
                $query->whereMonth('date', $this->month);
            }
        }

        if ($this->department_id) {
            $query->whereHas('user.employee', function($q) {
                $q->where('department_id', $this->department_id);
            });
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Karyawan',
            'Departemen',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Lokasi'
        ];
    }

    public function map($attendance): array
    {
        $statusLabel = [
            'present' => 'Hadir',
            'late' => 'Terlambat',
            'on_leave' => 'Izin/Sakit',
            'absent' => 'Alpa'
        ];

        return [
            $attendance->user->nip ?? '-',
            $attendance->user->name ?? '-',
            $attendance->user->employee->department->name ?? '-',
            $attendance->date,
            $attendance->time_in ?? '-',
            $attendance->time_out ?? '-',
            $statusLabel[$attendance->status] ?? $attendance->status,
            $attendance->location ?? '-'
        ];
    }
}
