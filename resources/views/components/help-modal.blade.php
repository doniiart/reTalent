@php
    $userRole = 'guest';
    if (auth()->check()) {
        if (auth()->user()->hasRole('superadmin')) {
            $userRole = 'superadmin';
        } elseif (auth()->user()->hasRole('admin')) {
            $userRole = 'admin';
        } else {
            $userRole = 'user';
        }
    }
@endphp

<!-- ReTalent Role-Tailored Help & Instruction Modal Component -->
<div x-show="helpOpen" 
     x-cloak
     x-data="{ 
        searchQuery: '',
        matchesSearch(text) {
            if (!this.searchQuery.trim()) return true;
            return text.toLowerCase().includes(this.searchQuery.toLowerCase());
        }
     }"
     @keydown.escape.window="helpOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
     
    <!-- Backdrop Overlay -->
    <div x-show="helpOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="helpOpen = false"
         class="fixed inset-0 bg-gray-900/65 backdrop-blur-sm transition-opacity">
    </div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-5 text-center">
        <div x-show="helpOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left align-middle shadow-2xl transition-all border border-gray-100 my-8">
            
            <!-- Modal Header with Dynamic Role Gradient & Title -->
            <div class="bg-gradient-to-r from-[#0a297a] via-[#18398a] to-[#2563eb] px-6 py-5 sm:px-8 text-white relative">
                <div class="flex items-center justify-between pr-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 text-white shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white flex items-center gap-2">
                                @if($userRole === 'superadmin')
                                    Pusat Bantuan - Super Admin
                                @elseif($userRole === 'admin')
                                    Pusat Bantuan - Admin HR
                                @elseif($userRole === 'user')
                                    Pusat Bantuan - Karyawan
                                @else
                                    Pusat Bantuan - Login & Akses
                                @endif
                            </h3>
                            <p class="text-xs sm:text-sm text-blue-100 mt-0.5 font-medium">
                                @if($userRole === 'superadmin')
                                    Petunjuk khusus Super Admin untuk mengelola sistem, role, audit trail & konfigurasi enterprise
                                @elseif($userRole === 'admin')
                                    Petunjuk khusus Admin HR untuk pengolahan data karyawan, absensi, cuti & penggajian
                                @elseif($userRole === 'user')
                                    Petunjuk penggunaan aplikasi ReTalent HRIS CV.MIB khusus untuk Karyawan
                                @else
                                    Petunjuk cara masuk ke portal dan pemulihan akses akun ReTalent HRIS
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Close Button (X) -->
                <button @click="helpOpen = false" 
                        class="absolute top-5 right-5 text-blue-100 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-all focus:outline-none cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Search Filter Input inside Help Header -->
                <div class="mt-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="searchQuery" 
                           placeholder="Cari kata kunci petunjuk penggunaan (misal: Absensi, Cuti, Slip Gaji)..." 
                           class="w-full pl-10 pr-4 py-2.5 bg-white/10 border border-white/20 rounded-xl text-xs sm:text-sm text-white placeholder-blue-200/70 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all">
                </div>
            </div>

            <!-- Single Role Badge Bar (Customized per Role) -->
            <div class="bg-gray-50 border-b border-gray-200 px-6 sm:px-8 py-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-semibold text-gray-500">Kategori Panduan:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs sm:text-sm font-bold bg-[#18398a] text-white shadow-sm gap-1.5">
                        @if($userRole === 'superadmin')
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            Panduan Khusus Super Admin
                        @elseif($userRole === 'admin')
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Panduan Khusus Admin HR
                        @elseif($userRole === 'user')
                            <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Panduan Khusus Karyawan
                        @else
                            <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Panduan Login & Pemulihan Akses
                        @endif
                    </span>
                </div>
                <span class="text-[11px] font-medium text-gray-400 hidden sm:inline">CV.MIB HRIS Portal</span>
            </div>

            <!-- Modal Content Body -->
            <div class="px-6 py-6 sm:px-8 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-6">

                @if($userRole === 'user')
                    <!-- ================= ROLE KARYAWAN ================= -->
                    <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex items-start space-x-3">
                        <span class="text-2xl">👤</span>
                        <div>
                            <h4 class="text-sm font-bold text-[#18398a]">Petunjuk Penggunaan Role Karyawan</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Sebagai Karyawan, Anda dapat melakukan presensi harian, membuat pengajuan cuti, mengunduh slip gaji bulanan, serta mengakses informasi internal perusahaan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Card 1: Absensi Saya -->
                        <div x-show="matchesSearch('absensi presensi clock in out hadir kerja jam masuk')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">1</div>
                                <h5 class="font-bold text-gray-900 text-sm">Presensi & Kehadiran (Clock In / Out)</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Masuk ke menu <strong class="text-gray-800">Absensi Saya</strong> pada sidebar.</li>
                                <li>Klik tombol <strong class="text-blue-700">Presensi Masuk (Clock In)</strong> saat tiba di lokasi kerja.</li>
                                <li>Sistem mencatat waktu masuk secara otomatis dan menghitung ketepatan waktu.</li>
                                <li>Klik <strong class="text-blue-700">Presensi Keluar (Clock Out)</strong> setelah jam kerja selesai.</li>
                                <li>Tinjau riwayat kehadiran bulanan Anda pada tabel bagian bawah.</li>
                            </ul>
                        </div>

                        <!-- Card 2: Pengajuan Cuti -->
                        <div x-show="matchesSearch('cuti izin sakit permohonan kuota')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">2</div>
                                <h5 class="font-bold text-gray-900 text-sm">Pengajuan Cuti & Izin</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Buka menu <strong class="text-gray-800">Pengajuan Cuti</strong> di sidebar.</li>
                                <li>Klik tombol <strong class="text-blue-700">Ajukan Cuti Baru</strong>.</li>
                                <li>Pilih jenis cuti (Tahunan, Sakit, Alasan Penting), isi tanggal mulai & selesai.</li>
                                <li>Unggah berkas pendukung (misal: Surat Sakit Dokter) bila diperlukan.</li>
                                <li>Pantau status persetujuan dari HRD / Atasan secara real-time.</li>
                            </ul>
                        </div>

                        <!-- Card 3: Slip Gaji -->
                        <div x-show="matchesSearch('gaji slip payroll honor pendapatan pdf download')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">3</div>
                                <h5 class="font-bold text-gray-900 text-sm">Melihat & Mengunduh Slip Gaji</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Akses menu <strong class="text-gray-800">Gaji</strong> dari navigasi utama.</li>
                                <li>Pilih periode bulan dan tahun slip gaji yang ingin diperiksa.</li>
                                <li>Lihat rincian Gaji Pokok, Tunjangan, Overtime, dan Potongan.</li>
                                <li>Klik ikon <strong class="text-emerald-700">Unduh PDF</strong> untuk menyimpan dokumen slip resmi.</li>
                            </ul>
                        </div>

                        <!-- Card 4: Informasi & Pengumuman -->
                        <div x-show="matchesSearch('pengumuman berita direktori karyawan profil')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">4</div>
                                <h5 class="font-bold text-gray-900 text-sm">Direktori Karyawan & Pengumuman</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Menu <strong class="text-gray-800">Karyawan</strong>: Cari informasi & kontak rekan kerja.</li>
                                <li>Menu <strong class="text-gray-800">Pengumuman</strong>: Baca pengumuman resmi dari perusahaan.</li>
                                <li>Menu <strong class="text-gray-800">Pengaturan</strong>: Perbarui foto profil & ubah kata sandi Anda.</li>
                            </ul>
                        </div>
                    </div>

                @elseif($userRole === 'admin')
                    <!-- ================= ROLE ADMIN HR ================= -->
                    <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 flex items-start space-x-3">
                        <span class="text-2xl">🛡️</span>
                        <div>
                            <h4 class="text-sm font-bold text-[#18398a]">Petunjuk Penggunaan Role Admin HR</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Sebagai Admin HR, Anda mengelola data pegawai, menyetujui pengajuan cuti, memproses rekapitulasi absensi harian, serta mengolah slip gaji karyawan.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Card 1: Kelola Karyawan -->
                        <div x-show="matchesSearch('karyawan pegawai staf tambah edit hapus')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">1</div>
                                <h5 class="font-bold text-gray-900 text-sm">Manajemen Data Karyawan</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Buka menu <strong class="text-gray-800">Karyawan</strong> dari sidebar.</li>
                                <li>Klik <strong class="text-blue-700">+ Tambah Karyawan</strong> untuk mendaftarkan anggota staf baru.</li>
                                <li>Isi NIP, Nama, Email, Jabatan, Departemen, dan Gaji Pokok.</li>
                                <li>Edit data profil karyawan atau ubah status keaktifannya bila diperlukan.</li>
                            </ul>
                        </div>

                        <!-- Card 2: Kehadiran Rekap -->
                        <div x-show="matchesSearch('kehadiran rekap absensi jam kerja ekspor')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm">2</div>
                                <h5 class="font-bold text-gray-900 text-sm">Kehadiran Rekap & Absensi Saya</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Buka menu <strong class="text-gray-800">Kehadiran Rekap</strong> untuk pantau absensi harian staf.</li>
                                <li>Filter data berdasarkan tanggal, bulan, atau departemen.</li>
                                <li>Gunakan tombol <strong class="text-teal-700">Ekspor Laporan</strong> (PDF/Excel) untuk kebutuhan HR.</li>
                                <li>Gunakan menu <strong class="text-gray-800">Absensi Saya</strong> untuk presensi mandiri Anda.</li>
                            </ul>
                        </div>

                        <!-- Card 3: Persetujuan Cuti -->
                        <div x-show="matchesSearch('cuti izin permohonan persetujuan setujui tolak')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">3</div>
                                <h5 class="font-bold text-gray-900 text-sm">Persetujuan & Kelola Cuti</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Buka menu <strong class="text-gray-800">Cuti</strong> untuk melihat pengajuan masuk.</li>
                                <li>Periksa sisa kuota, tanggal permohonan, dan berkas lampiran.</li>
                                <li>Klik <strong class="text-emerald-700">Setujui</strong> atau <strong class="text-rose-700">Tolak</strong> (sertakan catatan penolakan).</li>
                            </ul>
                        </div>

                        <!-- Card 4: Penggajian & Pengumuman -->
                        <div x-show="matchesSearch('gaji payroll slip pengumuman terbit')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">4</div>
                                <h5 class="font-bold text-gray-900 text-sm">Penggajian & Pengumuman</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Menu <strong class="text-gray-800">Gaji</strong>: Kelola rincian tunjangan & potongan slip gaji.</li>
                                <li>Menu <strong class="text-gray-800">Pengumuman</strong>: Buat & terbitkan pengumuman internal perusahaan.</li>
                            </ul>
                        </div>
                    </div>

                @elseif($userRole === 'superadmin')
                    <!-- ================= ROLE SUPER ADMIN ================= -->
                    <div class="bg-amber-50/70 border border-amber-100 rounded-xl p-4 flex items-start space-x-3">
                        <span class="text-2xl">👑</span>
                        <div>
                            <h4 class="text-sm font-bold text-[#18398a]">Petunjuk Penggunaan Role Super Admin</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Super Admin memegang wewenang tertinggi untuk manajemen hak akses/role, audit log aktivitas pengguna, reset kata sandi, dan pengaturan global holding CV.MIB.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Card 1: Manajemen Role & Pengguna -->
                        <div x-show="matchesSearch('superadmin role pengguna hak akses reset password')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">1</div>
                                <h5 class="font-bold text-gray-900 text-sm">Manajemen Hak Akses & User Role</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Akses menu <strong class="text-gray-800">Karyawan</strong>.</li>
                                <li>Ubah role akun pengguna menjadi <strong class="text-blue-700">SuperAdmin</strong>, <strong class="text-[#18398a]">Admin</strong>, atau <strong class="text-gray-700">User</strong>.</li>
                                <li>Gunakan opsi <strong class="text-rose-600">Reset Password</strong> instan untuk pengguna yang terkunci.</li>
                            </ul>
                        </div>

                        <!-- Card 2: Log Aktivitas System -->
                        <div x-show="matchesSearch('log aktivitas audit monitoring sesi session ip security')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-sm">2</div>
                                <h5 class="font-bold text-gray-900 text-sm">Log Aktivitas & Audit Security</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Akses menu <strong class="text-gray-800">Log Aktivitas</strong> (Monitoring).</li>
                                <li>Pantau sesi pengguna aktif real-time, alamat IP, dan lokasi login.</li>
                                <li>Tinjau audit trail transaksi data demi menjaga keamanan enterprise.</li>
                            </ul>
                        </div>

                        <!-- Card 3: Kuota Cuti & Payroll Enterprise -->
                        <div x-show="matchesSearch('kuota cuti departemen payroll perusahaan')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-[#18398a] text-white flex items-center justify-center font-bold text-sm">3</div>
                                <h5 class="font-bold text-gray-900 text-sm">Kuota Cuti Departemen & Gaji</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Pada menu <strong class="text-gray-800">Cuti</strong>, tetapkan kuota cuti tahunan per departemen.</li>
                                <li>Pada menu <strong class="text-gray-800">Gaji</strong>, awasi kelancaran proses payroll perusahaan.</li>
                            </ul>
                        </div>

                        <!-- Card 4: Pengaturan Global -->
                        <div x-show="matchesSearch('pengaturan sistem global holding mib')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-gray-800 text-white flex items-center justify-center font-bold text-sm">4</div>
                                <h5 class="font-bold text-gray-900 text-sm">Konfigurasi Sistem & Holding</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Akses menu <strong class="text-gray-800">Pengaturan</strong>.</li>
                                <li>Atur profil holding CV.MIB, logo aplikasi, serta kebijakan enkripsi.</li>
                            </ul>
                        </div>
                    </div>

                @else
                    <!-- ================= LOGIN & AKSES (GUEST) ================= -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start space-x-3">
                        <span class="text-2xl">🔑</span>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Petunjuk Login & Pemulihan Akses</h4>
                            <p class="text-xs text-gray-600 mt-0.5">Panduan dasar cara masuk ke dalam aplikasi ReTalent HRIS atau melakukan reset kata sandi jika lupa.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Card 1: Cara Login -->
                        <div x-show="matchesSearch('login masuk email nip password kata sandi')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">1</div>
                                <h5 class="font-bold text-gray-900 text-sm">Cara Masuk (Login)</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Buka URL aplikasi di browser web Anda.</li>
                                <li>Ketikkan <strong class="text-gray-800">Alamat Email / NIP</strong> resmi terdaftar.</li>
                                <li>Ketikkan <strong class="text-gray-800">Kata Sandi</strong> Anda.</li>
                                <li>Klik tombol <strong class="text-blue-700">Masuk</strong> untuk menuju Dashboard Role Anda.</li>
                            </ul>
                        </div>

                        <!-- Card 2: Lupa Password -->
                        <div x-show="matchesSearch('lupa password reset kata sandi email')" class="border border-gray-200 rounded-xl p-4 bg-white hover:border-blue-300 transition-all shadow-sm">
                            <div class="flex items-center space-x-3 mb-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">2</div>
                                <h5 class="font-bold text-gray-900 text-sm">Lupa Kata Sandi</h5>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1.5 list-disc list-inside">
                                <li>Klik link <strong class="text-blue-700">Lupa Password?</strong> di halaman login.</li>
                                <li>Masukkan email terdaftar untuk menerima link penyetelan ulang.</li>
                                <li>Buka pesan email dan buat kata sandi baru yang aman.</li>
                            </ul>
                        </div>
                    </div>
                @endif

            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center space-x-2 text-xs text-gray-500 font-medium">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Butuh bantuan lebih lanjut? Hubungi IT Support / HR Operations CV.MIB</span>
                </div>
                <button type="button" 
                        @click="helpOpen = false" 
                        class="w-full sm:w-auto px-6 py-2.5 bg-[#18398a] hover:bg-[#122b6b] text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                    Tutup Panduan
                </button>
            </div>

        </div>
    </div>
</div>
