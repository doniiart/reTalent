<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;

class GenerateAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure settings match user configuration (08:00 - 17:00, 15 min tolerance)
        Setting::updateOrCreate(['key' => 'jam_masuk'], ['value' => '08:00']);
        Setting::updateOrCreate(['key' => 'jam_keluar'], ['value' => '17:00']);
        Setting::updateOrCreate(['key' => 'toleransi_terlambat'], ['value' => '15']);

        // 2. Get all users except Sucipto (superadmin)
        $users = User::where('email', '!=', 'superadmin@retalent.com')
            ->where(function($q) {
                $q->whereDoesntHave('roles', function($rq) {
                    $rq->where('name', 'superadmin');
                })->orWhereHas('roles', function($rq) {
                    $rq->where('name', 'user');
                });
            })
            ->get();

        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();

        // Preset status & exact time configurations matching the attendance rules (08:00 - 17:00, tolerance 15 mins)
        // <= 08:15 is present (Hadir), > 08:15 is late (Terlambat)
        $presets = [
            ['status' => 'present',  'in' => '07:50:00', 'out' => '17:00:00'], // Hadir (Tepat waktu)
            ['status' => 'late',     'in' => '08:25:00', 'out' => '17:00:00'], // Terlambat (Lewat 08:15)
            ['status' => 'present',  'in' => '08:05:00', 'out' => '17:00:00'], // Hadir (Tepat waktu, dalam toleransi 15 min)
            ['status' => 'on_leave', 'in' => null,       'out' => null],       // Izin
            ['status' => 'present',  'in' => '07:45:00', 'out' => '17:00:00'], // Hadir (Tepat waktu)
            ['status' => 'late',     'in' => '08:35:00', 'out' => '17:00:00'], // Terlambat (Lewat 08:15)
            ['status' => 'absent',   'in' => null,       'out' => null],       // Alpa
            ['status' => 'present',  'in' => '08:10:00', 'out' => '17:00:00'], // Hadir (Tepat waktu, dalam toleransi 15 min)
        ];

        foreach ($users as $index => $user) {
            // Ensure user has 'user' role
            if (!$user->hasRole('superadmin') && !$user->hasRole('user')) {
                $user->assignRole('user');
            }

            // Pick preset for today
            $preset = $presets[$index % count($presets)];

            Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'date' => $today->toDateString(),
                ],
                [
                    'time_in'  => $preset['in'],
                    'time_out' => $preset['out'],
                    'status'   => $preset['status'],
                    'location' => 'Kantor Pusat',
                ]
            );

            // Seed working days (Monday - Saturday) for the current month up to Saturday of current week
            $endOfWeekSaturday = $today->copy()->startOfWeek(Carbon::MONDAY)->addDays(5);
            $seedEndDate = $today->gt($endOfWeekSaturday) ? $today : $endOfWeekSaturday;

            for ($d = $startOfMonth->copy(); $d->lte($seedEndDate); $d->addDay()) {
                if ($d->isSunday()) continue; // Only skip Sundays (Monday to Saturday are working days)

                $daySeed = ($user->id * 31 + $d->day) % 10;
                if ($daySeed < 6) {
                    $hStatus = 'present';
                    $hIn = '07:55:00';
                    $hOut = '17:00:00';
                } elseif ($daySeed < 8) {
                    $hStatus = 'late';
                    $hIn = '08:30:00';
                    $hOut = '17:00:00';
                } elseif ($daySeed < 9) {
                    $hStatus = 'on_leave';
                    $hIn = null;
                    $hOut = null;
                } else {
                    $hStatus = 'absent';
                    $hIn = null;
                    $hOut = null;
                }

                Attendance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'date' => $d->toDateString(),
                    ],
                    [
                        'time_in'  => $hIn,
                        'time_out' => $hOut,
                        'status'   => $hStatus,
                        'location' => 'Kantor Pusat',
                    ]
                );
            }
        }
    }
}
