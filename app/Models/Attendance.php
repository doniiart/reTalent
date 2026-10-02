<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk rekaman absensi harian karyawan (Clock In / Clock Out & GPS/Foto)
class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'status',
        'lat_in',
        'long_in',
        'lat_out',
        'long_out',
        'photo_in',
        'photo_out',
        'location',
    ];

    // Relasi balik ke pengguna (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
