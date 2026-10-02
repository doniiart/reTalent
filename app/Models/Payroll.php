<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Model untuk rekaman penggajian (Payroll) bulanan karyawan
class Payroll extends Model
{
    protected $fillable = [
        'user_id', 'month', 'year', 'basic_salary', 'premium', 
        'total_allowance', 'total_reward', 'jht_deduction', 
        'net_salary', 'status'
    ];

    // Relasi balik ke pengguna (User)
    public function user() { return $this->belongsTo(User::class); }
}
