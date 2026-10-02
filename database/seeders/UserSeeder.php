<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Ahmed Shahebuddin',
                'email' => 'ahmed@retalent.id',
                'password' => Hash::make('password123'),
                'role' => 'User',
                'jabatan' => 'Human Growth',
                'no_telepon' => '081234567890',
            ],
            [
                'name' => 'Upik Handayani',
                'email' => 'upik@retalent.id',
                'password' => Hash::make('password123'),
                'role' => 'User',
                'jabatan' => 'Sales Leader',
                'no_telepon' => '082198765432',
            ],
        ]);
    }
}