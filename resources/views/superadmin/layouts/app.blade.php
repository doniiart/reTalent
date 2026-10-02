<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#f4f7fb]" x-data="{ sidebarOpen: false, helpOpen: false }">
        <div class="flex h-screen overflow-hidden relative">

            <!-- Backdrop Overlay Mobile -->
            <div x-show="sidebarOpen" 
                 @click="sidebarOpen = false" 
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-30 lg:hidden"
                 style="display: none;">
            </div>

            <!-- Sidebar (Desktop static, Mobile drawer) -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-40 w-[260px] bg-[#22449c] text-white flex-shrink-0 flex flex-col shadow-xl transition-transform duration-300 ease-in-out lg:static lg:z-20">
                <div class="h-[70px] sm:h-[84px] flex items-center justify-between px-6 border-b border-white/10">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-white rounded-md flex items-center justify-center mr-3 font-bold text-[#22449c] text-xl">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5l-10-5v10l10 5 10-5v-10l-10 5z"></path></svg>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-wide block leading-tight">ReTalent</span>
                            <span class="text-[11px] text-blue-200 font-medium tracking-wider">HRIS Enterprise</span>
                        </div>
                    </div>
                    <!-- Mobile Close Sidebar Button -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-blue-200 hover:text-white p-1 rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto py-5 custom-scrollbar">
                    @include('superadmin.layouts.sidebar')
                </nav>
                <div class="p-4 border-t border-white/10">
                    <button type="button" @click="helpOpen = true" class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-blue-200 hover:text-white hover:bg-white/10 rounded-xl transition-colors mb-2 cursor-pointer text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Bantuan
                    </button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-blue-200 hover:text-white hover:bg-white/10 rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
                <!-- Top Header -->
                <header class="h-[70px] sm:h-[84px] bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 flex-shrink-0 gap-2 sm:gap-4">
                    <!-- Hamburger Button for Mobile/Tablet -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-600 hover:text-blue-700 hover:bg-gray-100 rounded-lg focus:outline-none flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
@php
    $currentRoute = request()->route()?->getName() ?? '';
    $searchContext = 'dashboard';
    $searchPlaceholder = 'Cari data karyawan, pengumuman, cuti, atau menu...';
    $searchLabel = 'Semua Menu';

    if (str_contains($currentRoute, 'employees')) {
        $searchContext = 'employees';
        $searchPlaceholder = 'Cari nama, NIP, email, atau jabatan karyawan...';
        $searchLabel = 'Menu Karyawan';
    } elseif (str_contains($currentRoute, 'payroll')) {
        $searchContext = 'payroll';
        $searchPlaceholder = 'Cari data slip gaji...';
        $searchLabel = 'Menu Gaji';
    } elseif (str_contains($currentRoute, 'attendance')) {
        $searchContext = 'attendance';
        $searchPlaceholder = 'Cari data rekap & absensi...';
        $searchLabel = 'Menu Kehadiran';
    } elseif (str_contains($currentRoute, 'leave')) {
        $searchContext = 'leave';
        $searchPlaceholder = 'Cari pengajuan cuti & izin...';
        $searchLabel = 'Menu Cuti';
    } elseif (str_contains($currentRoute, 'announcements')) {
        $searchContext = 'announcements';
        $searchPlaceholder = 'Cari pengumuman...';
        $searchLabel = 'Menu Pengumuman';
    } elseif (str_contains($currentRoute, 'monitoring')) {
        $searchContext = 'monitoring';
        $searchPlaceholder = 'Cari log audit & aktivitas...';
        $searchLabel = 'Menu Log Aktivitas';
    } elseif (str_contains($currentRoute, 'settings')) {
        $searchContext = 'settings';
        $searchPlaceholder = 'Cari pengaturan sistem...';
        $searchLabel = 'Menu Pengaturan';
    }
@endphp

                    <!-- Search dengan Live Search Context-Aware Dropdown -->
                    <div class="flex-1 max-w-[180px] xs:max-w-[220px] sm:max-w-xs md:max-w-md lg:w-[450px]" x-data="{
                        query: '',
                        results: [],
                        loading: false,
                        open: false,
                        context: '{{ $searchContext }}',
                        performSearch() {
                            if (this.query.trim().length < 1) {
                                this.results = [];
                                this.open = false;
                                return;
                            }
                            this.loading = true;
                            this.open = true;
                            fetch('{{ route('live.search') }}?q=' + encodeURIComponent(this.query) + '&context=' + this.context)
                                .then(res => res.json())
                                .then(data => {
                                    this.results = data.results || [];
                                    this.loading = false;
                                })
                                .catch(err => {
                                    this.loading = false;
                                });
                        }
                    }" @click.away="open = false" @keydown.escape="open = false">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 11-14 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" 
                                x-model="query" 
                                @input.debounce.150ms="performSearch()"
                                @focus="if(query.trim().length >= 1) open = true"
                                class="block w-full pl-9 sm:pl-11 pr-8 sm:pr-10 py-2 sm:py-3 border-0 bg-[#f4f7fb] rounded-lg text-xs sm:text-sm text-gray-800 placeholder-gray-500 font-medium focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm outline-none truncate" 
                                placeholder="{{ $searchPlaceholder }}">
                            
                            <!-- Loading indicator / clear button -->
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center">
                                <template x-if="loading">
                                    <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </template>
                                <template x-if="!loading && query.length > 0">
                                    <button @click="query = ''; results = []; open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </template>
                            </div>

                            <!-- Live Search Results Dropdown -->
                            <div x-show="open" style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden z-50 max-h-[420px] overflow-y-auto">
                                
                                <!-- Header Status -->
                                <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                    <span>Pencarian: {{ $searchLabel }}</span>
                                    <span x-text="loading ? 'Mencari...' : (results.length + ' hasil ditemukan')"></span>
                                </div>

                                <!-- Loading Skeleton -->
                                <template x-if="loading && results.length === 0">
                                    <div class="p-4 space-y-3">
                                        <div class="h-4 bg-gray-100 rounded w-3/4 animate-pulse"></div>
                                        <div class="h-4 bg-gray-100 rounded w-1/2 animate-pulse"></div>
                                    </div>
                                </template>

                                <!-- Results List -->
                                <template x-if="!loading && results.length > 0">
                                    <div class="divide-y divide-gray-50">
                                        <template x-for="(item, index) in results" :key="index">
                                            <a :href="item.url" class="flex items-center px-4 py-3 hover:bg-blue-50/70 transition-colors group">
                                                <div class="flex-shrink-0 mr-3">
                                                    <span class="px-2 py-1 text-[10px] font-bold rounded-md uppercase tracking-wider"
                                                        :class="{
                                                            'bg-blue-100 text-blue-700': item.type === 'employee',
                                                            'bg-emerald-100 text-emerald-700': item.type === 'announcement',
                                                            'bg-amber-100 text-amber-700': item.type === 'leave',
                                                            'bg-indigo-100 text-indigo-700': item.type === 'payroll',
                                                            'bg-teal-100 text-teal-700': item.type === 'attendance',
                                                            'bg-rose-100 text-rose-700': item.type === 'monitoring',
                                                            'bg-purple-100 text-purple-700': item.type === 'menu' || item.type === 'settings' || item.type === 'kpi'
                                                        }" x-text="item.badge"></span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-[13.5px] font-bold text-gray-900 group-hover:text-blue-700 truncate" x-text="item.title"></p>
                                                    <p class="text-[11.5px] text-gray-500 truncate mt-0.5" x-text="item.subtitle"></p>
                                                </div>
                                                <div class="flex-shrink-0 ml-2 text-gray-400 group-hover:text-blue-600 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </div>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                <!-- Empty State -->
                                <template x-if="!loading && results.length === 0 && query.trim().length >= 1">
                                    <div class="p-6 text-center">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 11-14 0 0114 0z"></path></svg>
                                        <p class="text-[13px] font-semibold text-gray-700">Tidak ada hasil ditemukan</p>
                                        <p class="text-[12px] text-gray-400 mt-0.5">Tidak ada data di {{ $searchLabel }} yang cocok dengan kata kunci ini.</p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Actions -->
                    <div class="flex items-center space-x-2 sm:space-x-4 lg:space-x-6 flex-shrink-0">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="relative text-gray-400 hover:text-gray-600 transition-colors focus:outline-none p-1">
                                <svg class="w-5 h-5 sm:w-[22px] sm:h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] font-bold text-white items-center justify-center border-2 border-white">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-3 w-72 sm:w-80 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-50 flex justify-between items-center">
                                    <h3 class="font-bold text-[14px] text-gray-800">Notifikasi</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.readAll') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[12px] font-medium text-blue-600 hover:text-blue-800">Tandai semua dibaca</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                    @forelse(auth()->user()->notifications->take(10) as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'opacity-60' : 'bg-blue-50/30' }} border-b border-gray-50 last:border-0">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    @if(isset($notification->data['icon']) && $notification->data['icon'] == 'clock')
                                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @elseif(isset($notification->data['icon']) && $notification->data['icon'] == 'calendar')
                                                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        </div>
                                                    @elseif(isset($notification->data['icon']) && $notification->data['icon'] == 'currency-dollar')
                                                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-3 w-0 flex-1">
                                                    <p class="text-[13px] font-semibold text-gray-900 leading-tight">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                    </p>
                                                    <p class="text-[12px] text-gray-500 mt-0.5 line-clamp-2">
                                                        {{ $notification->data['message'] ?? '' }}
                                                    </p>
                                                    <p class="text-[10px] text-gray-400 mt-1 font-medium">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <div class="flex-shrink-0 self-center ml-2">
                                                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center">
                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                            <p class="text-[13px] text-gray-500 font-medium">Belum ada notifikasi.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                        <div x-data="{ open: false }" class="relative">
                            <div @click="open = !open" @click.away="open = false" class="flex items-center cursor-pointer hover:bg-gray-50 px-1 sm:px-2 py-1 rounded-lg transition-colors">
                                <div class="mr-3 text-right hidden lg:block">
                                    <div class="text-[13px] font-bold text-[#22449c] leading-tight">{{ Auth::user()->name }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium tracking-wide">{{ Auth::user()->roles->first()->name ?? 'Administrator' }}</div>
                                </div>
                                
                                @php
                                    $navUserPhoto = Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : (Auth::user()->employee?->photo ? Storage::url(Auth::user()->employee->photo) : null);
                                @endphp

                                @if($navUserPhoto)
                                    <img src="{{ $navUserPhoto }}" alt="{{ Auth::user()->name }}" class="h-9 w-9 sm:h-10 sm:w-10 rounded-full object-cover border border-blue-200">
                                @else
                                    <div class="h-9 w-9 sm:h-10 sm:w-10 rounded-full bg-gradient-to-br from-[#18398a] to-[#2563eb] text-white flex items-center justify-center font-bold text-sm sm:text-[16px] shadow-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <svg class="w-4 h-4 text-gray-400 ml-1 sm:ml-2" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                                 style="display: none;">
                                
                                @php
                                    $settingsRoute = route('user.settings.index');
                                    if(auth()->user()->hasRole('superadmin')) {
                                        $settingsRoute = route('superadmin.settings.index');
                                    } elseif(auth()->user()->hasRole('admin')) {
                                        $settingsRoute = route('admin.settings.index');
                                    }
                                @endphp

                                <a href="{{ $settingsRoute }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-[#18398a] transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Profil Pribadi
                                </a>
                                
                                <div class="h-px bg-gray-100 my-1"></div>
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-[#f4f7fb] p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @include('components.help-modal')
    </body>
</html>

