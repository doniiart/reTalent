<x-app-layout>
<div x-data="{ activeModal: null }" class="max-w-7xl mx-auto pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <span>Beranda</span>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="font-semibold text-blue-800">Direktori Rekan Kerja</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Rekan Kerja</h1>
            <p class="text-gray-500 mt-2 text-[14.5px] max-w-2xl">
                Kenali rekan setim Anda lebih dekat untuk kolaborasi yang lebih baik. Gunakan fitur pencarian untuk menemukan departemen tertentu.
            </p>
        </div>
        
        <!-- Department Filter -->
        <form action="{{ route('admin.employees.index') }}" method="GET" class="relative group">
            <select name="department_id" onchange="this.form.submit()" class="appearance-none bg-blue-50/50 hover:bg-blue-100/50 border border-blue-200 text-blue-800 font-medium py-2.5 pl-10 pr-10 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors cursor-pointer text-sm">
                <option value="">Filter Departemen</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </div>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </form>
    </div>

    <!-- Employee Grid -->
    @if($employees->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($employees as $employee)
            <!-- Card -->
            <div class="bg-white rounded-[16px] border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col relative overflow-hidden group">
                <!-- Top Section: Avatar & Department Badge -->
                <div class="flex justify-between items-start mb-4">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 border-2 border-white shadow-sm flex items-center justify-center">
                            @php
                                $cardPhoto = $employee->photo ? Storage::url($employee->photo) : ($employee->user?->profile_photo_path ? Storage::url($employee->user->profile_photo_path) : null);
                            @endphp
                            @if($cardPhoto)
                                <img src="{{ $cardPhoto }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl font-extrabold text-blue-600">{{ strtoupper(substr($employee->user->name, 0, 2)) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($employee->department)
                        <span class="bg-blue-50 text-blue-700 text-[11px] font-bold px-3 py-1 rounded-full whitespace-nowrap overflow-hidden text-ellipsis max-w-[100px]" title="{{ $employee->department->name }}">
                            {{ $employee->department->name }}
                        </span>
                    @endif
                </div>

                <!-- Info Section -->
                <div class="flex-1">
                    <h3 class="text-[16px] font-extrabold text-gray-900 truncate" title="{{ $employee->user->name }}">
                        {{ $employee->user->name }}
                    </h3>
                    <p class="text-[13px] font-medium text-gray-500 truncate mt-0.5" title="{{ $employee->position->name ?? '-' }}">
                        {{ $employee->position->name ?? '-' }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex space-x-2 border-t border-gray-50 pt-4">
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $employee->user->email }}" target="_blank" class="flex-1 flex items-center justify-center space-x-2 py-2 px-3 bg-white border border-blue-200 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors font-semibold text-[13px]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>Kirim Email</span>
                    </a>
                    <button @click="activeModal = 'view-{{ $employee->id }}'" class="py-2 px-3 bg-white border border-gray-200 text-gray-500 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Modal for Employee Info -->
            <div x-show="activeModal === 'view-{{ $employee->id }}'" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-sm"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                
                <div @click.away="activeModal = null" class="bg-white rounded-2xl shadow-2xl w-[90%] sm:w-full sm:max-w-md max-h-[90vh] flex flex-col overflow-hidden mx-auto"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div class="relative bg-[#18398a] p-6 text-center text-white">
                        <button @click="activeModal = null" class="absolute top-4 right-4 text-white/70 hover:text-white transition-colors bg-black/10 hover:bg-black/20 p-1.5 rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="w-20 h-20 mx-auto rounded-full overflow-hidden bg-white border-[3px] border-white/20 flex items-center justify-center shadow-lg mb-3">
                            @if($cardPhoto)
                                <img src="{{ $cardPhoto }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl font-extrabold text-[#18398a]">{{ strtoupper(substr($employee->user->name, 0, 2)) }}</span>
                            @endif
                        </div>
                        <h2 class="text-[20px] font-extrabold leading-tight">{{ $employee->user->name }}</h2>
                        <p class="text-blue-100/90 font-medium text-[13px] mt-1">{{ $employee->position->name ?? '-' }} ({{ $employee->jobType->name ?? '-' }})</p>
                    </div>

                    <div class="p-5 overflow-y-auto custom-scrollbar flex-1 bg-white">
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 md:col-span-2">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Nama Lengkap</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->user->name }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 md:col-span-2">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Domisili</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->address ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Departemen</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->department->name ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Posisi</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->jobType->name ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Jabatan</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->position->name ?? '-' }}</span>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase mb-0.5">Pendidikan Terakhir</span>
                                <span class="text-[13px] font-semibold text-gray-800">{{ $employee->last_education ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 border-t border-gray-100 bg-gray-50">
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $employee->user->email }}" target="_blank" class="w-full flex items-center justify-center py-2.5 bg-[#18398a] hover:bg-blue-800 text-white rounded-xl transition-colors font-bold text-[13px] shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Kirim Email via Aplikasi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mb-8">
        {{ $employees->links() }}
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center shadow-sm">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Rekan Kerja Tidak Ditemukan</h3>
        <p class="text-gray-500">Tidak ada rekan kerja yang sesuai dengan filter pencarian Anda.</p>
    </div>
    @endif

    <!-- Bottom Banner (Stats) -->
    <div class="bg-[#eef2fe] rounded-[20px] p-5 md:p-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <!-- Total Employees -->
        <div class="flex items-center space-x-4 w-full md:w-auto justify-center md:justify-start">
            <div class="w-12 h-12 bg-[#18398a] rounded-xl flex items-center justify-center shadow-md shadow-blue-900/20 shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="text-left">
                <h3 class="text-[18px] md:text-[20px] font-extrabold text-[#111827] leading-tight">{{ $totalEmployees }} Rekan Kerja</h3>
                <p class="text-[13px] font-medium text-gray-600">Tersebar di {{ $totalDepartments }} Departemen</p>
            </div>
        </div>
        
        <div class="flex items-center justify-center gap-6 md:gap-10 border-y md:border-y-0 md:border-l border-blue-200/60 py-4 md:py-0 md:pl-8 w-full md:w-auto">
            <!-- Top 3 Departments Stats -->
            @foreach($topDepartments as $topDept)
            <div class="text-center">
                <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-0.5">{{ $topDept->name }}</span>
                <span class="text-[22px] font-extrabold text-[#18398a] leading-none">{{ $topDept->employees_count }}</span>
            </div>
            @endforeach
        </div>
        
        <!-- Small Avatars group on right -->
        <div class="flex -space-x-3 ml-2">
            @foreach($employees->take(3) as $emp)
                @if($emp->photo)
                    <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ Storage::url($emp->photo) }}">
                @else
                    <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($emp->user->name, 0, 2)) }}
                    </div>
                @endif
            @endforeach
            <div class="w-10 h-10 rounded-full border-2 border-white bg-[#5b8af0] text-white flex items-center justify-center font-bold text-[12px] relative z-10">
                +{{ max(0, $totalEmployees - 3) }}
            </div>
        </div>
    </div>
</div>
</x-app-layout>
