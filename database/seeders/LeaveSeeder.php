<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Leave;
use Carbon\Carbon;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::has('employee')->get();

        $reasons = [
            'Acara Keluarga',
            'Sakit Demam',
            'Sakit Perut',
            'Menjenguk Orang Tua',
            'Liburan',
            'Urusan Pribadi mendesak'
        ];

        foreach ($users as $user) {
            if ($user->nip === 'EMP004') {
                // Ensure Rina has a leave for today
                Leave::create([
                    'user_id' => $user->id,
                    'start_date' => Carbon::today()->toDateString(),
                    'end_date' => Carbon::today()->addDays(2)->toDateString(),
                    'reason' => 'Acara Keluarga',
                    'status' => 'approved'
                ]);
            } else {
                // Give some employees 1-3 leave records
                $numLeaves = rand(0, 3);

                for ($i = 0; $i < $numLeaves; $i++) {
                    $status = collect(['pending', 'approved', 'approved', 'rejected'])->random();
                    $start = Carbon::today()->subDays(rand(1, 90));
                    
                    // Some leaves are multi-day
                    $end = $start->copy()->addDays(rand(0, 3));

                    Leave::create([
                        'user_id' => $user->id,
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'reason' => $reasons[array_rand($reasons)],
                        'status' => $status
                    ]);
                }
            }
        }
    }
}
