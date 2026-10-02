{{-- Navigasi Sidebar Admin --}}
<ul class="space-y-1.5 px-4">
    <!-- Dashboard -->
    <li>
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.dashboard') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard
        </a>
    </li>

    <!-- Karyawan -->
    <li>
        <a href="{{ route('admin.employees.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.employees.*') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.employees.*') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Karyawan
        </a>
    </li>

    <!-- Gaji -->
    <li>
        <a href="{{ route('admin.payroll.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.payroll.*') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.payroll.*') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Gaji
        </a>
    </li>

    <!-- Kehadiran Rekap -->
    <li>
        <a href="{{ route('admin.attendance.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.attendance.index') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.attendance.index') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Kehadiran Rekap
        </a>
    </li>

    <!-- Absensi Saya -->
    <li>
        <a href="{{ route('admin.attendance.personal') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.attendance.personal') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.attendance.personal') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Absensi Saya
        </a>
    </li>

    <!-- Cuti -->
    <li>
        <a href="{{ route('admin.leave.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.leave.*') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.leave.*') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Cuti
        </a>
    </li>

    <!-- Pengumuman -->
    <li>
        <a href="{{ route('admin.announcements.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.announcements.*') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px]">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.announcements.*') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            Pengumuman
        </a>
    </li>


    <!-- Pengaturan -->
    <li>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'bg-[#5b8af0] text-white shadow-sm' : 'text-blue-100 hover:bg-white/10 hover:text-white' }} rounded-lg transition-all duration-200 font-medium text-[15px] mt-4">
            <svg class="w-[22px] h-[22px] mr-3.5 {{ request()->routeIs('admin.settings.*') ? 'text-[#18398a]' : 'text-blue-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Pengaturan
        </a>
    </li>
</ul>
