<?php

use App\Models\User;
use App\Models\JobType;
use App\Models\Employee;

// 1. Delete all dummy users (which cascades and deletes Employees, Attendances, Leaves, Payrolls)
$users = User::where('email', '!=', 'superadmin@retalent.com')->get();
foreach ($users as $user) {
    // Delete employee photos if they exist
    if ($user->employee && $user->employee->photo) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($user->employee->photo);
    }
    // Delete user profile photo
    if ($user->profile_photo_path) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
    }
    $user->delete(); // This triggers cascade deletes on employee, attendances, leaves if set up correctly
}

// 2. To be absolutely sure, delete any employees that don't belong to superadmin
Employee::whereHas('user', function($q) {
    $q->where('email', '!=', 'superadmin@retalent.com');
})->delete();

// 3. Delete specific job types
$jobTypesToDelete = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
JobType::whereIn('name', $jobTypesToDelete)->delete();

echo "Dummy data deleted successfully.\n";
