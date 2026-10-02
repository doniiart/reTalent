<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk master data Jenis Pekerjaan (WFO, Remote, Hybrid, dll)
class JobType extends Model
{
    protected $fillable = ['name'];

    // Relasi ke banyak karyawan (Employee)
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
