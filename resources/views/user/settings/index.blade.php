<x-app-layout>
<div class="max-w-7xl mx-auto pb-12" x-data="{ activeTab: 'profile' }">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Pengaturan</h1>
        <p class="text-[14px] text-gray-500 font-medium mt-1">Kelola profil, keamanan, dan preferensi akun Anda.</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center font-bold text-[13px]">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
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
        
        <!-- Left Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col items-center text-center">
                <div class="relative mb-4 group cursor-pointer" onclick="document.getElementById('photoInput').click()">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-gray-50 shadow-sm">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-[#18398a] to-[#2563eb] text-white flex items-center justify-center text-[32px] font-extrabold border-4 border-gray-50 shadow-sm">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <div class="absolute -bottom-2 -right-2 bg-[#18398a] text-white w-8 h-8 rounded-full flex items-center justify-center border-2 border-white shadow-sm group-hover:bg-[#122b6b] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>
                
                <form action="{{ route('user.settings.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="hidden">
                    @csrf
                    <input type="file" name="photo" id="photoInput" accept="image/*" onchange="document.getElementById('photoForm').submit()">
                </form>

                <h2 class="text-[20px] font-extrabold text-gray-900">{{ auth()->user()->name }}</h2>
                <p class="text-[13px] font-medium text-gray-500 mb-4">{{ auth()->user()->email }}</p>
                
                <div class="bg-[#f4f7fb] text-[#18398a] px-4 py-1.5 rounded-full text-[12px] font-bold inline-flex items-center">
                    <span class="w-2 h-2 rounded-full bg-[#18398a] mr-2"></span>
                    Aktif • {{ $employee->position->name ?? (auth()->user()->hasRole('superadmin') ? 'Superadmin' : 'Karyawan') }}
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <nav class="flex flex-col">
                    <a href="#" @click.prevent="activeTab = 'profile'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'profile', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'profile' }" class="px-6 py-4 flex items-center text-[14px] transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil Pribadi
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="#" @click.prevent="activeTab = 'security'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'security', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'security' }" class="px-6 py-4 flex items-center text-[14px] transition-colors border-t border-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Keamanan & Sandi
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="#" @click.prevent="activeTab = 'notifications'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'notifications', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'notifications' }" class="px-6 py-4 flex items-center text-[14px] transition-colors border-t border-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        Preferensi Notifikasi
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    
                    <a href="#" @click.prevent="activeTab = 'assets'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'assets', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'assets' }" class="px-6 py-4 flex items-center text-[14px] transition-colors border-t border-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Aset Kantor
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>

                    @if(auth()->user()->hasRole(['superadmin', 'admin']))
                    <a href="#" @click.prevent="activeTab = 'attendance'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'attendance', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'attendance' }" class="px-6 py-4 flex items-center text-[14px] transition-colors border-t border-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Pengaturan Absensi
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('superadmin'))
                    <a href="#" @click.prevent="activeTab = 'master'" :class="{ 'bg-gray-50 text-[#18398a] border-l-4 border-l-[#18398a] font-bold': activeTab === 'master', 'text-gray-600 font-medium hover:bg-gray-50 border-l-4 border-l-transparent': activeTab !== 'master' }" class="px-6 py-4 flex items-center text-[14px] transition-colors border-t border-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        Data Master Perusahaan
                        <svg class="w-4 h-4 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    @endif
                </nav>
            </div>
            
        </div>
        
        <!-- Right Content Area -->
        <div class="lg:col-span-8">
            
            <!-- Profil Pribadi Tab -->
            <div x-show="activeTab === 'profile'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('user.settings.profile') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h3 class="font-extrabold text-[16px] text-gray-900">Informasi Profil</h3>
                        <button type="submit" class="inline-flex justify-center rounded-lg px-4 py-2 bg-[#18398a] hover:bg-[#122b6b] text-[13px] font-bold text-white transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm" required>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Email Perusahaan</label>
                                <input type="email" value="{{ $user->email }}" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 text-[14px] font-medium text-gray-500 shadow-sm cursor-not-allowed" readonly disabled>
                                <p class="text-[11px] text-gray-500 italic mt-1.5">Email tidak dapat diubah oleh karyawan.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor Telepon -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm">
                            </div>
                            
                            <!-- Jabatan -->
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Jabatan</label>
                                <input type="text" value="{{ $employee->position->name ?? (auth()->user()->hasRole('superadmin') ? 'Superadmin' : '-') }}" class="w-full border border-gray-200 bg-[#f4f7fb] rounded-lg px-4 py-2.5 text-[14px] font-bold text-[#18398a] shadow-sm cursor-not-allowed" readonly disabled>
                            </div>
                        </div>
                        
                        <!-- Alamat -->
                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2">Alamat Tinggal</label>
                            <textarea name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm">{{ old('address', $employee->address ?? '') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Keamanan & Sandi Tab -->
            <div x-show="activeTab === 'security'" style="display: none;" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('user.settings.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="font-extrabold text-[16px] text-gray-900">Keamanan & Kata Sandi</h3>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-6">
                            <div class="mb-4 md:mb-0 md:w-1/2">
                                <label class="block text-[14px] font-bold text-gray-900">Kata Sandi Saat Ini</label>
                                <p class="text-[12px] text-gray-500 font-medium">Verifikasi identitas Anda.</p>
                            </div>
                            <div class="md:w-1/2 relative" x-data="{ showPassword: false }">
                                <input :type="showPassword ? 'text' : 'password'" name="current_password" class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm placeholder-gray-400" placeholder="••••••••" required>
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer" tabindex="-1" aria-label="Toggle password visibility">
                                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" style="display: none;" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.122-.463c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row md:items-start justify-between pb-2">
                            <div class="mb-4 md:mb-0 md:w-1/2 pt-2">
                                <label class="block text-[14px] font-bold text-gray-900">Kata Sandi Baru</label>
                                <p class="text-[12px] text-gray-500 font-medium">Minimal 8 karakter.</p>
                            </div>
                            <div class="md:w-1/2 space-y-4">
                                <div class="relative" x-data="{ showPassword: false }">
                                    <input :type="showPassword ? 'text' : 'password'" name="new_password" class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm" placeholder="Masukkan kata sandi baru" required>
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer" tabindex="-1" aria-label="Toggle password visibility">
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" style="display: none;" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.018 10.018 0 013.122-.463c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="inline-flex justify-center rounded-lg px-6 py-2.5 bg-white border border-[#18398a] text-[#18398a] hover:bg-gray-50 text-[13px] font-bold transition-colors shadow-sm">
                                Ubah Kata Sandi
                            </button>
                        </div>
                        
                    </div>
                </form>
            </div>
            
            <!-- Preferensi Notifikasi Tab -->
            <div x-show="activeTab === 'notifications'" style="display: none;" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
                x-data="{
                    notifyEmail: {{ auth()->user()->notify_email ? 'true' : 'false' }},
                    notifyAttendance: {{ auth()->user()->notify_attendance ? 'true' : 'false' }},
                    notifyLeave: {{ auth()->user()->notify_leave ? 'true' : 'false' }},
                    saving: false,
                    savedMessage: '',
                    toggleSetting(key) {
                        this[key] = !this[key];
                        this.savePreferences();
                    },
                    savePreferences() {
                        this.saving = true;
                        fetch('{{ route('user.settings.notifications') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                notify_email: this.notifyEmail,
                                notify_attendance: this.notifyAttendance,
                                notify_leave: this.notifyLeave
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.saving = false;
                            this.savedMessage = 'Preferensi disimpan';
                            setTimeout(() => this.savedMessage = '', 3000);
                        })
                        .catch(err => {
                            this.saving = false;
                        });
                    }
                }">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-[16px] text-gray-900">Preferensi Notifikasi</h3>
                        <p class="text-[12px] text-gray-500 font-medium">Atur jenis notifikasi sistem dan email yang ingin Anda terima.</p>
                    </div>
                    <div x-show="saving" class="text-xs text-blue-600 font-bold animate-pulse">Menyimpan...</div>
                    <div x-show="savedMessage" class="text-xs text-emerald-600 font-bold" x-text="savedMessage"></div>
                </div>
                
                <div class="p-6 space-y-0 divide-y divide-gray-100">
                    
                    <!-- Notifikasi Email -->
                    <div class="py-5 first:pt-0 flex items-center justify-between">
                        <div>
                            <h4 class="text-[14px] font-bold text-gray-900">Notifikasi Email</h4>
                            <p class="text-[12px] text-gray-500 font-medium">Dapatkan ringkasan harian dan pengingat lewat email.</p>
                        </div>
                        <button type="button" 
                            @click="toggleSetting('notifyEmail')" 
                            :class="notifyEmail ? 'bg-[#18398a]' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" role="switch">
                            <span :class="notifyEmail ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    
                    <!-- Status Kehadiran -->
                    <div class="py-5 flex items-center justify-between">
                        <div>
                            <h4 class="text-[14px] font-bold text-gray-900">Status Kehadiran</h4>
                            <p class="text-[12px] text-gray-500 font-medium">Notifikasi saat absen masuk/keluar berhasil atau gagal.</p>
                        </div>
                        <button type="button" 
                            @click="toggleSetting('notifyAttendance')" 
                            :class="notifyAttendance ? 'bg-[#18398a]' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" role="switch">
                            <span :class="notifyAttendance ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    
                    <!-- Persetujuan Cuti -->
                    <div class="py-5 pb-2 flex items-center justify-between">
                        <div>
                            <h4 class="text-[14px] font-bold text-gray-900">Persetujuan Cuti</h4>
                            <p class="text-[12px] text-gray-500 font-medium">Update instan saat pengajuan cuti Anda diproses.</p>
                        </div>
                        <button type="button" 
                            @click="toggleSetting('notifyLeave')" 
                            :class="notifyLeave ? 'bg-[#18398a]' : 'bg-gray-200'"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" role="switch">
                            <span :class="notifyLeave ? 'translate-x-5' : 'translate-x-0'" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    
                </div>
            </div>

            <!-- Pengaturan Absensi Tab -->
            @if(auth()->user()->hasRole(['superadmin', 'admin']))
            <div x-show="activeTab === 'attendance'" style="display: none;" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ route('user.settings.attendance.update') }}" method="POST">
                    @csrf
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h3 class="font-extrabold text-[16px] text-gray-900">Pengaturan Absensi</h3>
                        <button type="submit" class="inline-flex justify-center rounded-lg px-4 py-2 bg-[#18398a] hover:bg-[#122b6b] text-[13px] font-bold text-white transition-colors shadow-sm">
                            Simpan Pengaturan
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Jam Absen Masuk (WIB)</label>
                                <input type="time" name="jam_masuk" value="{{ $jamMasuk }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm" required>
                                <p class="text-[11px] text-gray-500 mt-1">Waktu dimulainya jam kerja.</p>
                            </div>
                            
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-2">Jam Absen Keluar (WIB)</label>
                                <input type="time" name="jam_keluar" value="{{ $jamKeluar }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm" required>
                                <p class="text-[11px] text-gray-500 mt-1">Waktu berakhirnya jam kerja.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 mb-2">Toleransi Terlambat (Menit)</label>
                            <input type="number" name="toleransi_terlambat" value="{{ $toleransiTerlambat }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-[14px] focus:ring-[#18398a] font-medium text-gray-900 shadow-sm" required>
                            <p class="text-[11px] text-gray-500 mt-1">Batas keterlambatan. Jika lewat dari (Jam Masuk + Toleransi), status menjadi Terlambat (Late).</p>
                        </div>
                    </div>
                </form>
            </div>
            @endif

            <!-- Data Master Tab (Superadmin) -->
            @if(auth()->user()->hasRole('superadmin'))
            <div x-show="activeTab === 'master'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
                <div class="p-6 border-b border-gray-100 bg-white">
                    <h3 class="font-extrabold text-[16px] text-gray-900">Data Master Perusahaan</h3>
                    <p class="text-[13px] text-gray-500 mt-1">Kelola data Departemen, Jabatan, dan Jenis Pekerjaan di sini.</p>
                </div>
                <div class="p-6 space-y-8 bg-gray-50">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Departemen -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                            <div class="p-4 border-b border-gray-100 bg-white flex justify-between items-center">
                                <h4 class="font-extrabold text-[14px] text-gray-900">Departemen</h4>
                            </div>
                            <div class="p-4 flex-1">
                                <form action="{{ route('user.settings.departments.store') }}" method="POST" class="flex gap-2 mb-4">
                                    @csrf
                                    <input type="text" name="name" placeholder="Tambah Departemen..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm" required>
                                    <button type="submit" class="bg-[#18398a] hover:bg-[#122b6b] text-white px-3 py-2 rounded-lg text-[13px] font-bold shadow-sm transition-colors">+</button>
                                </form>
                                <div class="space-y-2">
                                    @foreach($departments as $dept)
                                    <div class="flex items-center justify-between p-2.5 bg-white border border-gray-100 rounded-lg shadow-sm">
                                        <span class="text-[13px] font-bold text-gray-700">{{ $dept->name }}</span>
                                        <form action="{{ route('user.settings.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                            <div class="p-4 border-b border-gray-100 bg-white flex justify-between items-center">
                                <h4 class="font-extrabold text-[14px] text-gray-900">Jabatan</h4>
                            </div>
                            <div class="p-4 flex-1">
                                <form action="{{ route('user.settings.positions.store') }}" method="POST" class="flex gap-2 mb-4">
                                    @csrf
                                    <input type="text" name="name" placeholder="Tambah Jabatan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm" required>
                                    <button type="submit" class="bg-[#18398a] hover:bg-[#122b6b] text-white px-3 py-2 rounded-lg text-[13px] font-bold shadow-sm transition-colors">+</button>
                                </form>
                                <div class="space-y-2">
                                    @foreach($positions as $pos)
                                    <div class="flex items-center justify-between p-2.5 bg-white border border-gray-100 rounded-lg shadow-sm">
                                        <span class="text-[13px] font-bold text-gray-700">{{ $pos->name }}</span>
                                        <form action="{{ route('user.settings.positions.destroy', $pos) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Jenis Pekerjaan -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                            <div class="p-4 border-b border-gray-100 bg-white flex justify-between items-center">
                                <h4 class="font-extrabold text-[14px] text-gray-900">Jenis Pekerjaan</h4>
                            </div>
                            <div class="p-4 flex-1">
                                <form action="{{ route('user.settings.job_types.store') }}" method="POST" class="flex gap-2 mb-4">
                                    @csrf
                                    <input type="text" name="name" placeholder="Tambah Jenis Pekerjaan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm" required>
                                    <button type="submit" class="bg-[#18398a] hover:bg-[#122b6b] text-white px-3 py-2 rounded-lg text-[13px] font-bold shadow-sm transition-colors">+</button>
                                </form>
                                <div class="space-y-2">
                                    @foreach($jobTypes as $type)
                                    <div class="flex items-center justify-between p-2.5 bg-white border border-gray-100 rounded-lg shadow-sm">
                                        <span class="text-[13px] font-bold text-gray-700">{{ $type->name }}</span>
                                        <form action="{{ route('user.settings.job_types.destroy', $type) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Aset Kantor Tab -->
            <div x-show="activeTab === 'assets'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="font-extrabold text-[16px] text-gray-900">Aset Kantor</h3>
                        <p class="text-[13px] text-gray-500 font-medium mt-1">Daftar aset perusahaan yang sedang Anda bawa/gunakan.</p>
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    <form action="{{ route('assets.store') }}" method="POST" class="bg-gray-50 p-4 rounded-xl border border-gray-100 mb-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[12px] font-bold text-gray-700 mb-1">Nama Aset <span class="text-red-500">*</span></label>
                                <input type="text" name="name" placeholder="Contoh: Laptop Asus ROG" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm" required>
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-gray-700 mb-1">Nomor Seri</label>
                                <input type="text" name="serial_number" placeholder="Contoh: SN12345678" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm">
                            </div>
                            <div>
                                <label class="block text-[12px] font-bold text-gray-700 mb-1">Deskripsi</label>
                                <input type="text" name="description" placeholder="Contoh: Warna hitam, RAM 16GB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px] focus:ring-[#18398a] shadow-sm">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-[#18398a] hover:bg-[#122b6b] text-white px-4 py-2 rounded-lg text-[13px] font-bold shadow-sm transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Aset
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-[12px] font-bold border-b border-gray-100">
                                    <th class="py-3 px-4 rounded-tl-lg">Nama Aset</th>
                                    <th class="py-3 px-4">Nomor Seri</th>
                                    <th class="py-3 px-4">Deskripsi</th>
                                    <th class="py-3 px-4 text-center rounded-tr-lg">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse(auth()->user()->assets as $asset)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-4 text-[13px] font-bold text-gray-900">{{ $asset->name }}</td>
                                    <td class="py-3 px-4 text-[13px] text-gray-600">{{ $asset->serial_number ?? '-' }}</td>
                                    <td class="py-3 px-4 text-[13px] text-gray-600">{{ $asset->description ?? '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini dari daftar?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-1 bg-red-50 rounded-md hover:bg-red-100 transition-colors" title="Hapus Aset">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-[13px] text-gray-500 font-medium">Belum ada data aset kantor yang Anda bawa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
