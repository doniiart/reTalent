{{-- Tampilan Dashboard utama SuperAdmin (ringkasan statistik, kehadiran, cuti, & pengumuman) --}}
<x-app-layout>
    <div class="max-w-[1200px] mx-auto w-full pb-12">
        <!-- Header Title -->
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Dashboard SuperAdmin</h2>
                <p class="text-xs sm:text-[14px] text-gray-500 mt-1 font-medium">Ringkasan operasional SDM hari ini, {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d M Y') }}.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <button class="px-3.5 sm:px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs sm:text-[13px] font-bold rounded-lg hover:bg-gray-50 transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Pilih Periode
                </button>
                <a href="{{ route('superadmin.employees.create') }}" class="px-3.5 sm:px-4 py-2 bg-[#0c2f82] text-white text-xs sm:text-[13px] font-bold rounded-lg hover:bg-[#0a235c] transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Karyawan
                </a>
            </div>
        </div>

        <!-- 3 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-7">
            <!-- Total Karyawan -->
            <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <p class="text-[13px] font-bold text-gray-600 mb-1">Total Karyawan</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ number_format($totalEmployees) }}</h3>
                    <div class="flex items-center text-[12px] font-bold text-[#15a34a]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        +{{ $newEmployeesThisMonth }} bulan ini
                    </div>
                </div>
                <div class="p-3.5 bg-[#eef2fc] text-[#4a6bdf] rounded-[10px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>

            <!-- Kehadiran Hari Ini -->
            <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <p class="text-[13px] font-bold text-gray-600 mb-1">Kehadiran Hari Ini</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $attendancePercentage }}%</h3>
                    <div class="flex items-center text-[12px] font-bold text-gray-500">
                        <svg class="w-3 h-3 mr-1 text-[#15a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ number_format($attendanceToday) }} hadir
                    </div>
                </div>
                <div class="p-3.5 bg-[#eef2fc] text-[#4a6bdf] rounded-[10px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>

            <!-- Tugas Tertunda -->
            <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <p class="text-[13px] font-bold text-gray-600 mb-1">Tugas Tertunda</p>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $pendingTasks }}</h3>
                    <div class="flex items-center text-[12px] font-bold text-[#dc2626]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        {{ $pendingTasks }} butuh tindakan segera
                    </div>
                </div>
                <div class="p-3.5 bg-red-50 text-red-500 rounded-[10px]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
            <!-- Ringkasan Kehadiran Mingguan -->
            <div class="lg:col-span-2 bg-white rounded-[14px] shadow-sm border border-gray-200 p-4 sm:p-7">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-base sm:text-lg">Ringkasan Kehadiran Mingguan</h3>
                        <p class="text-xs sm:text-[13px] text-gray-500 mt-0.5 sm:mt-1 font-medium">Tinjauan tingkat kehadiran di seluruh departemen</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="btnFilterHadir" 
                                onclick="toggleAttendanceFilter('hadir')" 
                                class="px-2.5 sm:px-3 py-1 bg-[#eef2fc] text-[#4a6bdf] border border-[#4a6bdf]/30 text-[11px] font-bold rounded-md transition hover:bg-[#dbe4ff] cursor-pointer shadow-sm flex items-center gap-1.5"
                                title="Klik untuk memfilter data Hadir">
                            <span class="w-2 h-2 rounded-full bg-[#4a6bdf]"></span> Hadir
                        </button>
                        <button id="btnFilterIzin" 
                                onclick="toggleAttendanceFilter('izin')" 
                                class="px-2.5 sm:px-3 py-1 bg-amber-50 text-amber-600 border border-amber-300 text-[11px] font-bold rounded-md transition hover:bg-amber-100 cursor-pointer shadow-sm flex items-center gap-1.5"
                                title="Klik untuk memfilter data Izin/Sakit">
                            <span class="w-2 h-2 rounded-full bg-[#f59e0b]"></span> Izin/Sakit
                        </button>
                    </div>
                </div>
                <div class="relative h-[200px] sm:h-[250px] w-full">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Status Karyawan -->
            <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-5 sm:p-7 flex flex-col h-full">
                <h3 class="font-extrabold text-gray-900 text-base sm:text-lg mb-6">Status Karyawan</h3>
                
                <div class="relative flex-1 flex justify-center items-center mb-6">
                    <div class="relative w-36 h-36 sm:w-40 sm:h-40">
                        <canvas id="statusChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-xl sm:text-2xl font-extrabold text-gray-900">{{ number_format($totalEmployees) }}</span>
                            <span class="text-[11px] font-medium text-gray-500">Total</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center text-[13px]">
                        <div class="flex items-center text-gray-600 font-bold">
                            <span class="w-3 h-3 rounded-full bg-[#0c2f82] mr-2"></span> Tetap
                        </div>
                        <span class="font-extrabold text-gray-900">{{ number_format($statusTetap) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px]">
                        <div class="flex items-center text-gray-600 font-bold">
                            <span class="w-3 h-3 rounded-full bg-[#4a6bdf] mr-2"></span> Kontrak
                        </div>
                        <span class="font-extrabold text-gray-900">{{ number_format($statusKontrak) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px]">
                        <div class="flex items-center text-gray-600 font-bold">
                            <span class="w-3 h-3 rounded-full bg-[#eef2fc] mr-2"></span> Magang
                        </div>
                        <span class="font-extrabold text-gray-900">{{ number_format($statusMagang) }}</span>
                    </div>
                </div>

                <a href="{{ route('superadmin.employees.index') }}" class="w-full mt-auto text-center py-2.5 border border-gray-300 text-[#0c2f82] text-[13px] font-bold rounded-lg hover:bg-gray-50 transition-colors">
                    Lihat Detail Karyawan
                </a>
            </div>
        </div>

        <!-- Permintaan Terbaru Row -->
        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-4 sm:p-6 sm:px-7 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-base sm:text-lg">Permintaan Terbaru</h3>
                    <p class="text-xs sm:text-[13px] text-gray-500 mt-0.5 sm:mt-1 font-medium">Persetujuan cuti dan lembur yang butuh tindakan</p>
                </div>
                <a href="#" class="text-xs sm:text-[13px] font-bold text-[#4a6bdf] hover:text-blue-900 shrink-0">
                    Lihat Semua
                </a>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar touch-scroll">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="text-gray-500 text-[12px] font-bold border-b border-gray-100 uppercase tracking-wider">
                            <th class="py-4 px-7">KARYAWAN</th>
                            <th class="py-4 px-4">JENIS</th>
                            <th class="py-4 px-4">TANGGAL</th>
                            <th class="py-4 px-4">ALASAN</th>
                            <th class="py-4 px-4">STATUS</th>
                            <th class="py-4 px-7">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentRequests as $request)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-7">
                                    <div class="flex items-center">
                                        @if($request->user->profile_photo_path)
                                            <img src="{{ Storage::url($request->user->profile_photo_path) }}" alt="{{ $request->user->name }}" class="w-9 h-9 rounded-full object-cover mr-3 border border-gray-200">
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-blue-100 text-[#4a6bdf] font-bold flex items-center justify-center mr-3 text-[14px]">
                                                {{ substr($request->user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 text-[13.5px]">{{ $request->user->name }}</div>
                                            <div class="text-[11px] text-gray-500">{{ $request->user->employee->position->name ?? 'Karyawan' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[13px] text-gray-700 font-medium">
                                    Cuti Tahunan
                                </td>
                                <td class="py-4 px-4 text-[13px] text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($request->start_date)->translatedFormat('d M') }} - {{ \Carbon\Carbon::parse($request->end_date)->translatedFormat('d M') }}
                                </td>
                                <td class="py-4 px-4 text-[13px] text-gray-700 font-medium truncate max-w-[150px]">
                                    {{ $request->reason }}
                                </td>
                                <td class="py-4 px-4">
                                    @if($request->status == 'pending')
                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-[11px] font-bold">Menunggu</span>
                                    @elseif($request->status == 'approved')
                                        <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-[11px] font-bold">Disetujui</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-[11px] font-bold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-4 px-7">
                                    @if($request->status == 'pending')
                                    <div class="flex space-x-2">
                                        <form action="{{ route('superadmin.leave.update_status', $request) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="p-1.5 text-green-600 hover:bg-green-50 rounded-md transition-colors" title="Setujui">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.leave.update_status', $request) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Tolak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Diproses oleh sistem</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[13.5px] text-gray-500 font-medium">
                                    Belum ada permintaan terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pengumuman Row -->
        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 px-7 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg">Pengumuman Terbaru</h3>
                    <p class="text-[13px] text-gray-500 mt-1 font-medium">Informasi terkini dari Admin dan SuperAdmin</p>
                </div>
                <a href="{{ route('superadmin.announcements.index') }}" class="text-[13px] font-bold text-[#4a6bdf] hover:text-blue-900">
                    Lihat Semua
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-[12px] font-bold border-b border-gray-100 uppercase tracking-wider">
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
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data Mingguan (Senin - Sabtu)
            const weeklyDays = {!! json_encode($weeklyDays) !!};
            const weeklyHadir = {!! json_encode($weeklyHadir) !!};
            const weeklyIzin = {!! json_encode($weeklyIzin) !!};

            let attendanceChartInstance = null;

            // Attendance Chart
            const ctxAtt = document.getElementById('attendanceChart').getContext('2d');
            attendanceChartInstance = new Chart(ctxAtt, {
                type: 'bar',
                data: {
                    labels: weeklyDays,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: weeklyHadir,
                            backgroundColor: '#4a6bdf',
                            borderRadius: 4,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Izin/Sakit',
                            data: weeklyIzin,
                            backgroundColor: '#f59e0b',
                            borderRadius: 4,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });

            window.toggleAttendanceFilter = function(type) {
                if (!attendanceChartInstance) return;

                const btnHadir = document.getElementById('btnFilterHadir');
                const btnIzin = document.getElementById('btnFilterIzin');

                if (type === 'hadir') {
                    const currentHidden = attendanceChartInstance.getDatasetMeta(0).hidden;
                    attendanceChartInstance.setDatasetVisibility(0, currentHidden);
                } else if (type === 'izin') {
                    const currentHidden = attendanceChartInstance.getDatasetMeta(1).hidden;
                    attendanceChartInstance.setDatasetVisibility(1, currentHidden);
                }

                attendanceChartInstance.update();

                const isHadirVisible = !attendanceChartInstance.getDatasetMeta(0).hidden;
                const isIzinVisible = !attendanceChartInstance.getDatasetMeta(1).hidden;

                if (btnHadir) {
                    if (isHadirVisible) {
                        btnHadir.className = "px-3 py-1 bg-[#eef2fc] text-[#4a6bdf] border border-[#4a6bdf]/30 text-[11px] font-bold rounded-md transition hover:bg-[#dbe4ff] cursor-pointer shadow-sm flex items-center gap-1.5";
                    } else {
                        btnHadir.className = "px-3 py-1 bg-gray-100 text-gray-400 border border-transparent text-[11px] font-medium rounded-md transition hover:bg-gray-200 cursor-pointer opacity-50 flex items-center gap-1.5";
                    }
                }

                if (btnIzin) {
                    if (isIzinVisible) {
                        btnIzin.className = "px-3 py-1 bg-amber-50 text-amber-600 border border-amber-300 text-[11px] font-bold rounded-md transition hover:bg-amber-100 cursor-pointer shadow-sm flex items-center gap-1.5";
                    } else {
                        btnIzin.className = "px-3 py-1 bg-gray-100 text-gray-400 border border-transparent text-[11px] font-medium rounded-md transition hover:bg-gray-200 cursor-pointer opacity-50 flex items-center gap-1.5";
                    }
                }
            };

            // Status Chart
            const statusTetap = {{ $statusTetap }};
            const statusKontrak = {{ $statusKontrak }};
            const statusMagang = {{ $statusMagang }};
            
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Tetap', 'Kontrak', 'Magang'],
                    datasets: [{
                        data: [statusTetap, statusKontrak, statusMagang],
                        backgroundColor: ['#0c2f82', '#4a6bdf', '#eef2fc'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
