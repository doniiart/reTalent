<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

// Model utama Pengguna (Autentikasi & Role Management Spatie)
#[Fillable(['name', 'nip', 'email', 'password', 'profile_photo_path', 'notify_email', 'notify_attendance', 'notify_leave'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Konversi tipe data otomatis (casting).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notify_email' => 'boolean',
            'notify_attendance' => 'boolean',
            'notify_leave' => 'boolean',
        ];
    }

    // Relasi ke data Absensi pengguna
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Relasi ke data Pengajuan Cuti pengguna
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    // Relasi ke Log Aktivitas pengguna
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Relasi ke profil detail Karyawan (Employee)
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    // Relasi ke Aset kantor yang dipinjam pengguna
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
