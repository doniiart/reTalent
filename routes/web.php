<?php

use Illuminate\Support\Facades\Route;

// Redirect halaman utama (/) ke route dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Routing dashboard berdasarkan role pengguna (SuperAdmin, Admin, atau User)
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    return redirect()->route('login');
})->name('dashboard');

// Route notifikasi & live search umum (Untuk Semua Role yang Login)
Route::middleware('auth')->group(function () {
    Route::get('/live-search', [\App\Http\Controllers\SearchController::class, 'liveSearch'])->name('live.search');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.readAll');
});

// ========================================================================
// RUTE SUPERADMIN (Akses penuh untuk SuperAdmin & Admin)
// ========================================================================
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth', 'role:superadmin|admin'])->group(function () {
    // Dashboard SuperAdmin
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
    
    // CRUD & Import/Export Karyawan
    Route::post('/employees/import', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/template', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'downloadTemplate'])->name('employees.template');
    Route::get('/employees/export', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/employees/{employee}/download', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'downloadProfile'])->name('employees.download');
    Route::resource('employees', \App\Http\Controllers\SuperAdmin\EmployeeController::class);
    
    // Kehadiran (rekap, clock in/out, reset today, export, & import)
    Route::get('/attendance', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/clock-out', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
    Route::post('/attendance/reset-today', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'resetToday'])->name('attendance.reset_today');
    Route::get('/attendance/export', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'export'])->name('attendance.export');
    Route::post('/attendance/import', [\App\Http\Controllers\SuperAdmin\AttendanceController::class, 'import'])->name('attendance.import');

    // Manajemen Cuti (daftar, ajukan, export, kuota departemen, & status)
    Route::get('/leave', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/export', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'export'])->name('leave.export');
    Route::post('/leave/departments/update-quota', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'updateQuota'])->name('departments.update_quota');
    Route::patch('/leave/{leave}/status', [\App\Http\Controllers\SuperAdmin\LeaveController::class, 'updateStatus'])->name('leave.update_status');

    // Penggajian (rekap, export, proses status, update gaji, & download slip)
    Route::get('/payroll', [\App\Http\Controllers\SuperAdmin\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/export', [\App\Http\Controllers\SuperAdmin\PayrollController::class, 'export'])->name('payroll.export');
    Route::post('/payroll/process', [\App\Http\Controllers\SuperAdmin\PayrollController::class, 'process'])->name('payroll.process');
    Route::put('/payroll/{payroll}', [\App\Http\Controllers\SuperAdmin\PayrollController::class, 'update'])->name('payroll.update');
    Route::get('/payroll/{payroll}/download', [\App\Http\Controllers\SuperAdmin\PayrollController::class, 'downloadPayslip'])->name('payroll.download');

    // Pengumuman Perusahaan (CRUD)
    Route::resource('announcements', \App\Http\Controllers\SuperAdmin\AnnouncementController::class);


    // Log Aktivitas / Monitoring Sistem & Export
    Route::get('/monitoring', [\App\Http\Controllers\SuperAdmin\ActivityLogController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/export', [\App\Http\Controllers\SuperAdmin\ActivityLogController::class, 'export'])->name('monitoring.export');

    // Pengaturan Akun & Master Data (Departemen, Jabatan, Jenis Pekerjaan)
    Route::get('/settings', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/photo', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'updatePhoto'])->name('settings.photo');
    Route::post('/settings/attendance', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'updateAttendance'])->name('settings.attendance.update');
    Route::post('/settings/notifications', [\App\Http\Controllers\SuperAdmin\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
    
    Route::post('/settings/departments', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'storeDepartment'])->name('settings.departments.store');
    Route::delete('/settings/departments/{department}', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'destroyDepartment'])->name('settings.departments.destroy');
    Route::post('/settings/positions', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'storePosition'])->name('settings.positions.store');
    Route::delete('/settings/positions/{position}', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'destroyPosition'])->name('settings.positions.destroy');
    Route::post('/settings/job-types', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'storeJobType'])->name('settings.job_types.store');
    Route::delete('/settings/job-types/{jobType}', [\App\Http\Controllers\SuperAdmin\SettingController::class, 'destroyJobType'])->name('settings.job_types.destroy');
});

// ========================================================================
// RUTE ADMIN (Akses untuk Admin / HRD)
// ========================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin|admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees/export', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'export'])->name('employees.export');
    Route::get('/employees/{employee}/download', [\App\Http\Controllers\SuperAdmin\EmployeeController::class, 'downloadProfile'])->name('employees.download');
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)->except(['index']);
    Route::get('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
    
    // Kehadiran Karyawan & Pribadi
    Route::get('/attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/personal', [\App\Http\Controllers\Admin\AttendanceController::class, 'personal'])->name('attendance.personal');
    Route::post('/attendance/clock-in', [\App\Http\Controllers\Admin\AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/clock-out', [\App\Http\Controllers\Admin\AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
    Route::post('/attendance/reset-today', [\App\Http\Controllers\Admin\AttendanceController::class, 'resetToday'])->name('attendance.reset_today');
    Route::get('/attendance/export', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.export');
    Route::post('/attendance/import', [\App\Http\Controllers\Admin\AttendanceController::class, 'import'])->name('attendance.import');

    // Manajemen Cuti (Persetujuan Cuti)
    Route::get('/leave', [\App\Http\Controllers\Admin\LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [\App\Http\Controllers\Admin\LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [\App\Http\Controllers\Admin\LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/export', [\App\Http\Controllers\Admin\LeaveController::class, 'export'])->name('leave.export');
    Route::patch('/leave/{leave}/status', [\App\Http\Controllers\Admin\LeaveController::class, 'updateStatus'])->name('leave.update_status');

    // Penggajian (Daftar & Download Slip)
    Route::get('/payroll', [\App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/{payroll}/download', [\App\Http\Controllers\Admin\PayrollController::class, 'downloadPayslip'])->name('payroll.download');

    // Pengumuman Perusahaan
    Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);


    // Pengaturan Akun Admin
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/photo', [\App\Http\Controllers\Admin\SettingsController::class, 'updatePhoto'])->name('settings.photo');
    Route::post('/settings/attendance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAttendance'])->name('settings.attendance.update');
    Route::post('/settings/notifications', [\App\Http\Controllers\Admin\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
});

// ========================================================================
// RUTE KARYAWAN (USER) (Akses mandiri karyawan biasa)
// ========================================================================
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    // Dashboard Karyawan
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees', [\App\Http\Controllers\User\EmployeeController::class, 'index'])->name('employees.index');
    
    // Absensi Mandiri (Clock In & Out & Reset Today)
    Route::get('/attendance', [\App\Http\Controllers\User\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [\App\Http\Controllers\User\AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/clock-out', [\App\Http\Controllers\User\AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
    Route::post('/attendance/reset-today', [\App\Http\Controllers\User\AttendanceController::class, 'resetToday'])->name('attendance.reset_today');

    // Pengajuan Cuti Mandiri
    Route::get('/leave', [\App\Http\Controllers\User\LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/create', [\App\Http\Controllers\User\LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [\App\Http\Controllers\User\LeaveController::class, 'store'])->name('leave.store');

    // Slip Gaji & Download Slip
    Route::get('/payroll', [\App\Http\Controllers\User\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/{payroll}/download', [\App\Http\Controllers\User\PayrollController::class, 'downloadPayslip'])->name('payroll.download');

    // Pengumuman (Hanya Lihat)
    Route::resource('announcements', \App\Http\Controllers\User\AnnouncementController::class)->only(['index', 'show']);


    // Pengaturan Profil Karyawan
    Route::get('/settings', [\App\Http\Controllers\User\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\User\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [\App\Http\Controllers\User\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/photo', [\App\Http\Controllers\User\SettingsController::class, 'updatePhoto'])->name('settings.photo');
    Route::post('/settings/notifications', [\App\Http\Controllers\User\SettingsController::class, 'updateNotifications'])->name('settings.notifications');
});

// ========================================================================
// RUTE PROFIL & ASET KANTOR
// ========================================================================
Route::middleware('auth')->group(function () {
    // Edit & hapus profil user
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/notifications', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();
        $user->update([
            'notify_email' => $request->has('notify_email') ? filter_var($request->notify_email, FILTER_VALIDATE_BOOLEAN) : false,
            'notify_attendance' => $request->has('notify_attendance') ? filter_var($request->notify_attendance, FILTER_VALIDATE_BOOLEAN) : false,
            'notify_leave' => $request->has('notify_leave') ? filter_var($request->notify_leave, FILTER_VALIDATE_BOOLEAN) : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Preferensi notifikasi berhasil diperbarui.',
                'notify_email' => $user->notify_email,
                'notify_attendance' => $user->notify_attendance,
                'notify_leave' => $user->notify_leave,
            ]);
        }

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui.');
    })->name('profile.notifications');
    
    // CRUD Aset Kantor
    Route::post('/assets', [\App\Http\Controllers\AssetController::class, 'store'])->name('assets.store');
    Route::put('/assets/{asset}', [\App\Http\Controllers\AssetController::class, 'update'])->name('assets.update');
    Route::delete('/assets/{asset}', [\App\Http\Controllers\AssetController::class, 'destroy'])->name('assets.destroy');
});

// Import Rute Autentikasi (auth.php)
require __DIR__.'/auth.php';
