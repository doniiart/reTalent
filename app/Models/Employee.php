<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk data profil detail karyawan (gaji, posisi, departemen, dsb)
class Employee extends Model
{
    protected $fillable = [
        'user_id', 'position_id', 'department_id', 'job_type_id', 'photo', 'phone', 
        'address', 'last_education',
        'work_location', 'employment_status', 'join_date',
        'basic_salary', 'premium', 'tech_allowance', 'laptop_allowance',
        'family_allowance', 'discipline_reward', 'prayer_reward', 'is_imported'
    ];

    // Relasi ke User, Departemen, Jabatan, & Jenis Pekerjaan
    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function position() { return $this->belongsTo(Position::class); }
    public function jobType() { return $this->belongsTo(JobType::class); }
}
