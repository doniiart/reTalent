<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$superadmin = \App\Models\User::where('email', 'superadmin@retalent.com')->first();
$department = \App\Models\Department::firstOrCreate(['name' => 'HRD']);
$position = \App\Models\Position::firstOrCreate(['name' => 'Manager']);
$jobType = \App\Models\JobType::firstOrCreate(['name' => 'HR Manager']);

if (!$superadmin->employee) {
    \App\Models\Employee::create([
        'user_id' => $superadmin->id,
        'department_id' => $department->id,
        'position_id' => $position->id,
        'job_type_id' => $jobType->id,
        'join_date' => now(),
    ]);
}
echo "Done\n";
