<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Employee;
use App\Models\Payroll;

class NewEmployeeSalaryNotification extends Notification
{
    use Queueable;

    protected $employee;
    protected $payroll;

    public function __construct(Employee $employee, ?Payroll $payroll = null)
    {
        $this->employee = $employee;
        $this->payroll = $payroll;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $employeeName = $this->employee->user->name ?? 'Karyawan Baru';
        $payrollId = $this->payroll ? $this->payroll->id : null;
        
        $url = $payrollId 
            ? route('superadmin.payroll.index', ['target_payroll_id' => $payrollId])
            : route('superadmin.payroll.index');

        return [
            'title' => 'Atur Gaji Karyawan Baru',
            'message' => "Karyawan baru {$employeeName} telah ditambahkan oleh Admin. Klik untuk menambahkan spesifikasi gaji.",
            'url' => $url,
            'icon' => 'currency-dollar',
            'created_at' => now()->toDateTimeString()
        ];
    }
}
