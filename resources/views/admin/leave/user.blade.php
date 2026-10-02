<x-app-layout>
<div x-data="leaveApp()" class="max-w-7xl mx-auto pb-12">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Cuti Saya</h1>
            <p class="text-[14px] text-gray-500 font-medium mt-1">Kelola pengajuan dan pantau sisa kuota cuti Anda.</p>
        </div>
        
        <a href="{{ route('admin.leave.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-[#18398a] hover:bg-[#122b6b] text-white text-[14px] font-bold rounded-xl transition-all shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Ajukan Cuti Baru
        </a>
    </div>

    <!-- Error/Success Alerts -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center font-bold text-[13px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 flex items-center font-bold text-[13px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold text-[13px]">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quota Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Cuti Tahunan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex justify-between items-center relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-[12px] font-bold text-gray-500 mb-1">Cuti Tahunan</p>
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-[32px] font-extrabold text-[#18398a] leading-none">{{ $remainingQuota }}</span>
                    <span class="text-[14px] font-bold text-gray-500">/ {{ $quota }} Hari</span>
                </div>
                @if($remainingQuota == 0)
                    <div class="flex items-center text-[12px] font-bold text-red-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Kuota Habis
                    </div>
                @else
                    <div class="flex items-center text-[12px] font-bold text-green-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Kuota Tersedia
                    </div>
                @endif
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-[#18398a] flex items-center justify-center relative z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div class="absolute -right-6 -bottom-6 opacity-[0.03] pointer-events-none w-32 h-32">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 002 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"></path></svg>
            </div>
        </div>

        <!-- Cuti Sakit -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex justify-between items-center">
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-1">Cuti Sakit</p>
                <div class="text-[28px] font-extrabold text-[#2563eb] leading-tight mb-2">Unlimited</div>
                <div class="text-[12px] font-medium text-gray-500">Sesuai Surat Dokter</div>
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-[#2563eb] flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
        </div>

        <!-- Cuti Khusus -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex justify-between items-center">
            <div>
                <p class="text-[12px] font-bold text-gray-500 mb-1">Cuti Khusus</p>
                <div class="text-[28px] font-extrabold text-gray-900 leading-tight mb-2">Tersedia</div>
                <div class="text-[12px] font-medium text-gray-500">Nikah, Berduka, dll.</div>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gray-50 text-gray-500 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Col -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Riwayat Pengajuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-extrabold text-[16px] text-gray-900">Riwayat Pengajuan</h3>
                    <a href="#" class="text-[13px] font-bold text-[#18398a] hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#f4f7fb]/60 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Jenis Cuti</th>
                                <th class="px-6 py-4">Durasi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-[13px]">
                            @forelse($userLeaves as $leave)
                            @php
                                $start = \Carbon\Carbon::parse($leave->start_date);
                                $end = \Carbon\Carbon::parse($leave->end_date);
                                $days = $start->diffInDays($end) + 1;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($leave->created_at)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">Cuti {{ ucfirst($leave->leave_type) }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5 truncate max-w-[150px]">{{ $leave->reason }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-700">
                                    {{ $days }} Hari
                                </td>
                                <td class="px-6 py-4">
                                    @if($leave->status == 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($leave->status == 'rejected')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="viewLeave({{ $leave->toJson() }})" class="text-[#18398a] hover:text-[#122b6b]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada riwayat pengajuan cuti.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kebijakan -->
            <div class="bg-[#f8fafc] rounded-xl p-6 border border-gray-200 relative overflow-hidden">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[#18398a] mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-extrabold text-[15px] text-gray-900 mb-3">Kebijakan Cuti Perusahaan</h4>
                        <ul class="text-[13px] text-gray-600 space-y-2 list-disc pl-4 font-medium leading-relaxed">
                            <li>Pengajuan cuti tahunan dilakukan minimal 7 hari sebelum tanggal pelaksanaan.</li>
                            <li>Cuti sakit di atas 1 hari <b>wajib</b> melampirkan surat keterangan dokter.</li>
                            <li>Sisa kuota cuti tahunan akan hangus pada akhir periode tahun kalender (31 Desember).</li>
                            <li>Persetujuan cuti dilakukan secara berjenjang oleh Atasan Langsung dan HRD.</li>
                        </ul>
                    </div>
                </div>
                <svg class="absolute -right-8 -bottom-8 w-40 h-40 text-gray-200/50 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            </div>
            
        </div>

        <!-- Right Col -->
        <div class="lg:col-span-4 space-y-8">
            
            <!-- Kalender Cuti -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-extrabold text-gray-900 text-[16px]">Kalender Cuti</h3>
                    <div class="flex space-x-1">
                        <button class="text-gray-400 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button class="text-gray-400 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="text-center font-extrabold text-[#18398a] text-[15px] mb-4">
                    {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <div class="text-[10px] font-bold text-gray-400">S</div>
                    <div class="text-[10px] font-bold text-gray-400">S</div>
                    <div class="text-[10px] font-bold text-gray-400">R</div>
                    <div class="text-[10px] font-bold text-gray-400">K</div>
                    <div class="text-[10px] font-bold text-gray-400">J</div>
                    <div class="text-[10px] font-bold text-gray-400">S</div>
                    <div class="text-[10px] font-bold text-gray-400">M</div>
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center text-[13px] font-semibold text-gray-700 mb-6">
                    <!-- Dummy minimal calendar grid for design -->
                    @for($i = 1; $i <= 30; $i++)
                        @php
                            $isLeave = false;
                            $isPending = false;
                            $today = \Carbon\Carbon::now()->day;
                            
                            foreach($userLeaves as $l) {
                                $lStart = \Carbon\Carbon::parse($l->start_date)->day;
                                $lEnd = \Carbon\Carbon::parse($l->end_date)->day;
                                if ($i >= $lStart && $i <= $lEnd && \Carbon\Carbon::parse($l->start_date)->month == \Carbon\Carbon::now()->month) {
                                    if($l->status == 'approved') $isLeave = true;
                                    if($l->status == 'pending') $isPending = true;
                                }
                            }
                        @endphp
                        
                        @if($isLeave)
                            <div class="bg-[#18398a] text-white py-1.5 rounded-sm relative shadow-sm font-bold">{{ $i }}</div>
                        @elseif($isPending)
                            <div class="bg-amber-100 text-amber-700 py-1.5 rounded-sm relative font-bold">{{ $i }}</div>
                        @else
                            <div class="py-1.5 hover:bg-gray-50 cursor-pointer rounded-sm {{ $i == $today ? 'text-[#18398a] font-extrabold border border-[#18398a]' : '' }}">{{ $i }}</div>
                        @endif
                    @endfor
                </div>

                <div class="space-y-2 mt-4 text-[11px] font-bold text-gray-600">
                    <div class="flex items-center"><div class="w-2.5 h-2.5 rounded-full bg-[#18398a] mr-2"></div> Sudah Diambil (Disetujui)</div>
                    <div class="flex items-center"><div class="w-2.5 h-2.5 rounded-full bg-amber-400 mr-2"></div> Menunggu Persetujuan</div>
                </div>
            </div>

            <!-- Cuti Mendatang -->
            <div class="bg-[#18398a] rounded-xl p-6 text-white shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <svg class="w-6 h-6 text-blue-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="font-extrabold text-[16px] mb-2">Cuti Mendatang</h3>
                    <p class="text-[12px] text-blue-100 font-medium mb-6">Persiapkan pekerjaan Anda sebelum berlibur.</p>
                    
                    @forelse($upcomingLeaves as $uLeave)
                    @php
                        $start = \Carbon\Carbon::parse($uLeave->start_date);
                        $end = \Carbon\Carbon::parse($uLeave->end_date);
                        $days = $start->diffInDays($end) + 1;
                    @endphp
                    <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm mb-2 border border-white/10">
                        <div class="text-[12px] font-extrabold">{{ $start->translatedFormat('d') }} - {{ $end->translatedFormat('d F') }}</div>
                        <div class="text-[11px] text-blue-100 mt-1">Cuti {{ ucfirst($uLeave->leave_type) }} ({{ $days }} Hari)</div>
                    </div>
                    @empty
                    <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm text-[12px] text-center font-medium text-blue-100">
                        Tidak ada cuti terjadwal.
                    </div>
                    @endforelse
                </div>
                
                <div class="absolute -right-6 -bottom-6 opacity-10 pointer-events-none w-40 h-40">
                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M22.09 10.53L20 9l-10 5-6-3-2 1 7 4 13.09-5.47z"></path><path d="M20 13l-10 5-6-3-2 1 7 4 13-5.5z"></path></svg>
                </div>
            </div>
            
        </div>
    </div>

</div>

<script>
    function leaveApp() {
        return {
            // Functionalities that are still required on dashboard
        }
    }
</script>
</x-app-layout>
