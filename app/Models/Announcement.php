<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Model untuk data pengumuman perusahaan
class Announcement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'images', 'department_id', 'user_id', 'is_active'];

    protected $casts = [
        'images' => 'array',
    ];

    // Relasi ke pembuat pengumuman (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke departemen tujuan (opsional, null = semua departemen)
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
