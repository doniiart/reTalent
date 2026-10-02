<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $department_id;
    protected $position_id;
    protected $status;

    public function __construct($department_id = null, $position_id = null, $status = null)
    {
        $this->department_id = $department_id;
        $this->position_id = $position_id;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Employee::with(['user', 'department', 'position', 'jobType']);

        if ($this->department_id) {
            $query->where('department_id', $this->department_id);
        }

        if ($this->position_id) {
            $query->where('position_id', $this->position_id);
        }

        if ($this->status) {
            $query->where('employment_status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Lengkap',
            'Email',
            'Departemen',
            'Jabatan',
            'Jenis Pekerjaan',
            'Status Karyawan',
            'Tanggal Bergabung',
            'Lokasi Kerja',
            'No. HP',
            'Alamat',
            'Pendidikan Terakhir',
        ];
    }

    public function map($employee): array
    {
        $statusLabel = [
            'tetap' => 'Tetap',
            'kontrak' => 'Kontrak',
            'intership' => 'Magang',
        ];

        return [
            $employee->user->nip ?? '-',
            $employee->user->name ?? '-',
            $employee->user->email ?? '-',
            $employee->department->name ?? '-',
            $employee->position->name ?? '-',
            $employee->jobType->name ?? '-',
            $statusLabel[$employee->employment_status] ?? $employee->employment_status,
            $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d/m/Y') : '-',
            $employee->work_location ?? '-',
            $employee->phone ?? '-',
            $employee->address ?? '-',
            $employee->last_education ?? '-',
        ];
    }
}
