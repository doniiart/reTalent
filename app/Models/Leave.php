<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk data pengajuan cuti & izin karyawan
class Leave extends Model
{
    protected $fillable = [
        'user_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'attachment'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relasi balik ke pengguna (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
