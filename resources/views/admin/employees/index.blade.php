<x-app-layout>
    <x-slot name="header">
        Employees
    </x-slot>

    <div x-data="{ showModal: false, showImportModal: false, showExportModal: false, activeModal: null, photoPreview: null, exportTarget: 'all', selectedDept: '', selectedEmpId: '{{ $allEmployeesList->first()->id ?? '' }}', exportFormat: 'excel' }" class="relative">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Breadcrumb -->
            <div class="text-[13px] text-gray-500 font-medium mb-4 flex items-center space-x-2">
                <span>Dashboard</span>
                <span>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
                <span class="font-bold text-[#18398a]">Manajemen Karyawan</span>
            </div>

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-[28px] font-extrabold text-[#111827] tracking-tight mb-1">Manajemen Karyawan</h2>
                    <p class="text-[14px] text-gray-500 font-medium">Kelola data seluruh personil perusahaan dalam satu dashboard terpusat.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button @click.stop="showExportModal = true" type="button" class="px-5 py-2.5 bg-white border border-[#18398a] text-[#18398a] text-[14px] font-bold rounded-lg hover:bg-blue-50 transition-colors shadow-sm flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Ekspor Data
                    </button>
                    <button @click="showModal = true" class="px-5 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Tambah Karyawan
                    </button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Karyawan -->
                <div class="bg-white p-6 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-[#f4f7fb] text-[#18398a] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        @if($newEmployeesThisMonth > 0)
                            <span class="bg-green-50 text-green-600 text-[11px] font-extrabold px-2 py-1 rounded-md tracking-wide">+{{ $newEmployeesThisMonth }} Baru</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[12px] text-gray-500 font-bold mb-1">Total Karyawan</p>
                        <h3 class="text-[28px] font-extrabold text-[#111827] leading-none">{{ number_format($totalEmployees) }}</h3>
                    </div>
                </div>

                <!-- Karyawan Aktif -->
                <div class="bg-white p-6 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        @if($totalEmployees > 0)
                            <span class="bg-blue-50 text-blue-600 text-[11px] font-extrabold px-2 py-1 rounded-md tracking-wide">{{ round(($activeEmployees / $totalEmployees) * 100) }}%</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[12px] text-gray-500 font-bold mb-1">Karyawan Aktif</p>
                        <h3 class="text-[28px] font-extrabold text-[#111827] leading-none">{{ number_format($activeEmployees) }}</h3>
                    </div>
                </div>

                <!-- Masa Percobaan -->
                <div class="bg-white p-6 rounded-[16px] border border-gray-100 shadow-sm flex flex-col justify-between h-[140px]">
                    <div class="flex justify-between items-start">
                        <div class="w-10 h-10 bg-gray-50 text-gray-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-[12px] text-gray-500 font-bold mb-1">Masa Percobaan</p>
                        <h3 class="text-[28px] font-extrabold text-[#111827] leading-none">{{ number_format($probationEmployees) }}</h3>
                    </div>
                </div>

                <!-- Kehadiran Hari Ini -->
                <div class="bg-[#244cb3] p-6 rounded-[16px] shadow-sm flex flex-col justify-between h-[140px] text-white relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-5 rounded-full blur-2xl"></div>
                    <div>
                        <p class="text-[12px] text-blue-200 font-bold mb-1">Kehadiran Hari Ini</p>
                        <h3 class="text-[32px] font-extrabold leading-none">{{ $attendancePercentage }}%</h3>
                    </div>
                    @if($todayAttendance > 0)
                    <div class="flex items-center space-x-2 mt-4">
                        <div class="flex -space-x-2">
                            @foreach($recentAttendees as $attendee)
                            <div class="w-6 h-6 rounded-full bg-blue-400 border-2 border-[#244cb3] flex items-center justify-center text-[8px] font-bold" title="{{ $attendee->user->name }}">
                                {{ strtoupper(substr($attendee->user->name, 0, 1)) }}
                            </div>
                            @endforeach
                        </div>
                        @if($todayAttendance > 3)
                        <span class="text-[11px] text-blue-200 font-bold">+{{ $todayAttendance - 3 }} lainnya</span>
                        @endif
                    </div>
                    @else
                    <div class="mt-4">
                        <span class="text-[11px] text-blue-200 font-medium">Belum ada data kehadiran</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Data Table Section -->
            <div class="bg-white rounded-[16px] shadow-sm border border-gray-100 overflow-hidden">
                <!-- Filters -->
                <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center bg-white gap-3">
                    <form id="filterForm" method="GET" action="{{ route('admin.employees.index') }}" class="flex flex-wrap items-center gap-2">
                        <select name="department_id" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="tetap" {{ request('status') == 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="kontrak" {{ request('status') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="intership" {{ request('status') == 'intership' ? 'selected' : '' }}>Intership</option>
                        </select>
                        <select name="per_page" onchange="document.getElementById('filterForm').submit()" class="filter-select">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 data</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 data</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 data</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 data</option>
                        </select>
                    </form>
                    <div class="text-[13px] text-gray-500 font-medium">
                        Menampilkan <span class="font-bold text-gray-900">{{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }}</span> dari <span class="font-bold text-[#18398a]">{{ number_format($employees->total()) }}</span> Karyawan
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-900 text-[13px] font-bold border-b border-gray-100">
                                <th class="py-4 px-6 whitespace-nowrap">NIP</th>
                                <th class="py-4 px-6 whitespace-nowrap">Nama Lengkap</th>
                                <th class="py-4 px-6 whitespace-nowrap">Jabatan</th>
                                <th class="py-4 px-6 whitespace-nowrap">Posisi</th>
                                <th class="py-4 px-6 whitespace-nowrap">Departemen</th>
                                <th class="py-4 px-6 whitespace-nowrap">Status</th>
                                <th class="py-4 px-6 text-center whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($employees as $employee)
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="py-4 px-6 text-[13px] text-gray-700 font-bold font-mono">{{ $employee->user->nip ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        @if($employee->photo)
                                            <img src="{{ Storage::url($employee->photo) }}" class="w-8 h-8 rounded-full object-cover mr-3 shrink-0 border border-gray-200">
                                        @elseif($employee->user->profile_photo_path)
                                            <img src="{{ Storage::url($employee->user->profile_photo_path) }}" class="w-8 h-8 rounded-full object-cover mr-3 shrink-0 border border-gray-200">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-[11px] font-extrabold mr-3 shrink-0">
                                                {{ strtoupper(substr($employee->user->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-[13.5px] font-bold text-gray-900 leading-tight">{{ $employee->user->name }}</p>
                                            <p class="text-[12px] text-gray-500 font-medium">{{ $employee->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $employee->position->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $employee->jobType->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-[13px] text-gray-600 font-medium">{{ $employee->department->name ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    @if($employee->employment_status === 'tetap' || $employee->employment_status === 'kontrak')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-600">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> Aktif
                                        </span>
                                    @elseif($employee->employment_status === 'intership')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-orange-50 text-orange-600">
                                            <span class="w-1.5 h-1.5 bg-orange-500 rounded-full mr-1.5"></span> Magang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-gray-100 text-gray-600">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span> Non-aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- View -->
                                        <button type="button" @click.prevent="activeModal = 'view-{{ $employee->id }}'" class="text-gray-400 hover:text-blue-600 transition-colors p-1.5 rounded-md hover:bg-blue-50" title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <!-- Edit -->
                                        <button type="button" @click.prevent="activeModal = 'edit-{{ $employee->id }}'" class="text-gray-400 hover:text-orange-500 transition-colors p-1.5 rounded-md hover:bg-orange-50" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <!-- Delete -->
                                        @role('superadmin')
                                        <button type="button" @click.prevent="activeModal = 'delete-{{ $employee->id }}'" class="text-gray-400 hover:text-red-600 transition-colors p-1.5 rounded-md hover:bg-red-50" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 px-6 text-center text-gray-500 text-[13px] font-medium">Belum ada data...</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-white">
                    {{ $employees->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Modal Backdrop -->
        <div x-show="showModal" 
             style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Modal Content -->
            <div @click.away="showModal = false" 
                 class="bg-white rounded-[16px] shadow-2xl w-full max-w-[900px] max-h-[90vh] flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-[24px] font-extrabold text-[#111827] tracking-tight">Tambah Karyawan Baru</h2>
                        <p class="text-[14px] text-gray-500 mt-1 font-medium">Masukkan informasi detail untuk mendaftarkan anggota tim baru ke sistem.</p>
                    </div>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-[#f4f7fb]/30">
                    <form id="addEmployeeForm" action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        @csrf
                        
                        <!-- Left Column: Photo -->
                        <div class="lg:col-span-4">
                            <h3 class="font-bold text-[14px] text-gray-900 mb-4">Foto Profil</h3>
                            <label class="border-2 border-dashed border-gray-300 rounded-2xl p-8 flex flex-col items-center justify-center text-center bg-white hover:bg-blue-50/50 transition-colors cursor-pointer min-h-[220px] relative overflow-hidden">
                                
                                <template x-if="!photoPreview">
                                    <div>
                                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                        </div>
                                        <p class="text-[13.5px] font-bold text-gray-700 mb-1">Klik atau seret foto ke sini</p>
                                        <p class="text-[12px] text-gray-400 font-medium">Format JPG atau PNG, Maks. 2MB</p>
                                    </div>
                                </template>

                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="absolute inset-0 w-full h-full object-cover">
                                </template>

                                <input type="file" name="photo" class="hidden" accept="image/*" @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => photoPreview = e.target.result;
                                        reader.readAsDataURL(file);
                                    } else {
                                        photoPreview = null;
                                    }
                                ">
                            </label>
                        </div>

                        <!-- Right Column: Form Sections -->
                        <div class="lg:col-span-8 space-y-6">
                            <!-- Informasi Pribadi -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div class="flex items-center mb-5">
                                    <svg class="w-5 h-5 text-[#18398a] mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <h3 class="font-bold text-[15px] text-[#18398a]">Informasi Pribadi</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                                        <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">NIP (Nomor Induk Pegawai)</label>
                                        <input type="text" name="nip" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Contoh: 19950101" required>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Email Perusahaan</label>
                                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="nama@perusahaan.com" required>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">No. HP</label>
                                        <input type="text" name="phone" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="08123456789">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Alamat (Domisili)</label>
                                        <input type="text" name="address" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Kecamatan, Kabupaten, Provinsi">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                                        <input type="text" name="last_education" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 placeholder-gray-400 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="Contoh: S1 Teknik Informatika">
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Pekerjaan -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div class="flex items-center mb-5">
                                    <svg class="w-5 h-5 text-[#18398a] mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <h3 class="font-bold text-[15px] text-[#18398a]">Detail Pekerjaan</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Jabatan</label>
                                        <select name="position_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white" required>
                                            <option value="">Pilih Jabatan</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Posisi (Jenis Pekerjaan)</label>
                                        <select name="job_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white" required>
                                            <option value="">Pilih Jenis Pekerjaan</option>
                                            @foreach($jobTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Departemen</label>
                                        <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                                            <option value="">Pilih Departemen</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Lokasi Kerja</label>
                                        <select name="work_location" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                                            <option value="Kantor">Kantor</option>
                                            <option value="Remote">Remote</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Status Karyawan</label>
                                        <select name="employment_status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                                            <option value="tetap">Tetap</option>
                                            <option value="kontrak">Kontrak</option>
                                            <option value="intership">Intership</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Tanggal Bergabung</label>
                                        <input type="date" name="join_date" class="w-full md:w-1/2 border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none">
                                    </div>
                                </div>
                            </div>

                            <!-- Struktur Upah -->
                            @role('superadmin')
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div class="flex items-center mb-5">
                                    <svg class="w-5 h-5 text-[#18398a] mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <h3 class="font-bold text-[15px] text-[#18398a]">Struktur Upah</h3>
                                </div>
                                <div class="space-y-6">
                                    <!-- Upah Dasar & Potongan -->
                                    <div>
                                        <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Kompensasi Dasar</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Gaji Pokok</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="basic_salary" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Premi</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="premium" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tunjangan -->
                                    <div>
                                        <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Tunjangan</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Teknis</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="tunjangan_teknis" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Laptop</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="tunjangan_laptop" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Keluarga</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="tunjangan_keluarga" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reward -->
                                    <div>
                                        <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Reward</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Reward Disiplin</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="reward_disiplin" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Reward Solat</label>
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 font-bold text-[13px]">Rp</span>
                                                    </div>
                                                    <input type="number" name="reward_solat" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-[13px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endrole

                            <!-- Pengaturan Akun -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <div class="flex items-center mb-5">
                                    <svg class="w-5 h-5 text-[#18398a] mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    <h3 class="font-bold text-[15px] text-[#18398a]">Pengaturan Akun</h3>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Peran (Role)</label>
                                    <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-800 focus:ring-1 focus:ring-blue-500 outline-none bg-white mb-2">
                                        <option value="">Pilih Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                    <p class="text-[11.5px] text-gray-500 font-medium">Menentukan hak akses sistem pengguna.</p>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                
                <!-- Modal Footer -->
                <div class="px-8 py-5 border-t border-gray-100 bg-white flex justify-end items-center space-x-3">
                    <button @click="showModal = false" type="button" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-[14px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </button>
                    <button type="submit" form="addEmployeeForm" class="px-6 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Data Karyawan
                    </button>
                </div>
            </div>
        </div>

        <!-- Render Modals for Each Employee -->
        @foreach($employees as $employee)
        <!-- Modal View -->
        <div x-show="activeModal === 'view-{{ $employee->id }}'" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="activeModal = null" class="bg-white rounded-[16px] shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-[24px] font-extrabold text-[#111827] tracking-tight">Detail Karyawan</h2>
                        <p class="text-[14px] text-gray-500 mt-1 font-medium">Informasi lengkap karyawan.</p>
                    </div>
                    <button @click="activeModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-[#f4f7fb]/30">
                    <div class="flex items-center space-x-6 mb-8">
                        <div class="w-24 h-24 rounded-full overflow-hidden bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                            @if($employee->photo)
                                <img src="{{ Storage::url($employee->photo) }}" class="w-full h-full object-cover">
                            @elseif($employee->user->profile_photo_path)
                                <img src="{{ Storage::url($employee->user->profile_photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-extrabold text-blue-600">{{ strtoupper(substr($employee->user->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">{{ $employee->user->name }}</h3>
                            <p class="text-sm text-gray-500 font-medium">{{ $employee->position->name ?? '-' }} ({{ $employee->jobType->name ?? '-' }}) &bull; {{ $employee->department->name ?? '-' }}</p>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700 uppercase">{{ $employee->employment_status }}</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-5 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-sm text-gray-900 mb-4 pb-2 border-b">Informasi Pribadi</h4>
                            <dl class="space-y-3">
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Nama Lengkap</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->user->name }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Domisili</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->address ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Departemen</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->department->name ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Posisi</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->jobType->name ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Jabatan</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->position->name ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Pendidikan Terakhir</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->last_education ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">NIP</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->user->nip }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Email</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->user->email }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">No. HP</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->phone ?? '-' }}</dd></div>
                            </dl>
                        </div>
                        <div class="bg-white p-5 rounded-xl border border-gray-100">
                            <h4 class="font-bold text-sm text-gray-900 mb-4 pb-2 border-b">Detail Pekerjaan</h4>
                            <dl class="space-y-3">
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Lokasi Kerja</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->work_location ?? '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Tgl Bergabung</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d F Y') : '-' }}</dd></div>
                                <div><dt class="text-[11px] font-bold text-gray-500 uppercase">Role</dt><dd class="text-[13px] font-medium text-gray-900">{{ $employee->user->roles->first()->name ?? '-' }}</dd></div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-white p-5 rounded-xl border border-gray-100">
                        <h4 class="font-bold text-sm text-gray-900 mb-4 pb-2 border-b">Aset Kantor yang Dibawa</h4>
                        @if($employee->user->assets && $employee->user->assets->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 text-gray-600 text-[11px] uppercase font-bold border-b border-gray-100">
                                            <th class="py-2 px-3 rounded-tl-lg">Nama Aset</th>
                                            <th class="py-2 px-3">Nomor Seri</th>
                                            <th class="py-2 px-3 rounded-tr-lg">Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($employee->user->assets as $asset)
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="py-2 px-3 text-[13px] font-bold text-gray-900">{{ $asset->name }}</td>
                                            <td class="py-2 px-3 text-[13px] text-gray-600">{{ $asset->serial_number ?? '-' }}</td>
                                            <td class="py-2 px-3 text-[13px] text-gray-600">{{ $asset->description ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-[13px] text-gray-500 font-medium">Tidak ada aset kantor yang tercatat.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div x-show="activeModal === 'edit-{{ $employee->id }}'" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="activeModal = null" class="bg-white rounded-[16px] shadow-2xl w-full max-w-[900px] max-h-[90vh] flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-start bg-white">
                    <div>
                        <h2 class="text-[24px] font-extrabold text-[#111827] tracking-tight">Edit Karyawan</h2>
                        <p class="text-[14px] text-gray-500 mt-1 font-medium">Perbarui data informasi karyawan. Struktur upah dikelola di menu Payroll.</p>
                    </div>
                    <button @click="activeModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar flex-1 bg-[#f4f7fb]/30">
                    <form id="editEmployeeForm{{ $employee->id }}" action="{{ route('admin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        @csrf
                        @method('PUT')
                        
                        <!-- Left Column: Photo -->
                        <div class="lg:col-span-4" x-data="{ localPreview: '{{ $employee->photo ? Storage::url($employee->photo) : ($employee->user->profile_photo_path ? Storage::url($employee->user->profile_photo_path) : '') }}' }">
                            <h3 class="font-bold text-[14px] text-gray-900 mb-4">Foto Profil</h3>
                            <label class="border-2 border-dashed border-gray-300 rounded-2xl p-8 flex flex-col items-center justify-center text-center bg-white hover:bg-blue-50/50 transition-colors cursor-pointer min-h-[220px] relative overflow-hidden">
                                <template x-if="!localPreview">
                                    <div>
                                        <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                        </div>
                                        <p class="text-[13.5px] font-bold text-gray-700 mb-1">Klik atau seret foto</p>
                                    </div>
                                </template>
                                <template x-if="localPreview">
                                    <img :src="localPreview" class="absolute inset-0 w-full h-full object-cover">
                                </template>
                                <input type="file" name="photo" class="hidden" accept="image/*" @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        const reader = new FileReader();
                                        reader.onload = (e) => localPreview = e.target.result;
                                        reader.readAsDataURL(file);
                                    } else {
                                        localPreview = null;
                                    }
                                ">
                            </label>
                        </div>

                        <!-- Right Column: Form Sections -->
                        <div class="lg:col-span-8 space-y-6">
                            <!-- Informasi Pribadi -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Informasi Pribadi</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ old('name', $employee->user->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">NIP</label>
                                        <input type="text" name="nip" value="{{ old('nip', $employee->user->nip) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Email</label>
                                        <input type="email" name="email" value="{{ old('email', $employee->user->email) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">No. HP</label>
                                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Alamat (Domisili)</label>
                                        <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" placeholder="Kecamatan, Kabupaten, Provinsi">
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                                        <input type="text" name="last_education" value="{{ old('last_education', $employee->last_education) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" placeholder="Contoh: S1 Teknik Informatika">
                                    </div>
                                </div>
                            </div>

                            <!-- Detail Pekerjaan -->
                            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                                <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Detail Pekerjaan</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Jabatan</label>
                                        <select name="position_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Posisi (Jenis Pekerjaan)</label>
                                        <select name="job_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                            <option value="">Pilih Posisi</option>
                                            @foreach($jobTypes as $type)
                                                <option value="{{ $type->id }}" {{ $employee->job_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Departemen</label>
                                        <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{ $employee->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Lokasi Kerja</label>
                                        <select name="work_location" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                            <option value="Kantor" {{ $employee->work_location == 'Kantor' ? 'selected' : '' }}>Kantor</option>
                                            <option value="Remote" {{ $employee->work_location == 'Remote' ? 'selected' : '' }}>Remote</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Status Karyawan</label>
                                        <select name="employment_status" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
                                            <option value="tetap" {{ $employee->employment_status == 'tetap' ? 'selected' : '' }}>Tetap</option>
                                            <option value="kontrak" {{ $employee->employment_status == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                                            <option value="intership" {{ $employee->employment_status == 'intership' ? 'selected' : '' }}>Magang</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Tanggal Bergabung</label>
                                        <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="w-full md:w-1/2 border border-gray-300 rounded-lg px-4 py-2.5 text-[14px]" required>
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
                    <button type="submit" form="editEmployeeForm{{ $employee->id }}" class="px-6 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div x-show="activeModal === 'delete-{{ $employee->id }}'" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="activeModal = null" class="bg-white rounded-[16px] shadow-2xl w-full max-w-[480px] flex flex-col overflow-hidden"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Modal Header -->
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-[18px] font-extrabold text-[#111827] tracking-tight">Hapus Data Karyawan</h2>
                            <p class="text-[12px] text-gray-400 font-medium">Konfirmasi tindakan penghapusan</p>
                        </div>
                    </div>
                    <button @click="activeModal = null" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 bg-[#f4f7fb]/30 text-center">
                    <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-red-50 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-[16px] font-extrabold text-gray-900 mb-2">Hapus Karyawan {{ $employee->user->name ?? '' }}?</h3>
                    <p class="text-[13.5px] text-gray-600 font-medium leading-relaxed">
                        Apakah Anda yakin ingin menghapus karyawan ini? Data yang terhapus tidak dapat dikembalikan.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-100 bg-white flex justify-end items-center space-x-3">
                    <button @click="activeModal = null" type="button" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-[13.5px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        TIDAK
                    </button>
                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-600 text-white text-[13.5px] font-bold rounded-lg hover:bg-red-700 transition-colors flex items-center shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            YA
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

    <!-- Modal Export Data Karyawan -->
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
                <button @click="showExportModal = false" type="button" class="text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 bg-[#f4f7fb]/30">
                <div class="space-y-4">
                    
                    <!-- Target Laporan -->
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Jenis Laporan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" 
                                    @click="exportTarget = 'all'" 
                                    :class="exportTarget === 'all' ? 'bg-[#18398a] text-white font-bold' : 'bg-white text-gray-700 border border-gray-300 font-semibold'"
                                    class="py-2.5 px-3 text-[13px] rounded-lg text-center transition-colors shadow-sm">
                                Seluruh Karyawan
                            </button>
                            <button type="button" 
                                    @click="exportTarget = 'individual'" 
                                    :class="exportTarget === 'individual' ? 'bg-[#18398a] text-white font-bold' : 'bg-white text-gray-700 border border-gray-300 font-semibold'"
                                    class="py-2.5 px-3 text-[13px] rounded-lg text-center transition-colors shadow-sm">
                                Karyawan Individu
                            </button>
                        </div>
                    </div>

                    <!-- Departemen Filter -->
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Departemen</label>
                        <select x-model="selectedDept" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Karyawan Individu Select (Jika Individual dipilih) -->
                    <div x-show="exportTarget === 'individual'">
                        <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Pilih Karyawan</label>
                        <select x-model="selectedEmpId" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($allEmployeesList as $emp)
                                <option value="{{ $emp->id }}" x-show="!selectedDept || selectedDept == '{{ $emp->department_id }}'">
                                    {{ $emp->user->name ?? 'User' }} (NIP: {{ $emp->user->nip ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Format Ekspor -->
                    <div>
                        <label class="block text-[13px] font-bold text-gray-700 mb-2 mt-2">Format Ekspor</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="fmt_emp_admin" value="excel" x-model="exportFormat" class="w-4 h-4 text-[#18398a] border-gray-300 focus:ring-[#18398a]">
                                <span class="ml-2 text-[14px] text-gray-700 font-medium">Excel (.xlsx)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="fmt_emp_admin" value="pdf" x-model="exportFormat" class="w-4 h-4 text-[#18398a] border-gray-300 focus:ring-[#18398a]">
                                <span class="ml-2 text-[14px] text-gray-700 font-medium">PDF Document (.pdf)</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button @click="showExportModal = false" type="button" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 text-[13.5px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </button>
                        <button type="button" 
                                @click="
                                    if (exportTarget === 'all') {
                                        let url = '{{ route('admin.employees.export') }}?format=' + exportFormat;
                                        if (selectedDept) url += '&department_id=' + selectedDept;
                                        window.location.href = url;
                                    } else {
                                        if (!selectedEmpId) { alert('Silakan pilih karyawan'); return; }
                                        window.location.href = '{{ url('/') }}/admin/employees/' + selectedEmpId + '/download';
                                    }
                                    showExportModal = false;
                                " 
                                class="px-5 py-2.5 bg-[#18398a] text-white text-[13.5px] font-bold rounded-lg hover:bg-[#122b6b] transition-colors shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh Laporan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
