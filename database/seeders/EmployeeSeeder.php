<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $departemens = ['Tim Finance', 'Tim Sales', 'Tim Konten', 'Tim HG', 'Tim Marketing'];

        for ($i = 1; $i <= 23; $i++) {
            User::create([
                'name' => 'Karyawan Dummy ' . $i,
                'email' => 'karyawan' . $i . '@retalent.id',
                'password' => Hash::make('password123'),
                'jabatan' => 'Staff Regular',
                'departemen' => $departemens[array_rand($departemens)], // Pilih departemen acak
                'no_telepon' => '081234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
            ]);
        }
    }
}