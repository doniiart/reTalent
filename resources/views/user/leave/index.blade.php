<x-app-layout>
    <div x-data="{ 
        showDetailModal: false, 
        selectedEmployee: null,
        leaves: [],
        showQuotaModal: false,
        showExportModal: false,
        selectedDepartment: null,
        quotaForm: {
            id: '',
            name: '',
            quota: ''
        },
        openDetailModal(employeeName, leaveData) {
            this.selectedEmployee = employeeName;
            this.leaves = leaveData;
            this.showDetailModal = true;
        },
        openQuotaModal(id, name, quota) {
            this.quotaForm.id = id;
            this.quotaForm.name = name;
            this.quotaForm.quota = quota;
            this.showQuotaModal = true;
        }
    }" class="max-w-[1200px] mx-auto w-full pb-8 relative">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Manajemen Cuti & Jatah Tahunan</h2>
                <p class="text-[14px] text-gray-500 mt-1.5 font-medium">Monitor pengajuan cuti karyawan dan atur kebijakan jatah tahunan secara efisien di seluruh departemen.</p>
            </div>
            <div class="flex items-center">
                <button @click="showExportModal = true" class="px-5 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Laporan Cuti
                </button>
            </div>
        </div>

        <!-- 4 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Pengajuan -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Total Pengajuan</p>
                        <h3 class="text-[32px] font-extrabold text-gray-900 leading-tight mb-2">{{ $totalPengajuan }}</h3>
                        <div class="text-[#18398a] text-[11px] font-bold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +12% bulan ini
                        </div>
                    </div>
                    <div class="p-3 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Cuti Disetujui -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Cuti Disetujui</p>
                        <h3 class="text-[32px] font-extrabold text-gray-900 leading-tight mb-2">{{ $cutiDisetujui }}</h3>
                        <div class="text-[#15a34a] text-[11px] font-bold">
                            {{ $disetujuiPercentage }}% dari total
                        </div>
                    </div>
                    <div class="p-3 bg-[#ebf8f0] text-[#15a34a] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Cuti Menunggu -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Cuti Menunggu</p>
                        <h3 class="text-[32px] font-extrabold text-gray-900 leading-tight mb-2">{{ $cutiMenunggu }}</h3>
                        <div class="text-[#dc2626] text-[11px] font-bold leading-tight">
                            Butuh tindakan<br/>segera
                        </div>
                    </div>
                    <div class="p-3 bg-[#fef2f2] text-[#dc2626] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Total Karyawan -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Total Karyawan</p>
                        <h3 class="text-[32px] font-extrabold text-gray-900 leading-tight mb-2">{{ $totalKaryawan }}</h3>
                        <div class="text-gray-500 text-[11px] font-bold">
                            Aktif periode 2024
                        </div>
                    </div>
                    <div class="p-3 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Daftar Jatah Cuti Karyawan -->
        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 px-7 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center bg-white gap-4">
                <h3 class="font-extrabold text-gray-900 text-[18px]">Daftar Jatah Cuti Karyawan</h3>
                <form action="{{ route('user.leave.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto" id="filterForm">
                    <div class="relative w-full sm:w-[200px]">
                        <select name="department_id" onchange="document.getElementById('filterForm').submit()" class="filter-select w-full pr-8">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div class="relative w-full sm:w-[220px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 11-14 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" onchange="document.getElementById('filterForm').submit()" class="filter-input w-full pl-9" placeholder="Cari nama...">
                    </div>
                </form>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar touch-scroll">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-[#f8fafc] text-gray-600 text-[12px] font-bold border-b border-gray-200 tracking-wider">
                            <th class="py-4 px-7 uppercase">Nama Karyawan</th>
                            <th class="py-4 px-4 uppercase">Departemen</th>
                            <th class="py-4 px-4 uppercase text-center">Jatah Tahunan</th>
                            <th class="py-4 px-4 uppercase text-center">Sisa Cuti</th>
                            <th class="py-4 px-4 uppercase">Status</th>
                            <th class="py-4 px-7 uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($employees as $emp)
                            @php
                                $annualQuota = $emp->employee->department->annual_leave_quota ?? 12;
                                $approvedLeaves = $emp->leaves->where('status', 'approved');
                                $usedLeaves = 0;
                                foreach($approvedLeaves as $l) {
                                    $usedLeaves += \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1;
                                }
                                $remaining = $annualQuota - $usedLeaves;
                                
                                // Format leave data for JS
                                $leaveData = $emp->leaves->map(function($l) {
                                    return [
                                        'id' => $l->id,
                                        'start' => \Carbon\Carbon::parse($l->start_date)->format('d M Y'),
                                        'end' => \Carbon\Carbon::parse($l->end_date)->format('d M Y'),
                                        'duration' => \Carbon\Carbon::parse($l->start_date)->diffInDays(\Carbon\Carbon::parse($l->end_date)) + 1,
                                        'reason' => $l->reason,
                                        'status' => $l->status,
                                        'type' => ucfirst($l->leave_type),
                                        'attachment' => $l->attachment ? Storage::url($l->attachment) : null
                                    ];
                                })->toJson();
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-7 font-bold text-gray-900 text-[14px]">{{ $emp->name }}</td>
                                <td class="py-4 px-4 text-gray-600 text-[13.5px]">
                                    {{ $emp->employee->department->name ?? '-' }}
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-gray-700 text-[14px]">{{ $annualQuota }} Hari</td>
                                <td class="py-4 px-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[32px] h-[32px] rounded-full font-extrabold text-[13px] {{ $remaining > 5 ? 'bg-[#ebf8f0] text-[#15a34a]' : ($remaining > 0 ? 'bg-[#fef9c3] text-[#a16207]' : 'bg-[#fef2f2] text-[#dc2626]') }}">
                                        {{ $remaining }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($emp->leaves->where('status', 'pending')->count() > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-[#fef9c3] text-[#a16207]">Menunggu Persetujuan</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-gray-100 text-gray-600">Tidak ada pengajuan</span>
                                    @endif
                                </td>
                                <td class="py-4 px-7 text-right">
                                    <button @click="openDetailModal('{{ addslashes($emp->name) }}', {{ $leaveData }})" class="px-4 py-2 bg-white border border-gray-200 text-[#18398a] text-[13px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                        Detail Riwayat
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[13.5px] text-gray-500 font-medium">
                                    Belum ada data jatah cuti karyawan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="p-6 px-7 border-t border-gray-200 bg-white">
                {{ $employees->links() }}
            </div>
        </div>
        
        @if(auth()->user()->hasRole('superadmin'))
        <!-- Table Manajemen Cuti Departemen -->
        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden mt-8">
            <div class="p-6 px-7 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center bg-white gap-4">
                <h3 class="font-extrabold text-gray-900 text-[18px]">Manajemen Cuti Departemen</h3>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar touch-scroll">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-[#f8fafc] text-gray-600 text-[12px] font-bold border-b border-gray-200 tracking-wider">
                            <th class="py-4 px-7 uppercase">Departemen</th>
                            <th class="py-4 px-4 uppercase text-center">Jatah Tahunan</th>
                            <th class="py-4 px-7 uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($departments as $dept)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4 px-7 font-bold text-gray-900 text-[14px]">{{ $dept->name }}</td>
                                <td class="py-4 px-4 text-center font-bold text-gray-700 text-[14px]">{{ $dept->annual_leave_quota }} Hari</td>
                                <td class="py-4 px-7 text-right">
                                    <button @click="openQuotaModal({{ $dept->id }}, '{{ addslashes($dept->name) }}', {{ $dept->annual_leave_quota }})" class="px-4 py-2 bg-white border border-gray-200 text-[#18398a] text-[13px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                                        Ubah Jatah
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Footer Info -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col md:flex-row justify-between items-center text-[12px] text-gray-500 font-medium">
            <p>&copy; 2024 ReTalent HRIS Enterprise. Seluruh hak cipta dilindungi.</p>
            <div class="flex space-x-4 mt-2 md:mt-0">
                <a href="#" class="text-[#18398a] hover:underline">Kebijakan Privasi</a>
                <a href="#" class="text-[#18398a] hover:underline">Syarat & Ketentuan</a>
            </div>
        </div>

        <!-- Modal Detail Riwayat Cuti -->
        <div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDetailModal" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDetailModal" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-start mb-5">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" x-text="'Riwayat Cuti - ' + selectedEmployee"></h3>
                            <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="mt-4">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis & Durasi</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Alasan & Lampiran</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="leave in leaves" :key="leave.id">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="leave.start + ' - ' + leave.end"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="font-bold" x-text="'Cuti ' + leave.type"></div>
                                                    <div class="text-[12px] text-gray-500" x-text="leave.duration + ' Hari'"></div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <div x-text="leave.reason"></div>
                                                    <a x-show="leave.attachment" :href="leave.attachment" target="_blank" class="text-blue-600 hover:underline text-[11px] font-bold mt-1 inline-block">Lihat Lampiran</a>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span x-show="leave.status === 'approved'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                                    <span x-show="leave.status === 'pending'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                                    <span x-show="leave.status === 'rejected'" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <!-- Regular users cannot approve/reject leaves -->
                                                </td>
                                            </tr>
                                        </template>
                                        <tr x-show="leaves.length === 0">
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat cuti.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showDetailModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->hasRole('superadmin'))
        <!-- Modal Ubah Jatah Departemen -->
        <div x-show="showQuotaModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showQuotaModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('user.departments.update_quota') }}" method="POST">
                        @csrf
                        <input type="hidden" name="department_id" x-model="quotaForm.id">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-5">
                                <h3 class="text-xl leading-6 font-bold text-gray-900" x-text="'Ubah Jatah Tahunan: ' + quotaForm.name"></h3>
                            </div>
                            <div class="mt-4">
                                <label for="quota" class="block text-sm font-medium text-gray-700">Jatah Cuti (Hari)</label>
                                <input type="number" name="annual_leave_quota" id="quota" x-model="quotaForm.quota" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                <p class="mt-2 text-xs text-gray-500">Perubahan ini akan berlaku untuk semua karyawan di departemen tersebut.</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#18398a] text-base font-medium text-white hover:bg-[#22449c] sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                            <button @click="showQuotaModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal Export Cuti -->
        <div x-show="showExportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showExportModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('user.leave.export') }}" method="GET">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-5">
                                <h3 class="text-xl leading-6 font-bold text-gray-900">Ekspor Laporan Cuti</h3>
                                <p class="text-sm text-gray-500 mt-1">Gunakan filter di bawah untuk menyesuaikan data laporan cuti yang akan diekspor.</p>
                            </div>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Departemen</label>
                                    <select name="department_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="">Semua Departemen</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Bulan</label>
                                        <select name="month" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Semua Bulan</option>
                                            @for($i=1; $i<=12; $i++)
                                                <option value="{{ $i }}">{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tahun</label>
                                        <select name="year" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Semua Tahun</option>
                                            @for($i = date('Y')-2; $i <= date('Y'); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Karyawan (Opsional)</label>
                                    <input type="text" name="employee_name" placeholder="Masukkan nama karyawan..." class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Format</label>
                                    <select name="format" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                        <option value="pdf">PDF (.pdf)</option>
                                        <option value="excel">Excel (.xlsx)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#18398a] text-base font-medium text-white hover:bg-[#22449c] sm:ml-3 sm:w-auto sm:text-sm">Unduh Laporan</button>
                            <button @click="showExportModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
