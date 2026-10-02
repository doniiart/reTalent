<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk data aset kantor yang dipinjamkan ke pengguna
class Asset extends Model
{
    protected $fillable = ['user_id', 'name', 'serial_number', 'description'];

    // Relasi balik ke pengguna pemilik/penanggung jawab aset
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
