<x-app-layout>
<!-- Leaflet CSS & JS for Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div x-data="attendanceApp()" x-init="initApp()" class="max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Kehadiran Saya</h1>
            <p class="text-[14px] text-gray-500 font-medium mt-1">Pantau waktu kerja dan absensi harian Anda di sini.</p>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="text-right hidden md:block">
                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">Waktu Sekarang</span>
                <span x-text="currentTime" class="text-[20px] font-extrabold text-[#18398a] tabular-nums">--:--:-- WIB</span>
            </div>
            
            @if(!$todayAttendance || !$todayAttendance->time_in)
                <!-- Belum Clock In -->
                <button @click="openModal('in')" class="inline-flex items-center justify-center px-6 py-3 bg-[#18398a] hover:bg-[#122b6b] text-white text-[15px] font-bold rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Clock-In Sekarang
                </button>
            @elseif($todayAttendance->time_in && !$todayAttendance->time_out)
                <!-- Sudah Clock In, Belum Clock Out -->
                <button @click="openModal('out')" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-[15px] font-bold rounded-xl transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Clock-Out Selesai
                </button>
            @else
                <!-- Sudah Clock Out -->
                <div class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-500 text-[15px] font-bold rounded-xl border border-gray-200 cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tugas Selesai Hari Ini
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Column -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Total Jam Kerja -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center relative z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider bg-gray-50 px-2 py-1 rounded">Bulan Ini</span>
                </div>
                <div class="relative z-10">
                    <h3 class="text-[13px] font-bold text-gray-500 mb-1">Total Jam Kerja</h3>
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-[36px] font-extrabold text-gray-900 leading-none">{{ $totalJamKerja }}</span>
                        <span class="text-[14px] font-bold text-gray-500">Jam</span>
                    </div>
                    
                    @php $percentage = min(100, ($totalJamKerja / 160) * 100); @endphp
                    <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1.5 overflow-hidden">
                        <div class="bg-[#18398a] h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-[11px] font-semibold text-gray-400">Target: 160 Jam</p>
                </div>
                <!-- bg decor -->
                <div class="absolute -right-6 -bottom-6 opacity-[0.03] pointer-events-none w-32 h-32">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path></svg>
                </div>
            </div>

            <!-- Status Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-5 gap-2">
                    <h3 class="font-bold text-gray-900 text-[15px]">Status {{ $isActualToday ? 'Hari Ini' : 'pada ' . $selectedDate->isoFormat('D MMMM YYYY') }}</h3>
                    <div class="text-[10px] font-bold text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full whitespace-nowrap border border-gray-200">
                        Jam Kerja: {{ $jamMasuk }} - {{ $jamKeluar }} (Toleransi {{ $toleransi }}m)
                    </div>
                </div>
                
                <div class="relative pl-6 space-y-6 before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                    
                    <!-- Check-in Item -->
                    <div class="relative flex items-center justify-between">
                        <div class="absolute left-0 -ml-[21px] w-4 h-4 rounded-full border-4 border-white bg-[#18398a] shadow"></div>
                        <div class="pl-2 w-full">
                            <h4 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-0.5">Check-in</h4>
                            @if($selectedAttendance && $selectedAttendance->time_in)
                                <div class="text-[16px] font-extrabold text-gray-900">{{ substr($selectedAttendance->time_in, 0, 5) }} WIB</div>
                                <div class="text-[11px] font-medium text-gray-400 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $selectedAttendance->location ?? 'Kantor Pusat' }}
                                </div>
                            @else
                                <div class="text-[16px] font-bold text-gray-400">Belum Presensi</div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Check-out Item -->
                    <div class="relative flex items-center justify-between">
                        <div class="absolute left-0 -ml-[21px] w-4 h-4 rounded-full border-4 border-white {{ ($selectedAttendance && $selectedAttendance->time_out) ? 'bg-amber-500' : 'bg-gray-300' }} shadow"></div>
                        <div class="pl-2 w-full">
                            <h4 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-0.5">Check-out</h4>
                            @if($selectedAttendance && $selectedAttendance->time_out)
                                <div class="text-[16px] font-extrabold text-gray-900">{{ substr($selectedAttendance->time_out, 0, 5) }} WIB</div>
                                <div class="text-[11px] font-medium text-gray-400 flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $selectedAttendance->location ?? 'Kantor Pusat' }}
                                </div>
                            @else
                                <div class="text-[16px] font-bold text-gray-400">Belum Presensi</div>
                            @endif
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Lokasi Map Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-900 text-[15px]">Lokasi Terakhir</h3>
                </div>
                
                @if($selectedAttendance && $selectedAttendance->lat_in)
                    <div id="staticMap" class="w-full h-40 bg-gray-100 rounded-lg overflow-hidden border border-gray-200 z-0"></div>
                @else
                    <div class="w-full h-40 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <span class="text-[12px] font-bold">Lokasi belum direkam</span>
                    </div>
                @endif
            </div>
            
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Calendar View -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-[20px] font-extrabold text-gray-900">{{ $selectedDate->locale('id')->translatedFormat('F Y') }}</h2>
                    </div>
                    <div class="flex items-center gap-4 text-[11px] font-bold text-gray-500">
                        <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-green-500"></div> Hadir</div>
                        <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-red-500"></div> Terlambat</div>
                        <div class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div> Cuti / Izin</div>
                    </div>
                </div>
                
                <!-- Simple CSS Grid Calendar -->
                <div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded-lg overflow-hidden">
                    @php
                        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        $firstDayOfMonth = $selectedDate->copy()->startOfMonth();
                        $daysInMonth = $firstDayOfMonth->daysInMonth;
                        $dayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                        
                        $attendancesByDay = [];
                        foreach($userAttendances as $att) {
                            $day = \Carbon\Carbon::parse($att->date)->day;
                            $attendancesByDay[$day] = $att;
                        }
                    @endphp
                    
                    <!-- Headers -->
                    @foreach($days as $dayName)
                        <div class="bg-gray-50 py-2 text-center text-[12px] font-bold text-gray-600 uppercase">{{ $dayName }}</div>
                    @endforeach
                    
                    <!-- Empty slots before 1st day -->
                    @for($i = 0; $i < $dayOfWeek; $i++)
                        <div class="bg-gray-50/50 p-2 h-[80px]"></div>
                    @endfor
                    
                    <!-- Days -->
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $loopDate = $firstDayOfMonth->copy()->addDays($day - 1);
                            $isSelected = $day == $selectedDate->day;
                            $isActualToday = $loopDate->isSameDay($actualToday);
                            $att = $attendancesByDay[$day] ?? null;
                        @endphp
                        <a href="{{ route('admin.attendance.personal', ['date' => $loopDate->format('Y-m-d')]) }}" class="block bg-white p-2 h-[80px] relative hover:bg-gray-50 transition {{ $isSelected ? 'ring-2 ring-inset ring-[#18398a] bg-blue-50/20' : '' }}">
                            <span class="text-[13px] font-semibold {{ $isSelected ? 'text-[#18398a]' : 'text-gray-700' }} {{ $isActualToday && !$isSelected ? 'underline decoration-2 underline-offset-2' : '' }}">{{ $day }}</span>
                            
                            @if($att)
                                <div class="absolute bottom-2 left-2 right-2 flex justify-center">
                                    @if($att->status == 'present')
                                        <div class="h-1.5 w-full bg-green-500 rounded-full" title="Hadir Tepat Waktu"></div>
                                    @elseif($att->status == 'late')
                                        <div class="h-1.5 w-full bg-red-500 rounded-full" title="Terlambat"></div>
                                    @elseif($att->status == 'on_leave')
                                        <div class="h-1.5 w-full bg-amber-500 rounded-full" title="Cuti/Izin"></div>
                                    @endif
                                </div>
                            @endif
                        </a>
                    @endfor
                    
                    <!-- Empty slots after last day to fill grid -->
                    @php
                        $remainingSlots = (7 - (($dayOfWeek + $daysInMonth) % 7)) % 7;
                    @endphp
                    @for($i = 0; $i < $remainingSlots; $i++)
                        <div class="bg-gray-50/50 p-2 h-[80px]"></div>
                    @endfor
                </div>
            </div>

            <!-- Riwayat Kehadiran Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-[15px] text-gray-900">Riwayat Kehadiran Terbaru</h3>
                    <a href="#" class="text-[13px] font-bold text-[#18398a] hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50 border-y border-gray-100 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Check-in</th>
                                <th class="px-6 py-4">Check-out</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-[13px]">
                            @forelse($recentAttendances as $att)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($att->date)->translatedFormat('l, d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $att->time_in ? substr($att->time_in, 0, 5) . ' WIB' : '-' }}</div>
                                    @if($att->time_in) <div class="text-[11px] text-gray-500 mt-0.5">{{ $att->location }}</div> @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $att->time_out ? substr($att->time_out, 0, 5) . ' WIB' : '-' }}</div>
                                    @if($att->time_out) <div class="text-[11px] text-gray-500 mt-0.5">{{ $att->location }}</div> @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($att->status == 'present')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-100 text-green-700">Tepat Waktu</span>
                                    @elseif($att->status == 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-red-100 text-red-700">Terlambat</span>
                                    @elseif($att->status == 'on_leave')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-100 text-amber-700">Cuti</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-700">Alpha</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada riwayat kehadiran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Modal Clock In / Out -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div x-show="isModalOpen" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                    <!-- Loading State -->
                    <div x-show="isLoadingLocation" class="absolute inset-0 bg-white/90 z-20 flex flex-col items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-[#18398a] mb-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-bold text-gray-700">Mencari Lokasi GPS Anda...</p>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-extrabold text-gray-900" id="modal-title">
                                Konfirmasi <span x-text="actionType === 'in' ? 'Clock-In' : 'Clock-Out'"></span>
                            </h3>
                            
                            <div class="mt-4 space-y-4">
                                <!-- Map Preview -->
                                <div>
                                    <p class="text-[12px] font-bold text-gray-500 mb-2">Lokasi Anda Saat Ini</p>
                                    <div id="modalMap" class="w-full h-40 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden relative z-0"></div>
                                    <p x-show="geoError" class="text-xs text-red-500 font-medium mt-2" x-text="geoError"></p>
                                </div>

                                <!-- Camera View for Remote -->
                                @if($workLocation == 'Remote')
                                <div x-show="!photoCaptured">
                                    <p class="text-[12px] font-bold text-gray-500 mb-2">Verifikasi Wajah (Wajib untuk Remote)</p>
                                    <div class="w-full bg-black rounded-lg overflow-hidden relative aspect-video flex items-center justify-center">
                                        <video id="webcam" class="w-full h-full object-cover" autoplay playsinline></video>
                                    </div>
                                    <button @click="takePhoto()" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 sm:text-sm">
                                        Ambil Foto
                                    </button>
                                </div>
                                
                                <div x-show="photoCaptured">
                                    <p class="text-[12px] font-bold text-gray-500 mb-2">Hasil Foto</p>
                                    <div class="w-full bg-gray-100 rounded-lg overflow-hidden relative aspect-video">
                                        <canvas id="canvas" class="hidden"></canvas>
                                        <img id="photoResult" class="w-full h-full object-cover">
                                    </div>
                                    <button @click="retakePhoto()" type="button" class="mt-3 text-sm text-blue-600 font-bold hover:underline">
                                        Ulangi Foto
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <form x-bind:action="actionType === 'in' ? '{{ route('admin.attendance.clock_in') }}' : '{{ route('admin.attendance.clock_out') }}'" method="POST" id="attendanceForm">
                        @csrf
                        <input type="hidden" name="latitude" x-model="lat">
                        <input type="hidden" name="longitude" x-model="lng">
                        <input type="hidden" name="photo" x-model="photoData">
                        
                        <button type="submit" 
                                x-bind:disabled="!lat || !lng || ('{{ $workLocation }}' === 'Remote' && !photoData)"
                                x-bind:class="{ 'opacity-50 cursor-not-allowed': (!lat || !lng || ('{{ $workLocation }}' === 'Remote' && !photoData)) }"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-[#18398a] text-base font-bold text-white hover:bg-[#122b6b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Presensi
                        </button>
                    </form>
                    <button @click="closeModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function attendanceApp() {
        return {
            currentTime: '',
            isModalOpen: false,
            actionType: 'in', // 'in' or 'out'
            isLoadingLocation: false,
            lat: null,
            lng: null,
            geoError: '',
            modalMapInstance: null,
            modalMarker: null,
            
            // Webcam logic
            stream: null,
            photoCaptured: false,
            photoData: '',
            
            initApp() {
                // Live clock
                setInterval(() => {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
                }, 1000);
                
                // Init Static Map if location exists
                @if($selectedAttendance && $selectedAttendance->lat_in)
                    setTimeout(() => {
                        const staticMap = L.map('staticMap', { zoomControl: false }).setView([{{ $selectedAttendance->lat_in }}, {{ $selectedAttendance->long_in }}], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; OpenStreetMap contributors'
                        }).addTo(staticMap);
                        L.marker([{{ $selectedAttendance->lat_in }}, {{ $selectedAttendance->long_in }}]).addTo(staticMap);
                    }, 500);
                @endif
            },
            
            openModal(type) {
                this.actionType = type;
                this.isModalOpen = true;
                this.isLoadingLocation = true;
                this.geoError = '';
                
                // Get Location
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.lat = position.coords.latitude;
                            this.lng = position.coords.longitude;
                            this.isLoadingLocation = false;
                            this.initModalMap();
                        },
                        (error) => {
                            this.isLoadingLocation = false;
                            this.geoError = 'Gagal mendapatkan lokasi: ' + error.message;
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    this.isLoadingLocation = false;
                    this.geoError = 'Geolocation tidak didukung oleh browser Anda.';
                }
                
                // Init Webcam if Remote
                @if($workLocation == 'Remote')
                    this.initWebcam();
                @endif
            },
            
            closeModal() {
                this.isModalOpen = false;
                if(this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
            },
            
            initModalMap() {
                // Timeout to ensure DOM is visible
                setTimeout(() => {
                    if(!this.modalMapInstance) {
                        this.modalMapInstance = L.map('modalMap').setView([this.lat, this.lng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(this.modalMapInstance);
                        this.modalMarker = L.marker([this.lat, this.lng]).addTo(this.modalMapInstance);
                    } else {
                        this.modalMapInstance.setView([this.lat, this.lng], 15);
                        this.modalMarker.setLatLng([this.lat, this.lng]);
                        this.modalMapInstance.invalidateSize();
                    }
                }, 300);
            },
            
            initWebcam() {
                this.photoCaptured = false;
                this.photoData = '';
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                    .then(stream => {
                        this.stream = stream;
                        const video = document.getElementById('webcam');
                        video.srcObject = stream;
                    })
                    .catch(err => {
                        console.error("Error accessing webcam: ", err);
                        alert("Tidak dapat mengakses kamera. Harap izinkan akses kamera.");
                    });
            },
            
            takePhoto() {
                const video = document.getElementById('webcam');
                const canvas = document.getElementById('canvas');
                const photo = document.getElementById('photoResult');
                
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                
                this.photoData = canvas.toDataURL('image/jpeg');
                photo.src = this.photoData;
                this.photoCaptured = true;
            },
            
            retakePhoto() {
                this.photoCaptured = false;
                this.photoData = '';
            }
        }
    }
</script>
</x-app-layout>
