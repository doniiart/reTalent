<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk master data Departemen/Divisi perusahaan
class Department extends Model
{
    protected $fillable = ['name', 'description'];

    // Relasi ke banyak karyawan (Employee)
    public function employees() { return $this->hasMany(Employee::class); }
}
