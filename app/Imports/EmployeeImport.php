<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\JobType;
use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Normalisasi key array header
        $nip = $row['nip'] ?? $row['nip_karyawan'] ?? null;
        $name = $row['nama'] ?? $row['nama_lengkap'] ?? $row['name'] ?? null;
        $email = $row['email'] ?? null;

        if (!$email || !$name) {
            return null;
        }

        // Cek jika email atau NIP sudah terdaftar
        $existingUser = User::where('email', $email)
            ->when($nip, function($q) use ($nip) {
                return $q->orWhere('nip', $nip);
            })
            ->first();

        if ($existingUser) {
            // Tandai employee sebagai is_imported jika sudah ada tetapi belum bertanda
            if ($existingUser->employee) {
                $existingUser->employee->update(['is_imported' => true]);
            }
            return null; // Skip duplikasi pembuat akun baru
        }

        // Resolusi Departemen
        $deptName = $row['departemen'] ?? $row['department'] ?? null;
        $department = null;
        if ($deptName) {
            $department = Department::where('name', 'LIKE', '%' . trim($deptName) . '%')->first();
        }
        if (!$department) {
            $department = Department::first() ?? Department::create(['name' => $deptName ?: 'Umum']);
        }

        // Resolusi Posisi / Jabatan
        $posName = $row['jabatan'] ?? $row['posisi'] ?? $row['position'] ?? null;
        $position = null;
        if ($posName) {
            $position = Position::where('name', 'LIKE', '%' . trim($posName) . '%')->first();
        }
        if (!$position) {
            $position = Position::first() ?? Position::create(['name' => $posName ?: 'Staf']);
        }

        // Resolusi Tipe / Jenis Pekerjaan
        $jobTypeName = $row['jenis_pekerjaan'] ?? $row['tipe_pekerjaan'] ?? $row['job_type'] ?? null;
        $jobType = null;
        if ($jobTypeName) {
            $jobType = JobType::where('name', 'LIKE', '%' . trim($jobTypeName) . '%')->first();
        }
        if (!$jobType) {
            $jobType = JobType::first() ?? JobType::create(['name' => $jobTypeName ?: 'Full Time']);
        }

        // Formatting Tanggal Bergabung
        $joinDate = now()->format('Y-m-d');
        $rawJoinDate = $row['tanggal_bergabung'] ?? $row['join_date'] ?? null;
        if (!empty($rawJoinDate)) {
            if (is_numeric($rawJoinDate)) {
                $joinDate = Date::excelToDateTimeObject($rawJoinDate)->format('Y-m-d');
            } else {
                try {
                    $joinDate = Carbon::parse($rawJoinDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    $joinDate = now()->format('Y-m-d');
                }
            }
        }

        // Mapping Status Karyawan (tetap, kontrak, intership)
        $rawStatus = strtolower($row['status_karyawan'] ?? $row['status'] ?? 'tetap');
        $employmentStatus = 'tetap';
        if (str_contains($rawStatus, 'kontrak')) {
            $employmentStatus = 'kontrak';
        } elseif (str_contains($rawStatus, 'magang') || str_contains($rawStatus, 'intership') || str_contains($rawStatus, 'intern')) {
            $employmentStatus = 'intership';
        }

        // NIP fallback jika tidak diisi
        if (!$nip) {
            $nip = 'NIP-' . rand(100000, 999999);
        }

        // Role
        $roleName = strtolower($row['role'] ?? 'user');
        if (!in_array($roleName, ['superadmin', 'admin', 'user'])) {
            $roleName = 'user';
        }

        // Gaji Pokok & Tunjangan
        $basicSalary = floatval($row['gaji_pokok'] ?? $row['basic_salary'] ?? 0);
        $phone = $row['no_telepon'] ?? $row['telepon'] ?? $row['phone'] ?? null;
        $address = $row['alamat'] ?? $row['address'] ?? null;
        $education = $row['pendidikan_terakhir'] ?? $row['education'] ?? null;
        $workLocation = $row['lokasi_kerja'] ?? $row['work_location'] ?? 'Kantor Pusat';

        DB::transaction(function () use (
            $name, $nip, $email, $roleName, $department, $position, $jobType, 
            $phone, $address, $education, $workLocation, $employmentStatus, $joinDate, $basicSalary, &$employee
        ) {
            // 1. Buat User
            $user = User::create([
                'name' => $name,
                'nip' => $nip,
                'email' => $email,
                'password' => Hash::make('12345'),
            ]);
            $user->assignRole($roleName);

            // 2. Buat Employee dengan is_imported = true
            $employee = Employee::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'position_id' => $position->id,
                'job_type_id' => $jobType->id,
                'phone' => $phone,
                'address' => $address,
                'last_education' => $education,
                'work_location' => $workLocation,
                'employment_status' => $employmentStatus,
                'join_date' => $joinDate,
                'basic_salary' => $basicSalary,
                'is_imported' => true,
            ]);

            // 3. Draft Payroll
            $jhtDeduction = $basicSalary * 0.02;
            $netSalary = $basicSalary - $jhtDeduction;

            Payroll::create([
                'user_id' => $user->id,
                'month' => now()->format('F'),
                'year' => now()->year,
                'basic_salary' => $basicSalary,
                'premium' => 0,
                'total_allowance' => 0,
                'total_reward' => 0,
                'jht_deduction' => $jhtDeduction,
                'net_salary' => $netSalary,
                'status' => 'draft',
            ]);
        });

        return $employee;
    }
}
