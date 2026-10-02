<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'user');
        })->get();

        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        foreach ($users as $user) {
            // Seed for today
            if ($user->nip === 'EMP003') {
                $status = 'late';
            } elseif ($user->nip === 'EMP004') {
                $status = 'on_leave';
            } else {
                $status = collect(['present', 'present', 'present', 'present', 'late', 'on_leave', 'absent'])->random();
            }
            $timeIn = null;
            $timeOut = null;
            
            if (in_array($status, ['present', 'late'])) {
                $timeIn = $status == 'present' ? '07:45:00' : '08:15:00';
                $timeOut = '17:00:00';
            }

            Attendance::updateOrCreate(
                ['user_id' => $user->id, 'date' => $today->toDateString()],
                ['time_in' => $timeIn, 'time_out' => $timeOut, 'status' => $status]
            );

            // Seed historical data for the month (Monday - Saturday)
            for ($d = $startOfMonth->copy(); $d->lte($today->copy()->subDay()); $d->addDay()) {
                if ($d->isSunday()) continue;

                $histStatus = collect(['present', 'present', 'present', 'present', 'present', 'late', 'on_leave', 'absent'])->random();
                $hTimeIn = null;
                $hTimeOut = null;

                if (in_array($histStatus, ['present', 'late'])) {
                    $hTimeIn = $histStatus == 'present' ? '07:50:00' : '08:10:00';
                    $hTimeOut = '17:05:00';
                }

                Attendance::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $d->toDateString()],
                    ['time_in' => $hTimeIn, 'time_out' => $hTimeOut, 'status' => $histStatus]
                );
            }
        }
    }
}
