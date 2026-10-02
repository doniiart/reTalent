<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$employees = \App\Models\Employee::with('user', 'user.roles')->get();
echo "Total Employees in DB: " . $employees->count() . "\n";
foreach ($employees as $emp) {
    echo "Employee ID: " . $emp->id . " - User Name: " . ($emp->user ? $emp->user->name : 'No User') . " - User Roles: " . ($emp->user ? $emp->user->roles->pluck('name')->implode(', ') : 'N/A') . "\n";
}
