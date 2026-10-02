<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::has('employee')->get();
        if ($users->isEmpty()) return;

        $modules = ['Cuti', 'Gaji', 'Karyawan', 'Auth'];
        $statuses = ['success', 'success', 'success', 'warning', 'error'];
        
        $activities = [
            'Cuti' => ['Menyetujui Cuti Tahunan', 'Menolak Pengajuan Cuti', 'Menyesuaikan Jatah Cuti Departemen'],
            'Gaji' => ['Update Rincian Gaji (Tunjangan)', 'Mencetak Slip Gaji', 'Generate Laporan Gaji Bulanan'],
            'Karyawan' => ['Menambah Karyawan Baru', 'Mengubah Data Karyawan', 'Menonaktifkan Akun Karyawan'],
            'Auth' => ['Gagal Login - 3x Percobaan', 'Login Berhasil', 'Reset Password Karyawan']
        ];

        for ($i = 0; $i < 50; $i++) {
            $module = $modules[array_rand($modules)];
            $status = $statuses[array_rand($statuses)];
            $activity = $activities[$module][array_rand($activities[$module])];
            $user = $users->random();
            
            // Generate some random specific ID for activity description
            if ($status == 'success' && in_array($module, ['Cuti', 'Karyawan'])) {
                $activity .= " - ID: #" . rand(1000, 9999);
            }

            // Distribute dates mostly today, some yesterday
            $date = rand(0, 10) > 2 ? Carbon::today()->addHours(rand(8, 17))->addMinutes(rand(0, 59)) : Carbon::yesterday()->addHours(rand(8, 17));

            ActivityLog::create([
                'user_id' => $user->id,
                'activity' => $activity,
                'module' => $module,
                'status' => $status,
                'ip_address' => '192.168.1.' . rand(10, 255),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
