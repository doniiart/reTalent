<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="text-[13px] text-gray-500 font-medium mb-1">Beranda / <span class="text-[#18398a] font-bold">Dashboard Karyawan</span></div>
            <h1 class="text-[28px] font-extrabold text-gray-900 tracking-tight mb-1">Dashboard Karyawan</h1>
            <p class="text-[14px] text-gray-600 font-medium">Selamat datang kembali, {{ Auth::user()->name }}. Berikut ringkasan aktivitas Anda hari ini.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="bg-white border border-gray-200 text-[#18398a] font-bold px-4 py-2.5 rounded-lg flex items-center text-[14px] shadow-sm">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
            </div>
            <a href="{{ route('superadmin.leave.index') }}" class="bg-[#18398a] hover:bg-[#122b6b] text-white font-bold px-5 py-2.5 rounded-lg flex items-center text-[14px] shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajukan Cuti
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between h-[150px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-[14px] font-bold text-gray-700 mb-1">Sisa Cuti Tahunan</h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[36px] font-extrabold text-gray-900 leading-none">{{ $sisaCuti }}</span>
                        <span class="text-[14px] font-medium text-gray-500">Hari</span>
                    </div>
                </div>
                <div class="bg-[#eff4ff] p-3 rounded-lg text-[#18398a]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-green-600 text-[13px] font-bold">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Tersedia hingga Des {{ \Carbon\Carbon::now()->year }}
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between h-[150px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-[14px] font-bold text-gray-700 mb-1">Kehadiran Bulan Ini</h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-[36px] font-extrabold text-gray-900 leading-none">{{ $attendancePercentage }}</span>
                        <span class="text-[18px] font-bold text-gray-500">%</span>
                    </div>
                </div>
                <div class="bg-[#eff4ff] p-3 rounded-lg text-[#18398a]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-center text-gray-500 text-[13px] font-medium">
                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $attendedDays }}/{{ $totalWorkDays }} Hari Kerja
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between h-[150px]">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-[14px] font-bold text-gray-700 mb-1">Slip Gaji Terbaru</h3>
                    <div class="text-[20px] font-extrabold text-gray-900 mt-2">
                        {{ $payrollDate }}
                    </div>
                </div>
                <div class="bg-[#eff4ff] p-3 rounded-lg text-[#18398a]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div>
                <a href="{{ route('superadmin.payroll.index') }}" class="text-[#18398a] hover:text-[#122b6b] text-[14px] font-bold inline-flex items-center group">
                    Lihat Rincian 
                    <svg class="w-4 h-4 ml-1.5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Statistik Kehadiran Mingguan -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-[16px] font-bold text-gray-900">Statistik Kehadiran Mingguan</h2>
                    <p class="text-[13px] text-gray-500 font-medium">Perbandingan jam kerja aktual vs target.</p>
                </div>
                <select class="border border-gray-300 rounded-lg text-[13px] font-medium text-gray-700 py-2 px-3 focus:ring-[#18398a] focus:border-[#18398a]">
                    <option>7 Hari Terakhir</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            
            <div class="h-[220px] flex items-end justify-between px-2 gap-4">
                <!-- Simple mocked bar chart -->
                @foreach($chartDays as $index => $day)
                <div class="flex flex-col items-center flex-1">
                    <div class="w-full bg-[#eff4ff] rounded-t-sm relative h-[180px]">
                        @if($chartHeights[$index] > 0)
                        <div class="absolute bottom-0 w-full bg-[#18398a] rounded-t-sm" style="height: {{ $chartHeights[$index] }}%"></div>
                        @endif
                    </div>
                    <span class="text-[12px] font-medium text-gray-500 mt-3">{{ $day }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">

            <!-- Aktivitas Terkini -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-[15px] font-bold text-gray-900 mb-5">Aktivitas Terkini</h2>
                <div class="space-y-5">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start">
                        @if($activity['type'] == 'success')
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        @elseif($activity['type'] == 'info')
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        @else
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        @endif
                        
                        <div>
                            <div class="text-[13px] font-bold text-gray-800">{{ $activity['title'] }}</div>
                            <div class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $activity['time'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Status Pengajuan Cuti -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-[16px] font-bold text-gray-900">Status Pengajuan Cuti</h2>
            <a href="{{ route('superadmin.leave.index') }}" class="text-[13px] font-bold text-[#18398a] hover:text-[#122b6b]">Lihat Semua Riwayat</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-4 px-6 text-[12px] font-bold text-gray-700 uppercase tracking-wider bg-gray-50/50">Jenis Cuti</th>
                        <th class="py-4 px-6 text-[12px] font-bold text-gray-700 uppercase tracking-wider bg-gray-50/50">Tanggal</th>
                        <th class="py-4 px-6 text-[12px] font-bold text-gray-700 uppercase tracking-wider bg-gray-50/50">Durasi</th>
                        <th class="py-4 px-6 text-[12px] font-bold text-gray-700 uppercase tracking-wider bg-gray-50/50">Alasan</th>
                        <th class="py-4 px-6 text-[12px] font-bold text-gray-700 uppercase tracking-wider bg-gray-50/50">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($leaveRequests as $req)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-[13px] font-bold text-gray-900">{{ $req->type }}</td>
                        <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $req->date_range }}</td>
                        <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $req->duration }}</td>
                        <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $req->reason }}</td>
                        <td class="py-4 px-6">
                            @if($req->status == 'MENUNGGU')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-yellow-100 text-yellow-800 tracking-wider">
                                    MENUNGGU
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-green-100 text-green-800 tracking-wider">
                                    DISETUJUI
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pengumuman Row -->
    <div class="bg-white rounded-[14px] shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 px-7 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="font-extrabold text-gray-900 text-lg">Pengumuman Terbaru</h3>
                <p class="text-[13px] text-gray-500 mt-1 font-medium">Informasi terkini dari perusahaan</p>
            </div>
            <a href="{{ route('superadmin.announcements.index') }}" class="text-[13px] font-bold text-[#18398a] hover:text-[#122b6b]">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-[12px] font-bold border-b border-gray-100 uppercase tracking-wider bg-gray-50/50">
                        <th class="py-4 px-7">JUDUL</th>
                        <th class="py-4 px-4">TANGGAL</th>
                        <th class="py-4 px-7">OLEH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                        <tr x-data="{ open: false }" @click="open = true" class="hover:bg-gray-50 transition-colors cursor-pointer group">
                            <td class="py-4 px-7">
                                <div class="font-bold text-[#0c2f82] group-hover:underline text-[13.5px] block mb-0.5">{{ $announcement->title }}</div>
                                <div class="text-[12px] text-gray-500 truncate max-w-md">{{ Str::limit(strip_tags($announcement->content), 80) }}</div>
                                
                                <!-- Modal -->
                                <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click.stop="open = false" aria-hidden="true"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="open" @click.stop x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100 whitespace-normal cursor-default">
                                            <div class="bg-white px-6 pt-6 pb-6">
                                                <div class="flex items-start justify-between border-b border-gray-100 pb-4 mb-4">
                                                    <div class="flex items-center">
                                                        @if($announcement->images && count($announcement->images) > 0)
                                                            <div class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden border border-gray-200">
                                                                <img src="{{ Storage::url($announcement->images[0]) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                                            </div>
                                                        @else
                                                            <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-50 text-blue-600">
                                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div class="ml-4">
                                                            <h3 class="text-xl font-extrabold text-gray-900" id="modal-title">
                                                                {{ $announcement->title }}
                                                            </h3>
                                                            <div class="mt-1 flex items-center text-[13px] text-gray-500 font-medium space-x-2">
                                                                <span>{{ $announcement->created_at->translatedFormat('d M Y') }}</span>
                                                                <span>&bull;</span>
                                                                <span>{{ $announcement->user->name ?? 'Admin' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button @click.stop="open = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                                
                                                @if($announcement->images && count($announcement->images) > 0)
                                                    <div x-data="{ activeIndex: 0, images: {{ json_encode(array_map(fn($path) => Storage::url($path), $announcement->images)) }} }" @click.stop class="relative w-full h-48 sm:h-64 mb-5 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                                        <template x-for="(image, index) in images" :key="index">
                                                            <img x-show="activeIndex === index" :src="image" alt="Announcement Image" class="w-full h-full object-contain" x-transition.opacity.duration.300ms>
                                                        </template>
                                                        
                                                        <template x-if="images.length > 1">
                                                            <div class="absolute inset-0 flex items-center justify-between p-3">
                                                                <button @click="activeIndex = activeIndex === 0 ? images.length - 1 : activeIndex - 1" class="p-1.5 rounded-full bg-white/70 hover:bg-white text-gray-800 shadow transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                                </button>
                                                                <button @click="activeIndex = activeIndex === images.length - 1 ? 0 : activeIndex + 1" class="p-1.5 rounded-full bg-white/70 hover:bg-white text-gray-800 shadow transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                        
                                                        <template x-if="images.length > 1">
                                                            <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-1.5">
                                                                <template x-for="(image, index) in images" :key="index">
                                                                    <button @click="activeIndex = index" :class="{'bg-[#4a6bdf]': activeIndex === index, 'bg-gray-300': activeIndex !== index}" class="w-2 h-2 rounded-full shadow-sm transition-colors"></button>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>
                                                @endif
                                                
                                                <div class="text-[14px] text-gray-700 leading-relaxed prose prose-blue max-w-none">
                                                    {!! nl2br(e($announcement->content)) !!}
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                                                <button type="button" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-5 py-2.5 bg-white text-[13.5px] font-bold text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors" @click.stop="open = false">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-[13px] text-gray-700 font-medium">
                                {{ $announcement->created_at->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-7 text-[13px] text-gray-700 font-medium">
                                {{ $announcement->user->name ?? 'Admin' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-[13.5px] text-gray-500 font-medium">
                                Belum ada pengumuman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
