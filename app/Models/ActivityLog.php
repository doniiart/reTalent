<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk mencatat riwayat aktivitas/audit log pengguna
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity',
        'module',
        'status',
        'ip_address'
    ];

    // Relasi balik ke pengguna (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
