<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;


class UserController extends Controller
{


        public function leave()
    {
        // Mengarahkan ke file resources/views/user/leave.blade.php
        $riwayatCuti = \App\Models\Leave::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

    // Kirim data tersebut ke tampilan (view) menggunakan compact
    return view('user.leave', compact('riwayatCuti'));
    }

    public function dashboard()
    {
        $bulanIni = \Carbon\Carbon::now()->month;
    $tahunIni = \Carbon\Carbon::now()->year;

    // 1. Hitung berapa kali user absen masuk di bulan ini
    $kehadiranBulanIni = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('tanggal', $bulanIni)
                        ->whereYear('tanggal', $tahunIni)
                        ->count();

    // 2. Tentukan target hari kerja dalam sebulan (sesuai desain statismu: 21)
    $totalHariKerja = 21;

    // 3. Hitung persentase kehadiran
    $persentaseKehadiran = 0;
    if ($totalHariKerja > 0) {
        $persentaseKehadiran = ($kehadiranBulanIni / $totalHariKerja) * 100;
        // Pastikan persentase tidak lebih dari 100% jika user lembur/masuk di hari libur
        if ($persentaseKehadiran > 100) {
            $persentaseKehadiran = 100;
        }

    {    $bulanIni = \Carbon\Carbon::now()->month;
    $tahunIni = \Carbon\Carbon::now()->year;

    $kehadiranBulanIni = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereMonth('tanggal', $bulanIni)
                        ->whereYear('tanggal', $tahunIni)
                        ->count();

    $totalHariKerja = 21;
    $persentaseKehadiran = 0;
    if ($totalHariKerja > 0) {
        $persentaseKehadiran = ($kehadiranBulanIni / $totalHariKerja) * 100;
        if ($persentaseKehadiran > 100) {
            $persentaseKehadiran = 100;
        }
    }

    // --- 2. Logika Slip Gaji Terbaru (Baru ditambahkan) ---
    // Mengambil 1 data gaji paling terakhir (berdasarkan waktu dibuat)
    $slipGajiTerbaru = \App\Models\Payroll::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->first();

    // Tambahkan variabel $slipGajiTerbaru ke dalam compact
    return view('user.dashboard', compact('kehadiranBulanIni', 'totalHariKerja', 'persentaseKehadiran', 'slipGajiTerbaru'));}
    }

    // Kirim data ke tampilan dashboard
    return view('user.dashboard', compact('kehadiranBulanIni', 'totalHariKerja', 'persentaseKehadiran'));
    }

    public function employees(Request $request)
{
    // Mengambil query dasar
    $query = \App\Models\User::query();

    // Jika user memilih filter departemen dari dropdown
    if ($request->has('departemen') && $request->departemen != '') {
        $query->where('departemen', $request->departemen);
    }

    // Ambil semua data sesuai filter
    $employees = $query->get();

    return view('user.employees', compact('employees'));
}

    public function payroll()
{
    // Ambil 1 data gaji yang paling baru milik user yang sedang login
    $gajiTerakhir = \App\Models\Payroll::where('user_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->first();

    // Kirim data tersebut ke file tampilan
    return view('user.payroll', compact('gajiTerakhir'));
}

    public function attendance()
    {
        $hariIni = \Carbon\Carbon::today();
    
    // Data presensi untuk hari ini (untuk status kotak kiri)
    $presensiHariIni = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereDate('tanggal', $hariIni)
                        ->first();

    // Data riwayat presensi terbaru (diambil 5 data terakhir)
    $riwayatKehadiran = \App\Models\Attendance::where('user_id', auth()->id())
                        ->orderBy('tanggal', 'desc')
                        ->take(5)
                        ->get();

    return view('user.attendance', compact('presensiHariIni', 'riwayatKehadiran'));
}

    public function leaveApply()
    {
        return view('user.leave-apply');
    }

    public function settings()
    {
        return view('user.settings');
    }

    public function storeLeave(Request $request)
{
{
    // 1. Validasi dulu SEMUA data sekaligus
    $request->validate([
        'leave_type' => 'required',
        'duration' => 'required|numeric',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'required',
        'document_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    // 2. Siapkan data ke dalam array
    $data = [
        'user_id' => auth()->id(),
        'leave_type' => $request->leave_type,
        'duration' => $request->duration,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'reason' => $request->reason,
        'status' => 'Menunggu',
    ];
    
    // 3. Logika upload dokumen (Jika ada filenya)
    if ($request->hasFile('document_path')) {
        $path = $request->file('document_path')->store('dokumen_cuti', 'public');
        $data['document_path'] = $path;
    }

    // 4. Baru simpan ke database
    \App\Models\Leave::create($data);

    return redirect()->route('user.leave')->with('success', 'Pengajuan berhasil dikirim!');
}
}

    public function editProfile()
{
    dd("Tombol berhasil tersambung!");
    $user = auth()->user(); // Mengambil data user yang sedang login
    return view('user.profile-edit', compact('user'));
}

public function updateProfile(Request $request)
{
   $user = auth()->user();
    
    // Update data teks biasa
    $user->name = $request->name;
    // ... update no_telepon dll jika ada ...

    // LOGIKA UNTUK MENYIMPAN FOTO
    if ($request->hasFile('foto_profil')) {
        // Simpan file ke folder storage/app/public/profile_photos
        $path = $request->file('foto_profil')->store('profile_photos', 'public');
        
        // Simpan nama path-nya ke database
        $user->foto_profil = $path;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profil berhasil diupdate!');
}   

public function showLogin()
{
    // Memanggil file view yang ada di folder resources/views/auth/login.blade.php
    return view('auth.login'); 
}

public function showResetForm()
{
    // Ini akan memanggil file resources/views/auth/reset.blade.php
    return view('auth.reset'); 
}

public function processReset(Request $request)
{
    // 1. Validasi input (memastikan sandi baru dan konfirmasinya sama)
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    // 2. Cari user berdasarkan Email
    $user = \App\Models\User::where('email', $request->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
    }

    // 3. Ganti password dan simpan
    $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
    $user->save();

    // 4. Arahkan kembali ke halaman login dengan pesan sukses
    return redirect()->route('login')->with('success', 'Kata sandi berhasil direset! Silakan login dengan kata sandi baru.');
}

public function authenticate(Request $request)
{
    // 1. Validasi input form
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2. Cek kecocokan email dan password di database
    if (Auth::attempt($credentials)) {
        // Jika cocok, buat sesi keamanan baru
        $request->session()->regenerate();
        
        // Arahkan masuk ke dashboard!
        return redirect()->route('user.dashboard');
    }

    // 3. Jika email/password salah, kembalikan ke halaman login beserta pesan error
    return back()->withErrors([
        'email' => 'Email atau kata sandi yang Anda masukkan salah.',
    ])->onlyInput('email');
}

    public function downloadPdf()
{
    $user = auth()->user();
    
    // Samakan data rincian dengan yang ada di tampilan web
    $rincian = [
        'gaji_pokok' => 3000000,
        'tunjangan_teknis' => 500000,
        'tunjangan_laptop' => 200000,
        'tunjangan_keluarga' => 400000,
        'reward_disiplin' => 255000, 
        'reward_sholat' => 170000,
        'jht' => 120000,
    ];
    
    $total_pendapatan = array_sum($rincian);

    $data = [
        'title' => 'Slip Gaji - ' . $user->name,
        'user' => $user,
        'rincian' => $rincian,
        'total' => $total_pendapatan
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.payroll-pdf', $data);
    
    return $pdf->download('Slip-Gaji-Karyawan.pdf');
}

public function updatePassword(Request $request)
{
    // 1. Validasi inputan
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8',
    ]);

    $user = auth()->user();

    // 2. Cek apakah password lama (current_password) cocok dengan di database
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok dengan data kami.']);
    }

    // 3. Jika cocok, ganti dengan password baru yang sudah dienkripsi
    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('password_success', 'Kata sandi berhasil diperbarui!');
}

    public function updatePreferences(Request $request)
{
    $user = auth()->user();

    // Trik HTML: Jika checkbox dicentang, nilainya ada di $request. 
    // Jika tidak dicentang, nilainya kosong (null).
    // Fungsi has() akan mengecek: jika ada jadikan true (1), jika tidak ada jadikan false (0).
    $user->update([
        'notif_email' => $request->has('notif_email'),
        'notif_kehadiran' => $request->has('notif_kehadiran'),
        'notif_cuti' => $request->has('notif_cuti'),
    ]);

    return back()->with('success', 'Preferensi notifikasi berhasil diperbarui!');
}

public function absenMasuk(Request $request)
{
    $hariIni = \Carbon\Carbon::today();
    
    $cekAbsen = \App\Models\Attendance::where('user_id', auth()->id())
                    ->whereDate('tanggal', $hariIni)
                    ->first();

    if ($cekAbsen) {
        return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini!');
    }

    // Gabungkan latitude dan longitude jadi satu teks
    $lokasi = $request->latitude && $request->longitude 
                ? $request->latitude . ',' . $request->longitude 
                : 'Lokasi tidak terdeteksi';

    \App\Models\Attendance::create([
        'user_id' => auth()->id(),
        'tanggal' => date('Y-m-d'),
        'waktu_masuk' => date('H:i:s'),
        'status' => 'Hadir',
        'lokasi_masuk' => $lokasi, // Simpan ke database
    ]);

    return back()->with('success', 'Presensi masuk berhasil dicatat!');
}

public function absenKeluar(Request $request)
{
    $hariIni = \Carbon\Carbon::today();
    
    // Cari data absen hari ini untuk user tersebut
    $presensi = \App\Models\Attendance::where('user_id', auth()->id())
                    ->whereDate('tanggal', $hariIni)
                    ->first();

    // Jika belum absen masuk, tolak
    if (!$presensi) {
        return back()->with('error', 'Anda belum melakukan presensi masuk hari ini!');
    }

    // Jika sudah absen pulang sebelumnya, tolak
    if ($presensi->waktu_keluar) {
        return back()->with('error', 'Anda sudah melakukan presensi pulang hari ini!');
    }

    // Gabungkan koordinat lokasi
    $lokasi = $request->latitude && $request->longitude 
                ? $request->latitude . ',' . $request->longitude 
                : 'Lokasi tidak terdeteksi';

    // Perbarui data dengan jam dan lokasi pulang
    $presensi->update([
        'waktu_keluar' => date('H:i:s'),
        'lokasi_keluar' => $lokasi,
    ]);

    return back()->with('success', 'Presensi pulang berhasil dicatat. Selamat beristirahat!');
}

}