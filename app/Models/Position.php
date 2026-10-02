<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk master data Jabatan / Posisi pekerjaan
class Position extends Model
{
    protected $fillable = ['name', 'description'];

    // Relasi ke banyak karyawan (Employee)
    public function employees() { return $this->hasMany(Employee::class); }
}
