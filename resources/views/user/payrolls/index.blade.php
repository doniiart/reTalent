<x-app-layout>
    <x-slot name="header">
        Manajemen Penggajian
    </x-slot>

    <div x-data="{ activeModal: null, showExportModal: false }" class="relative">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight mb-1">Manajemen Penggajian</h1>
                    <p class="text-[14px] text-gray-500 font-medium">Periode Pembayaran: {{ $currentMonth }} {{ now()->year }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click="showExportModal = true" class="px-5 py-2.5 bg-white border border-[#18398a] text-[#18398a] text-[14px] font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Ekspor Laporan
                    </button>
                    <form action="{{ route('user.payroll.process') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses semua gaji draft bulan ini menjadi paid?');">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Proses Gaji
                        </button>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Payroll -->
                <div class="bg-white p-5 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Total Gaji</p>
                        <h3 class="text-[18px] sm:text-[19px] xl:text-[21px] 2xl:text-[23px] font-extrabold text-gray-900 leading-none whitespace-nowrap truncate tracking-tight" title="Rp {{ number_format($totalPayroll, 0, ',', '.') }}">
                            <span class="text-[14px] sm:text-[15px] font-extrabold text-gray-700 mr-0.5">Rp</span>{{ number_format($totalPayroll, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Total Karyawan -->
                <div class="bg-white p-5 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Total Karyawan</p>
                        <h3 class="text-[20px] xl:text-[23px] font-extrabold text-gray-900 leading-none whitespace-nowrap truncate tracking-tight">{{ $totalKaryawan }} <span class="text-base text-gray-400 font-medium">Orang</span></h3>
                    </div>
                </div>

                <!-- Belum Dibayar -->
                <div class="bg-white p-5 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        @if($belumDibayar > 0)
                            <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-extrabold rounded-md uppercase">Urgent</span>
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Belum Dibayar</p>
                        <h3 class="text-[20px] xl:text-[23px] font-extrabold text-gray-900 leading-none whitespace-nowrap truncate tracking-tight">{{ $belumDibayar }} <span class="text-base text-gray-400 font-medium">Karyawan</span></h3>
                    </div>
                </div>

                <!-- Status Pembayaran -->
                <div class="bg-white p-5 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[13px] font-bold text-gray-500 mb-1">Status Pembayaran</p>
                        <h3 class="text-[20px] xl:text-[23px] font-extrabold text-gray-900 leading-none whitespace-nowrap truncate tracking-tight">{{ $statusPembayaran }}</h3>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Data Table Section -->
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-3">
                    <h2 class="text-sm sm:text-[15px] font-bold text-gray-900">Daftar Rincian Gaji</h2>
                    <form id="filterForm" method="GET" action="{{ route('user.payroll.index') }}" class="flex items-center gap-2">
                        <select name="department_id" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="filter-btn text-gray-500 hover:bg-gray-50 border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-900 text-[13px] font-bold border-b border-gray-100">
                                <th class="py-4 px-6 whitespace-nowrap">Nama Karyawan</th>
                                <th class="py-4 px-6 whitespace-nowrap">Gaji Pokok</th>
                                <th class="py-4 px-6 whitespace-nowrap">Tunjangan</th>
                                <th class="py-4 px-6 whitespace-nowrap">Potongan</th>
                                <th class="py-4 px-6 whitespace-nowrap">Total Gaji</th>
                                <th class="py-4 px-6 whitespace-nowrap">Status</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($payrolls as $payroll)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center space-x-3">
                                            @if($payroll->user->employee->photo ?? null)
                                                <img src="{{ Storage::url($payroll->user->employee->photo) }}" class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                                    {{ strtoupper(substr($payroll->user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-[13px] font-bold text-gray-900">{{ $payroll->user->name }}</div>
                                                <div class="text-[11px] text-gray-500 font-medium">{{ $payroll->user->employee->position->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">Rp {{ number_format($payroll->premium + $payroll->total_allowance + $payroll->total_reward, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">Rp {{ number_format($payroll->jht_deduction, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-[13px] font-bold text-[#18398a]">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6">
                                        @if($payroll->status == 'paid')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5"></span> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @role('superadmin')
                                            @if($payroll->status == 'draft' && $payroll->basic_salary == 0)
                                                <button type="button" @click.prevent="activeModal = 'edit-{{ $payroll->id }}'" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 text-[11px] font-bold rounded-lg transition-colors inline-flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    Atur Gaji
                                                </button>
                                            @else
                                                <button type="button" @click.prevent="activeModal = 'edit-{{ $payroll->id }}'" class="text-gray-400 hover:text-blue-600 transition-colors p-1.5 rounded-md hover:bg-blue-50" title="Edit Gaji">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                            @endif
                                        @else
                                            <span class="text-[11px] text-gray-400 italic">Khusus Superadmin</span>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center">
                                        <p class="text-gray-500 text-[13px] font-medium">Belum ada data rincian gaji untuk bulan ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($payrolls->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $payrolls->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Render Modals for Each Payroll -->
        @foreach($payrolls as $payroll)
        <div x-show="activeModal === 'edit-{{ $payroll->id }}'" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="activeModal = null" class="bg-white rounded-[16px] shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-[24px] font-extrabold text-[#111827] tracking-tight">Edit Rincian Gaji</h2>
                        <p class="text-[14px] text-gray-500 mt-1 font-medium">{{ $payroll->user->name }} - {{ $payroll->user->employee->position->name ?? '-' }}</p>
                    </div>
                    <button @click="activeModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-[#f4f7fb]/30">
                    <form id="editPayrollForm{{ $payroll->id }}" action="{{ route('user.payroll.update', $payroll) }}" method="POST" class="space-y-6"
                          x-data="{ 
                              basic: {{ old('basic_salary', $payroll->basic_salary) }},
                              premium: {{ old('premium', $payroll->premium) }},
                              allowance: {{ old('total_allowance', $payroll->total_allowance) }},
                              reward: {{ old('total_reward', $payroll->total_reward) }},
                              get jht() { return (Number(this.basic) + Number(this.premium) + Number(this.allowance) + Number(this.reward)) * 0.02; }
                          }">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Struktur Upah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Gaji Pokok</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                        <div class="px-4 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-500 font-medium text-[14px]">
                                            Rp
                                        </div>
                                        <input type="number" name="basic_salary" x-model="basic" class="w-full border-none px-4 py-2.5 text-[14px] focus:ring-0" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Premi</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                        <div class="px-4 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-500 font-medium text-[14px]">
                                            Rp
                                        </div>
                                        <input type="number" name="premium" x-model="premium" class="w-full border-none px-4 py-2.5 text-[14px] focus:ring-0" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Tunjangan Lainnya</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                        <div class="px-4 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-500 font-medium text-[14px]">
                                            Rp
                                        </div>
                                        <input type="number" name="total_allowance" x-model="allowance" class="w-full border-none px-4 py-2.5 text-[14px] focus:ring-0" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Reward (Disiplin/Solat)</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500">
                                        <div class="px-4 py-2.5 bg-gray-50 border-r border-gray-200 text-gray-500 font-medium text-[14px]">
                                            Rp
                                        </div>
                                        <input type="number" name="total_reward" x-model="reward" class="w-full border-none px-4 py-2.5 text-[14px] focus:ring-0" required>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5 flex items-center justify-between">
                                        Potongan JHT (2%)
                                        <span class="text-[11px] text-gray-400 font-normal border border-gray-200 px-1.5 py-0.5 rounded">Otomatis</span>
                                    </label>
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden md:w-1/2 bg-gray-50">
                                        <div class="px-4 py-2.5 border-r border-gray-200 text-gray-500 font-medium text-[14px]">
                                            Rp
                                        </div>
                                        <input type="number" :value="jht" class="w-full border-none px-4 py-2.5 text-[14px] focus:ring-0 bg-transparent text-gray-500 cursor-not-allowed pointer-events-none" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-8 py-5 border-t border-gray-100 bg-white flex justify-end items-center space-x-3">
                    <button @click="activeModal = null" type="button" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-[14px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="editPayrollForm{{ $payroll->id }}" class="px-6 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Export Modal -->
        <div x-show="showExportModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showExportModal = false" class="bg-white rounded-[16px] shadow-2xl w-full max-w-md flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-[20px] font-extrabold text-[#111827] tracking-tight">Filter Ekspor Laporan</h2>
                    </div>
                    <button @click="showExportModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 bg-[#f4f7fb]/30">
                    <form id="exportForm" action="{{ route('user.payroll.export') }}" method="GET" class="space-y-4">
                        
                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Departemen</label>
                            <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Departemen</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Jabatan</label>
                            <select name="position_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Jabatan</option>
                                @foreach($positions as $pos)
                                    <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Bulan</label>
                                <select name="month" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $m)
                                        <option value="{{ $m }}" {{ now()->format('F') == $m ? 'selected' : '' }}>{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Tahun</label>
                                <select name="year" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-600 focus:ring-blue-500 focus:border-blue-500" required>
                                    @for($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2 mt-4">Format Ekspor</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="format" value="pdf" class="w-4 h-4 text-[#18398a] border-gray-300 focus:ring-[#18398a]" checked>
                                    <span class="ml-2 text-[14px] text-gray-700 font-medium">PDF Document (.pdf)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="format" value="excel" class="w-4 h-4 text-[#18398a] border-gray-300 focus:ring-[#18398a]">
                                    <span class="ml-2 text-[14px] text-gray-700 font-medium">Excel (.xlsx)</span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end items-center space-x-3">
                    <button @click="showExportModal = false" type="button" class="px-5 py-2 bg-white border border-gray-300 text-gray-700 text-[13px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="exportForm" @click="showExportModal = false" class="px-5 py-2 bg-[#18398a] border border-[#18398a] text-white text-[13px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Laporan
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
