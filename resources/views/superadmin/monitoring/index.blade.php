<x-app-layout>
    <div x-data="{ showExportModal: false }" class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-[#18398a] tracking-tight">Monitoring Aktivitas</h1>
                <p class="text-sm md:text-[15px] text-gray-500 mt-1.5 font-medium">Pantau jejak audit dan aktivitas operasional tim HRD secara real-time.</p>
            </div>
            <div class="flex items-center">
                <button @click="showExportModal = true" class="px-5 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh Log Audit
                </button>
            </div>
        </div>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <!-- Total Aktivitas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 uppercase tracking-wider mb-1">Total Aktivitas Hari Ini</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">{{ number_format($todayActivities) }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    @if($percentChange >= 0)
                        <span class="text-[13px] font-bold text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            +{{ $percentChange }}%
                        </span>
                        <span class="text-[13px] text-gray-500 font-medium ml-2">dari kemarin</span>
                    @else
                        <span class="text-[13px] font-bold text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                            {{ $percentChange }}%
                        </span>
                        <span class="text-[13px] text-gray-500 font-medium ml-2">dari kemarin</span>
                    @endif
                </div>
            </div>

            <!-- Log Tertunda -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 uppercase tracking-wider mb-1">Log Aktivitas Tertunda</p>
                        <h2 class="text-3xl font-extrabold text-gray-900">0</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-[13px] text-gray-500 font-medium">Sinkronisasi sistem terakhir: 2 menit yang lalu</span>
                </div>
            </div>

            <!-- Alert Sistem -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[13px] font-bold text-gray-500 uppercase tracking-wider mb-1">Alert Sistem</p>
                        <h2 class="text-3xl font-extrabold text-red-600">0</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center">
                    <span class="text-[13px] font-bold text-red-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Semua sistem aman
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Table -->
            <div class="xl:col-span-2 space-y-6">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <form action="{{ route('superadmin.monitoring.index') }}" method="GET" style="display: grid; grid-template-columns: 1fr 1.6fr 1fr auto; gap: 12px; align-items: end;" class="mb-6">
                        <!-- 1. Nama Staf -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Staf</label>
                            <select name="staff_id" class="filter-select w-full">
                                <option value="">Semua Staf</option>
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}" {{ $staffId == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <!-- 2. Rentang Tanggal -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Rentang Tanggal</label>
                            <div class="flex items-center space-x-1.5">
                                <input type="date" name="start_date" value="{{ $startDate }}" class="filter-input w-full">
                                <span class="text-gray-400 text-xs font-bold">s/d</span>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="filter-input w-full">
                            </div>
                        </div>
                    
                        <!-- 3. Tipe Aktivitas -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tipe Aktivitas</label>
                            <select name="module" class="filter-select w-full">
                                <option value="">Semua Tipe</option>
                                @foreach($modules as $mod)
                                    <option value="{{ $mod }}" {{ $module == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <!-- 4. Tombol Filter -->
                        <div>
                            <button type="submit" class="w-full py-2.5 px-4 bg-[#18398a] text-white font-bold text-sm rounded-lg hover:bg-opacity-90 flex items-center justify-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                <span>Filter</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar touch-scroll">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-[#f8fafc] text-gray-700 text-[13px] font-bold border-b border-gray-200">
                                    <th class="py-4 px-6">Nama Staf</th>
                                    <th class="py-4 px-6">Role</th>
                                    <th class="py-4 px-6">Aktivitas</th>
                                    <th class="py-4 px-6">Modul</th>
                                    <th class="py-4 px-6">Waktu</th>
                                    <th class="py-4 px-6">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                @if($log->user->employee && $log->user->employee->photo)
                                                    <img src="{{ Storage::url($log->user->employee->photo) }}" class="w-8 h-8 rounded-full object-cover mr-3 border border-gray-200">
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-[#18398a] text-white flex items-center justify-center font-bold text-xs mr-3">
                                                        {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                <span class="font-bold text-gray-900 text-[14px]">{{ $log->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-600 text-[13px]">
                                            {{ $log->user->employee->position->name ?? 'Staff' }}
                                        </td>
                                        <td class="py-4 px-6 text-gray-900 font-medium text-[13px] max-w-xs truncate">
                                            {{ $log->activity }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">
                                                {{ $log->module }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500 text-[13px]">
                                            {{ $log->created_at->format('H:i:s') }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap w-max">
                                            @if($log->status == 'success')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                                    Success
                                                </span>
                                            @elseif($log->status == 'warning')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                    Warning
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                                    Error
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada log aktivitas</h3>
                                            <p class="mt-1 text-sm text-gray-500">Mulai gunakan sistem untuk melihat log aktivitas.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($logs->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Sidebar Widgets -->
            <div class="space-y-6">
                <!-- Sesi Aktif HRD -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            <h3 class="font-extrabold text-gray-900 text-[15px]">Sesi Aktif HRD</h3>
                        </div>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded uppercase tracking-wider">Live</span>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($activeSessions as $sessionUser)
                            <div class="p-4 border border-gray-100 rounded-lg hover:border-gray-300 transition-colors">
                                <div class="flex items-center mb-3">
                                    <div class="relative">
                                        @if($sessionUser->employee && $sessionUser->employee->photo)
                                            <img src="{{ Storage::url($sessionUser->employee->photo) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-[#18398a] text-white flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($sessionUser->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-white"></span>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-bold text-gray-900">{{ $sessionUser->name }}</h4>
                                        <p class="text-[11px] text-gray-500 font-medium">Login: {{ $sessionUser->activityLogs->first()->created_at->format('h:i A') ?? '08:00 AM' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-[12px] text-gray-600 font-medium bg-gray-50 p-2 rounded-md">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Modul: <span class="font-bold text-gray-900 ml-1">{{ $sessionUser->activityLogs->first()->module ?? '-' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center">Tidak ada sesi aktif.</p>
                        @endforelse
                        
                        <button class="w-full py-2.5 mt-2 bg-white border border-[#18398a] text-[#18398a] font-bold text-[13px] rounded-lg hover:bg-gray-50 transition-colors">
                            Lihat Semua Sesi
                        </button>
                    </div>
                </div>

                <!-- Keamanan Sistem -->
                <div class="bg-[#eff6ff] rounded-xl shadow-sm border border-blue-100 p-6 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500 rounded-full opacity-10"></div>
                    <div class="relative">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-700 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <h3 class="font-extrabold text-gray-900 text-[15px]">Keamanan Sistem</h3>
                        </div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-xs font-medium text-gray-600">Log Integrity</span>
                            <span class="text-xs font-bold text-emerald-600">100% Secure</span>
                        </div>
                        <div class="w-full bg-blue-200 rounded-full h-1.5 mb-4">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-[11px] leading-relaxed text-gray-600 font-medium">
                            Seluruh aktivitas dienkripsi dan diproteksi dengan verifikasi hash ganda untuk mencegah manipulasi data log.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Export -->
        <div x-show="showExportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showExportModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('superadmin.monitoring.export') }}" method="GET">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-5">
                                <h3 class="text-xl leading-6 font-bold text-gray-900">Ekspor Log Audit</h3>
                                <p class="text-sm text-gray-500 mt-1">Gunakan filter di bawah untuk menyesuaikan data log yang akan diekspor ke format PDF.</p>
                            </div>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Departemen (Karyawan/HRD)</label>
                                    <select name="department_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#18398a] focus:border-[#18398a] sm:text-sm">
                                        <option value="">Semua Departemen</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Rentang Tanggal</label>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <input type="date" name="start_date" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#18398a] focus:border-[#18398a] sm:text-sm" placeholder="Dari">
                                        <span class="text-gray-500 text-sm">s/d</span>
                                        <input type="date" name="end_date" class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#18398a] focus:border-[#18398a] sm:text-sm" placeholder="Sampai">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipe Aktivitas</label>
                                    <select name="module" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#18398a] focus:border-[#18398a] sm:text-sm">
                                        <option value="">Semua Tipe</option>
                                        @foreach($modules as $mod)
                                            <option value="{{ $mod }}">{{ $mod }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" @click="showExportModal = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#18398a] text-base font-medium text-white hover:bg-[#22449c] sm:ml-3 sm:w-auto sm:text-sm">Unduh PDF</button>
                            <button @click="showExportModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
