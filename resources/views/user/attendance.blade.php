@extends('layouts.app')

@section('content')
@if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Header Kehadiran -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kehadiran Saya</h2>
            <p class="text-gray-500 text-sm mt-1">Pantau waktu kerja dan absensi harian Anda di sini.</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <p class="text-xs text-gray-500 font-semibold tracking-wider">WAKTU SEKARANG</p>
                <p id="realtime-clock" class="text-lg font-bold text-[#1e3a8a]">Memuat...</p>
            </div>
            @if(!$presensiHariIni || !$presensiHariIni->waktu_masuk)
            <form id="form-masuk" action="{{ route('user.attendance.masuk') }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="latitude" id="lat-masuk">
                <input type="hidden" name="longitude" id="long-masuk">
                <button type="button" onclick="getLocationAndSubmit('masuk')" class="bg-[#1e3a8a] text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center hover:bg-blue-800 transition">
                    <i data-feather="log-in" class="w-4 h-4 mr-2"></i> Presensi Masuk
                </button>
            </form>
        @elseif(!$presensiHariIni->waktu_keluar)
            <form id="form-keluar" action="{{ route('user.attendance.keluar') }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="latitude" id="lat-keluar">
                <input type="hidden" name="longitude" id="long-keluar">
                <button type="button" onclick="getLocationAndSubmit('keluar')" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center hover:bg-red-700 transition">
                    <i data-feather="log-out" class="w-4 h-4 mr-2"></i> Presensi Pulang
                </button>
            </form>
        @else
            <button disabled class="bg-gray-400 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center cursor-not-allowed">
                <i data-feather="check-circle" class="w-4 h-4 mr-2"></i> Presensi Selesai
            </button>
        @endif
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Kolom Kiri: Ringkasan & Lokasi -->
        <div class="col-span-4 space-y-6">
            <!-- Total Jam Kerja -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <div class="bg-blue-50 text-blue-600 p-2 rounded-lg"><i data-feather="clock" class="w-5 h-5"></i></div>
                    <span class="text-sm text-gray-500">Bulan Ini</span>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Jam Kerja</p>
                <h3 class="text-3xl font-bold text-gray-800">164.5 <span class="text-base text-gray-500 font-normal">Jam</span></h3>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-4 mb-2">
                    <div class="bg-[#1e3a8a] h-2 rounded-full" style="width: 82%"></div>
                </div>
                <p class="text-xs text-gray-500">Target: 200 jam</p>
            </div>

            <!-- Status Hari Ini -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-semibold text-gray-800 mb-4">Status Hari Ini</h4>
                <div class="relative border-l-2 border-[#1e3a8a] pl-4 ml-2 space-y-6">
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-[#1e3a8a] rounded-full ring-4 ring-white"></div>
                        <p class="text-sm font-semibold text-gray-800">Check-in</p>
                        <p class="text-sm font-bold text-gray-900">
                            @if($presensiHariIni && $presensiHariIni->waktu_masuk)
                                {{ \Carbon\Carbon::parse($presensiHariIni->waktu_masuk)->format('H:i') }} WIB
                            @else
                                Belum Presensi
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 flex items-center mt-1">
                            <i data-feather="map-pin" class="w-3 h-3 mr-1"></i>
                            {{ $presensiHariIni->lokasi_masuk ?? 'Belum ada data' }}
                        </p>                    
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-3 h-3 bg-gray-300 rounded-full ring-4 ring-white"></div>
                        <p class="text-sm font-semibold text-gray-500">Check-out</p>
                        <p class="font-bold text-gray-800">
                            @if($presensiHariIni && $presensiHariIni->waktu_keluar)
                                {{ \Carbon\Carbon::parse($presensiHariIni->waktu_keluar)->format('H:i') }} WIB
                            @else
                                Belum Presensi
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Map Placeholder -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                     <h4 class="font-semibold text-gray-800">Lokasi Presensi</h4>
                     <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Peta</a>
                </div>
                <div class="mt-4">
    @if($presensiHariIni && $presensiHariIni->lokasi_masuk && $presensiHariIni->lokasi_masuk != 'Lokasi tidak terdeteksi')
        @php
            // Pecah data "lat,long" menjadi dua variabel
            $coords = explode(',', $presensiHariIni->lokasi_masuk);
            $lat = $coords[0];
            $lon = $coords[1] ?? '';
        @endphp
        <iframe 
            width="100%" 
            height="200" 
            style="border:0; border-radius: 0.5rem;" 
            loading="lazy" 
            allowfullscreen 
            src="https://maps.google.com/maps?q={{ $lat }},{{ $lon }}&z=15&output=embed">
        </iframe>
    @else
        <div class="bg-gray-100 rounded-lg flex items-center justify-center border border-dashed border-gray-300 h-48">
            <span class="text-sm text-gray-400">Peta belum tersedia</span>
        </div>
    @endif
</div>
            </div>
        </div>

        <!-- Kolom Kanan: Kalender & Riwayat -->
<div class="col-span-8 space-y-6">
    
    <!-- MULAI AREA KALENDER BARU -->
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <!-- Header Kalender: Judul Bulan & Tombol Navigasi -->
        <div class="flex justify-between items-center mb-6">
            <h3 id="calendar-month-year" class="text-lg font-bold text-gray-800">Memuat...</h3>
            <div class="flex space-x-2">
                <button id="prev-month" type="button" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="next-month" type="button" class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>

        <!-- Nama-nama Hari -->
        <div class="grid grid-cols-7 gap-2 mb-2 text-center">
            <div class="text-xs font-semibold text-red-500">Min</div>
            <div class="text-xs font-semibold text-gray-500">Sen</div>
            <div class="text-xs font-semibold text-gray-500">Sel</div>
            <div class="text-xs font-semibold text-gray-500">Rab</div>
            <div class="text-xs font-semibold text-gray-500">Kam</div>
            <div class="text-xs font-semibold text-gray-500">Jum</div>
            <div class="text-xs font-semibold text-gray-500">Sab</div>
        </div>

        <!-- Area Tanggal -->
        <div id="calendar-dates" class="grid grid-cols-7 gap-2 text-center">
            <!-- Angka tanggal dirender di sini oleh JavaScript -->
        </div>
    </div>
    <!-- SELESAI AREA KALENDER BARU -->

    <!-- Tabel Riwayat Kehadiran (Kode ini tetap dibiarkan seperti aslimu) -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h4 class="font-semibold text-gray-800">Riwayat Kehadiran Terbaru</h4>
                    <a href="#" class="text-sm text-blue-600 hover:underline">Lihat Semua</a>
                </div>
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-white border-b border-gray-200 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">TANGGAL</th>
                            <th class="px-6 py-3 font-medium">CHECK-IN</th>
                            <th class="px-6 py-3 font-medium">CHECK-OUT</th>
                            <th class="px-6 py-3 font-medium">TOTAL JAM</th>
                            <th class="px-6 py-3 font-medium">STATUS</th>
                        </tr>
                    </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($riwayatKehadiran as $riwayat)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('D, d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $riwayat->waktu_masuk ? \Carbon\Carbon::parse($riwayat->waktu_masuk)->format('H:i') . ' WIB' : '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $riwayat->waktu_keluar ? \Carbon\Carbon::parse($riwayat->waktu_keluar)->format('H:i') . ' WIB' : '-' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($riwayat->waktu_masuk && $riwayat->waktu_keluar)
                                        @php
                                            $masuk = \Carbon\Carbon::parse($riwayat->waktu_masuk);
                                            $keluar = \Carbon\Carbon::parse($riwayat->waktu_keluar);
                                            $durasi = $masuk->diff($keluar);
                                        @endphp
                                        {{ $durasi->format('%hh %im') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($riwayat->status == 'Hadir')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Tepat Waktu</span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-medium">{{ $riwayat->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Belum ada riwayat kehadiran yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            
            // Mengambil jam, menit, detik dan memastikan selalu 2 digit (contoh: 09 bukan 9)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Menggabungkan format menjadi HH:MM:SS WIB
            const timeString = `${hours}:${minutes}:${seconds} WIB`;
            
            // Mengirim waktu tersebut ke elemen HTML yang memiliki ID 'realtime-clock'
            document.getElementById('realtime-clock').innerText = timeString;
        }

        // Jalankan fungsi updateClock setiap 1000 milidetik (1 detik)
        setInterval(updateClock, 1000);
        
        // Jalankan sekali saat halaman pertama kali dimuat agar tidak menunggu 1 detik pertama
        updateClock();

    function getLocationAndSubmit(jenis) {
    let formId = jenis === 'masuk' ? 'form-masuk' : 'form-keluar';
    let latId = jenis === 'masuk' ? 'lat-masuk' : 'lat-keluar';
    let longId = jenis === 'masuk' ? 'long-masuk' : 'long-keluar';

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById(latId).value = position.coords.latitude;
                document.getElementById(longId).value = position.coords.longitude;
                document.getElementById(formId).submit();
            }, 
            function(error) {
                alert('Gagal mendapatkan lokasi.');
                document.getElementById(formId).submit();
            }
        );
    } else {
        alert('Browser Anda tidak mendukung fitur lokasi.');
        document.getElementById(formId).submit();
    }
}

// --- SCRIPT UNTUK KALENDER INTERAKTIF ---
    const monthYearHeader = document.getElementById('calendar-month-year');
    const calendarDates = document.getElementById('calendar-dates');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');

    // Nama-nama bulan dalam bahasa Indonesia
    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    // Ambil tanggal hari ini sebagai titik awal
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    function renderCalendar(month, year) {
        // Kosongkan area tanggal sebelumnya
        calendarDates.innerHTML = '';
        
        // Ubah teks judul bulan dan tahun
        monthYearHeader.innerText = `${monthNames[month]} ${year}`;

        // Cari tahu hari pertama di bulan ini (0 = Minggu, 1 = Senin, dst)
        const firstDay = new Date(year, month, 1).getDay();
        
        // Cari tahu jumlah total hari di bulan ini
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // 1. Buat kotak kosong untuk menggeser tanggal 1 sesuai harinya
        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = "p-2";
            calendarDates.appendChild(emptyDiv);
        }

        // 2. Buat kotak untuk setiap tanggal dalam bulan ini
        for (let i = 1; i <= daysInMonth; i++) {
            const dateDiv = document.createElement('div');
            dateDiv.innerText = i;
            
            // Desain dasar untuk tanggal
            dateDiv.className = "py-2 text-sm rounded-lg text-gray-700 hover:bg-gray-100 cursor-pointer transition";

            // Tandai jika ini adalah tanggal hari ini
            if (i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                dateDiv.className = "py-2 text-sm rounded-lg font-bold bg-[#1e3a8a] text-white shadow-md cursor-pointer";
            }

            calendarDates.appendChild(dateDiv);
        }
    }

    // Fungsi klik tombol bulan sebelumnya (<)
    prevMonthBtn.addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    // Fungsi klik tombol bulan berikutnya (>)
    nextMonthBtn.addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    // Jalankan kalender pertama kali saat halaman dimuat
    renderCalendar(currentMonth, currentYear);
    </script>
@endsection