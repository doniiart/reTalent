<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    // Menampilkan halaman pengaturan umum, profil, & sistem absensi
    public function index()
    {
        $user = auth()->user();
        $employee = $user->employee;
        $departments = \App\Models\Department::all();
        $positions = \App\Models\Position::all();
        $jobTypes = \App\Models\JobType::all();
        
        $jamMasuk = \App\Models\Setting::get('jam_masuk', '08:00');
        $jamKeluar = \App\Models\Setting::get('jam_keluar', '16:00');
        $toleransiTerlambat = \App\Models\Setting::get('toleransi_terlambat', '0');
        
        return view('superadmin.settings.index', compact('user', 'employee', 'departments', 'positions', 'jobTypes', 'jamMasuk', 'jamKeluar', 'toleransiTerlambat'));
    }

    // Update pengaturan jam kerja & toleransi absensi & sinkronisasi database realtime
    public function updateAttendance(Request $request)
    {
        $request->validate([
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i',
            'toleransi_terlambat' => 'required|integer|min:0',
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'jam_masuk'], ['value' => $request->jam_masuk]);
        \App\Models\Setting::updateOrCreate(['key' => 'jam_keluar'], ['value' => $request->jam_keluar]);
        \App\Models\Setting::updateOrCreate(['key' => 'toleransi_terlambat'], ['value' => $request->toleransi_terlambat]);

        // Sinkronkan seluruh status data absensi di database secara realtime
        $jamMasuk = $request->jam_masuk;
        $toleransi = (int) $request->toleransi_terlambat;
        $batasWaktu = \Carbon\Carbon::parse($jamMasuk)->addMinutes($toleransi)->format('H:i:s');

        $attendances = \App\Models\Attendance::whereNotNull('time_in')
            ->whereIn('status', ['present', 'late', 'hadir', 'terlambat'])
            ->get();

        foreach ($attendances as $att) {
            $checkIn = \Carbon\Carbon::parse($att->time_in)->format('H:i:s');
            $newStatus = ($checkIn <= $batasWaktu) ? 'present' : 'late';
            if ($att->status !== $newStatus) {
                $att->update(['status' => $newStatus]);
            }
        }

        return back()->with('success', 'Pengaturan absensi berhasil diperbarui dan data absensi telah disinkronkan secara real-time.');
    }

    // Update profil akun sendiri
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->update(['name' => $request->name]);

        if ($user->employee) {
            $user->employee->update([
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // Ubah kata sandi / password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    // Upload & ganti foto profil
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_path' => $path]);
            if ($user->employee) {
                $user->employee->update(['photo' => $path]);
            }
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    // Update preferensi notifikasi pengguna
    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'notify_email' => $request->has('notify_email') ? filter_var($request->notify_email, FILTER_VALIDATE_BOOLEAN) : false,
            'notify_attendance' => $request->has('notify_attendance') ? filter_var($request->notify_attendance, FILTER_VALIDATE_BOOLEAN) : false,
            'notify_leave' => $request->has('notify_leave') ? filter_var($request->notify_leave, FILTER_VALIDATE_BOOLEAN) : false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Preferensi notifikasi berhasil diperbarui.',
                'notify_email' => $user->notify_email,
                'notify_attendance' => $user->notify_attendance,
                'notify_leave' => $user->notify_leave,
            ]);
        }

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui.');
    }
}
