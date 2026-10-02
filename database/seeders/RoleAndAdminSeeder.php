<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // Create superadmin user
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@retalent.com'],
            [
                'name' => 'Super Administrator',
                'nip' => 'superadmin123',
                'password' => Hash::make('12345'),
            ]
        );

        if (!$superadmin->hasRole('superadmin')) {
            $superadmin->assignRole('superadmin');
        }

        // Get or Create Department, Position, and Job Type
        $department = \App\Models\Department::firstOrCreate(['name' => 'HRD']);
        $position = \App\Models\Position::firstOrCreate(['name' => 'Manager']);
        $jobType = \App\Models\JobType::firstOrCreate(['name' => 'HR Manager']);

        // Create employee record for superadmin if not exists
        if (!$superadmin->employee) {
            \App\Models\Employee::create([
                'user_id' => $superadmin->id,
                'department_id' => $department->id,
                'position_id' => $position->id,
                'job_type_id' => $jobType->id,
                'join_date' => now(),
            ]);
        }
    }
}
