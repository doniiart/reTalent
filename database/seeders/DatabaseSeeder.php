<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndAdminSeeder::class,
            GenerateAttendanceSeeder::class,
        ]);

        // Create Departments and Positions (Master Data)
        $it = \App\Models\Department::firstOrCreate(['name' => 'IT'], ['annual_leave_quota' => 12]);
        $hr = \App\Models\Department::firstOrCreate(['name' => 'HRD'], ['annual_leave_quota' => 12]);
        $finance = \App\Models\Department::firstOrCreate(['name' => 'Finance'], ['annual_leave_quota' => 12]);
        $marketing = \App\Models\Department::firstOrCreate(['name' => 'Marketing'], ['annual_leave_quota' => 12]);
        $operations = \App\Models\Department::firstOrCreate(['name' => 'Operations'], ['annual_leave_quota' => 12]);
        $sales = \App\Models\Department::firstOrCreate(['name' => 'Sales'], ['annual_leave_quota' => 12]);
        
        $manager = \App\Models\Position::firstOrCreate(['name' => 'Manager']);
        $staff = \App\Models\Position::firstOrCreate(['name' => 'Staff']);
        $supervisor = \App\Models\Position::firstOrCreate(['name' => 'Supervisor']);
        $director = \App\Models\Position::firstOrCreate(['name' => 'Director']);
        $intern = \App\Models\Position::firstOrCreate(['name' => 'Intern']);

        // Create Job Types (Professions)
        $professions = [
            // IT
            'Software Engineer', 'System Administrator', 'Network Engineer',
            // HRD
            'HR Manager', 'Recruitment Specialist', 'Payroll Officer',
            // Finance
            'Financial Analyst', 'Accountant', 'Tax Specialist',
            // Marketing
            'Marketing Executive', 'Digital Marketer', 'Content Creator',
            // Operations
            'Operations Manager', 'Supply Chain Analyst', 'Customer Service',
            // Sales
            'Sales Executive', 'Account Manager', 'Business Development'
        ];

        foreach ($professions as $profession) {
            \App\Models\JobType::firstOrCreate(['name' => $profession]);
        }
    }
}
