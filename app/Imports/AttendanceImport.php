<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // row keys are based on header (e.g. "Email" -> "email", "Tanggal" -> "tanggal")
        $email = $row['email'] ?? null;
        if (!$email) return null;

        $user = User::where('email', $email)->first();
        if (!$user) return null;

        // handle date formatting (could be excel serial or string)
        $date = null;
        if (isset($row['tanggal'])) {
            if (is_numeric($row['tanggal'])) {
                $date = Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d');
            } else {
                $date = Carbon::parse($row['tanggal'])->format('Y-m-d');
            }
        }
        if (!$date) return null;

        // Map status: Hadir -> present, Terlambat -> late, Izin/Sakit -> on_leave, Alpa -> absent
        $statusInput = strtolower($row['status'] ?? '');
        $status = 'absent';
        if (str_contains($statusInput, 'hadir') || $statusInput == 'present') {
            $status = 'present';
        } elseif (str_contains($statusInput, 'terlambat') || $statusInput == 'late') {
            $status = 'late';
        } elseif (str_contains($statusInput, 'izin') || str_contains($statusInput, 'sakit') || $statusInput == 'on_leave') {
            $status = 'on_leave';
        }

        // Time In/Out
        $timeIn = null;
        if (!empty($row['jam_masuk'])) {
            if (is_numeric($row['jam_masuk'])) {
                $timeIn = Date::excelToDateTimeObject($row['jam_masuk'])->format('H:i:s');
            } else {
                $timeIn = Carbon::parse($row['jam_masuk'])->format('H:i:s');
            }
        }

        $timeOut = null;
        if (!empty($row['jam_keluar'])) {
            if (is_numeric($row['jam_keluar'])) {
                $timeOut = Date::excelToDateTimeObject($row['jam_keluar'])->format('H:i:s');
            } else {
                $timeOut = Carbon::parse($row['jam_keluar'])->format('H:i:s');
            }
        }

        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $date)
                                ->first();

        if ($attendance) {
            $attendance->update([
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'status' => $status,
                'location' => 'Kantor Pusat'
            ]);
            return null; // Return null so it doesn't create a new one
        }

        return new Attendance([
            'user_id' => $user->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'status' => $status,
            'location' => 'Kantor Pusat'
        ]);
    }
}
