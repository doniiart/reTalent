<x-app-layout>
<div x-data="leaveFormApp()" class="max-w-7xl mx-auto pb-12">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Ajukan Cuti</h1>
        </div>
    </div>

    <!-- Error Alerts -->
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Col: Form -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-extrabold text-[18px] text-gray-900 mb-1">Formulir Pengajuan Cuti</h3>
                    <p class="text-[13px] text-gray-500 font-medium">Lengkapi data di bawah ini untuk mengajukan permohonan cuti Anda.</p>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('superadmin.leave.store') }}" method="POST" enctype="multipart/form-data" id="leaveForm">
                        @csrf
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Jenis Cuti -->
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Jenis Cuti</label>
                                    <select name="leave_type" x-model="leaveType" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-blue-500 font-medium text-gray-700 shadow-sm" required>
                                        <option value="tahunan">Cuti Tahunan</option>
                                        <option value="sakit">Cuti Sakit</option>
                                        <option value="khusus">Cuti Khusus</option>
                                    </select>
                                </div>
                                
                                <!-- Durasi -->
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Durasi (Hari)</label>
                                    <div class="relative">
                                        <input type="text" x-model="totalDays" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 font-bold shadow-sm" readonly>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-[13px] font-medium text-gray-500">Hari Kerja</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tanggal Mulai -->
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Tanggal Mulai</label>
                                    <input type="date" name="start_date" x-model="startDate" @change="calcDays()" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-blue-500 font-medium text-gray-700 shadow-sm" required>
                                </div>
                                
                                <!-- Tanggal Berakhir -->
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-2">Tanggal Berakhir</label>
                                    <input type="date" name="end_date" x-model="endDate" @change="calcDays()" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-blue-500 font-medium text-gray-700 shadow-sm" required>
                                </div>
                            </div>
                            
                            <!-- Alasan -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Alasan Cuti</label>
                                <textarea name="reason" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-[14px] focus:ring-blue-500 font-medium text-gray-700 shadow-sm" required placeholder="Jelaskan alasan pengambilan cuti secara singkat dan jelas..."></textarea>
                            </div>
                            
                            <!-- Dokumen Pendukung -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Dokumen Pendukung (Opsional)</label>
                                
                                <!-- Alert optional -->
                                <div x-show="leaveType === 'sakit' && totalDays > 2" class="mb-3 bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-lg text-[12px] font-bold flex items-start" style="display: none;">
                                    <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <div>Cuti sakit lebih dari 2 hari sangat disarankan untuk melampirkan Surat Dokter (Opsional di form, tapi sesuai kebijakan).</div>
                                </div>
                                
                                <div class="w-full relative border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer group">
                                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="updateFileName">
                                    <div class="text-gray-400 group-hover:text-[#18398a] transition-colors mb-2">
                                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="text-[14px] font-bold text-gray-700 mb-1" x-text="fileName || 'Klik untuk unggah atau seret file ke sini'"></p>
                                    <p class="text-[12px] font-medium text-gray-500">PDF, JPG, atau PNG (Maks. 5MB)</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-4">
                            <a href="{{ route('superadmin.leave.index') }}" class="inline-flex justify-center rounded-lg border border-gray-300 px-6 py-2.5 bg-white text-[14px] font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-lg px-6 py-2.5 bg-[#18398a] hover:bg-[#122b6b] text-[14px] font-bold text-white transition-colors shadow-sm">
                                Ajukan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Footer Cards -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-gray-200 pt-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-700 mb-0.5">Butuh Bantuan?</h4>
                        <p class="text-[12px] font-medium text-gray-500">Hubungi tim HR Operations</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-700 mb-0.5">Data Aman</h4>
                        <p class="text-[12px] font-medium text-gray-500">Enkripsi standar industri</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                    <div>
                        <h4 class="text-[13px] font-bold text-gray-700 mb-0.5">Arsip Digital</h4>
                        <p class="text-[12px] font-medium text-gray-500">Tersimpan otomatis di database</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Col: Sidebar Info -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Sisa Kuota -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-extrabold text-[15px] text-gray-900 mb-4">Sisa Kuota Cuti</h3>
                
                <div class="bg-[#f4f7fb] rounded-lg p-4 border border-blue-50 flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-[#18398a] text-white flex items-center justify-center mr-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-500 mb-0.5">Cuti Tahunan</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-[18px] font-extrabold text-gray-900">{{ $remainingQuota }}</span>
                                <span class="text-[12px] font-bold text-gray-500">Hari</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-[#18398a]">Berlaku s/d</p>
                        <p class="text-[12px] font-extrabold text-gray-900">31 Des {{ \Carbon\Carbon::now()->year }}</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-gray-200 flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#2563eb] flex items-center justify-center mr-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-500 mb-0.5">Cuti Sakit</p>
                        <div class="text-[16px] font-extrabold text-gray-900">Unlimited</div>
                    </div>
                </div>
            </div>
            
            <!-- Kebijakan -->
            <div class="bg-[#f4f7fb] rounded-xl p-6 border border-blue-50 relative overflow-hidden">
                <div class="flex items-center mb-4 relative z-10">
                    <svg class="w-5 h-5 text-[#18398a] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h4 class="font-extrabold text-[14px] text-gray-900">Kebijakan Cuti</h4>
                </div>
                
                <ul class="text-[12.5px] text-gray-600 space-y-3 list-disc pl-4 font-medium relative z-10">
                    <li>Pengajuan maksimal H-7 untuk cuti tahunan.</li>
                    <li>Lampirkan surat dokter jika cuti sakit > 2 hari.</li>
                    <li>Persetujuan dilakukan oleh Manager & HR.</li>
                </ul>
                
                <a href="#" class="inline-block mt-4 text-[12px] font-bold text-[#18398a] hover:underline relative z-10">Lihat Selengkapnya</a>
                
                <svg class="absolute -right-8 -bottom-8 w-32 h-32 text-blue-100 opacity-50 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
            </div>
            
            <!-- Riwayat Pengajuan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-extrabold text-[14px] text-gray-900">Riwayat Pengajuan</h3>
                    <a href="{{ route('superadmin.leave.index') }}" class="text-[12px] font-bold text-[#18398a] hover:underline">Semua</a>
                </div>
                
                <div class="space-y-4">
                    @forelse($userLeaves as $leave)
                        @php
                            $start = \Carbon\Carbon::parse($leave->start_date);
                            $end = \Carbon\Carbon::parse($leave->end_date);
                        @endphp
                        <div class="flex justify-between items-start border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <h4 class="text-[13px] font-bold text-gray-900">Cuti {{ ucfirst($leave->leave_type) }}</h4>
                                <div class="flex items-center text-[11px] font-medium text-gray-500 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $start->translatedFormat('d M') }} - {{ $end->translatedFormat('d M Y') }}
                                </div>
                            </div>
                            <div>
                                @if($leave->status == 'approved')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Disetujui</span>
                                @elseif($leave->status == 'rejected')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">Proses</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-[12px] text-gray-500 text-center py-2 font-medium">Belum ada riwayat</div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    function leaveFormApp() {
        return {
            leaveType: 'tahunan',
            startDate: '',
            endDate: '',
            totalDays: 0,
            fileName: '',
            
            calcDays() {
                if (this.startDate && this.endDate) {
                    const start = new Date(this.startDate);
                    const end = new Date(this.endDate);
                    if (end >= start) {
                        const diffTime = Math.abs(end - start);
                        this.totalDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    } else {
                        this.totalDays = 0;
                    }
                } else {
                    this.totalDays = 0;
                }
            },
            
            updateFileName(e) {
                if(e.target.files.length > 0) {
                    this.fileName = e.target.files[0].name;
                } else {
                    this.fileName = '';
                }
            }
        }
    }
</script>
</x-app-layout>
