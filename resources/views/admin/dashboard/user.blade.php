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
            <a href="{{ route('admin.leave.index') }}" class="bg-[#18398a] hover:bg-[#122b6b] text-white font-bold px-5 py-2.5 rounded-lg flex items-center text-[14px] shadow-sm transition-colors">
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
                <a href="{{ route('admin.payroll.index') }}" class="text-[#18398a] hover:text-[#122b6b] text-[14px] font-bold inline-flex items-center group">
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
            <a href="{{ route('admin.leave.index') }}" class="text-[13px] font-bold text-[#18398a] hover:text-[#122b6b]">Lihat Semua Riwayat</a>
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
            <a href="{{ route('admin.announcements.index') }}" class="text-[13px] font-bold text-[#18398a] hover:text-[#122b6b]">
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
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-7">
                                <div class="font-bold text-gray-900 text-[13.5px]">{{ $announcement->title }}</div>
                                <div class="text-[12px] text-gray-500 truncate max-w-md">{{ Str::limit(strip_tags($announcement->content), 80) }}</div>
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
