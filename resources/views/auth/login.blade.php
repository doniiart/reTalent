{{-- Halaman form login pengguna --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - ReTalent HRIS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-[#f4f7fb] min-h-screen flex flex-col" x-data="{ helpOpen: false }">

    <!-- Top Navbar -->
    <nav class="bg-white px-4 sm:px-8 py-3.5 sm:py-4 flex justify-between items-center z-10 shadow-sm border-b border-gray-100">
        <div class="text-xl sm:text-2xl font-bold text-[#0c2f82]">ReTalent <span class="font-normal">HRIS</span></div>
        <button type="button" @click="helpOpen = true" class="text-xs sm:text-sm font-medium text-gray-600 hover:text-gray-900 cursor-pointer">Bantuan</button>
    </nav>

    <!-- Main Content Box -->
    <main class="flex-grow flex items-center justify-center p-4 sm:p-6 lg:p-10">
        <div class="w-full max-w-[1100px] bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden flex flex-col md:flex-row min-h-0 md:min-h-[580px]">
            
            <!-- Left Side (Blue Background) -->
            <div class="w-full md:w-[45%] bg-[#0a297a] text-white p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                <h1 class="text-2xl sm:text-[28px] font-extrabold mb-3 sm:mb-5 tracking-wide">CV.MIB</h1>
                <p class="text-[12px] sm:text-[13px] leading-relaxed mb-6 sm:mb-10 text-gray-200">
                    CV.MIB adalah perusahaan holding di sukoharjo Jawa Tengah yang memiliki lini bisnis dibidang produksi dan distribusi bahan kimia supply laundry seperti penyedia bahan baku keperluan laundry, parfum laundry, car wash, sabun cuci piring, produk kebersihan rumah tangga lainnya dan lini bisnis software house pengembang aplikasi teknologi. Perusahaan ini di isi oleh talenta muda berintegritas yang terus bertumbuh dalam nilai-nilai keimanan.
                </p>

                <div class="space-y-5 sm:space-y-7">
                    <!-- Feature 1 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-[#284999] p-2 sm:p-2.5 rounded-lg mr-3 sm:mr-4 border border-[#3f5fab]">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-[15px] font-bold mb-1">Bisnis Berkah</h3>
                            <p class="text-[11px] sm:text-[12px] text-gray-300 leading-tight">Kami percaya setiap aktivitas bisnis bisa menjadi jalan ibadah serta wasilah kebaikan dan kebermanfaatan bagi banyak orang.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-[#284999] p-2 sm:p-2.5 rounded-lg mr-3 sm:mr-4 border border-[#3f5fab]">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-[15px] font-bold mb-1">Amanah & Profesional</h3>
                            <p class="text-[11px] sm:text-[12px] text-gray-300 leading-tight">Kami berkomitmen menjalankan bisnis secara amanah, profesional, dan memberikan dampak positif yang nyata bagi masyarakat.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-[#284999] p-2 sm:p-2.5 rounded-lg mr-3 sm:mr-4 border border-[#3f5fab]">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-[15px] font-bold mb-1">Prinsip Syariah</h3>
                            <p class="text-[11px] sm:text-[12px] text-gray-300 leading-tight">Bisnis kami dijalankan tanpa Riba (berbunga), Maysir (Perjudian), Gharar (Tidak Pasti), dan Akad batil (akad tidak sesuai syariat)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side (Login Form) -->
            <div class="w-full md:w-[55%] p-6 sm:p-10 md:p-14 flex flex-col justify-center">
                <div class="w-full max-w-[400px] mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-[#0c2f82] mb-1">Masuk ke ReTalent</h2>
                    <p class="text-[12px] sm:text-[13px] text-gray-500 mb-6 sm:mb-8">Silakan masukkan kredensial Anda untuk mengakses sistem.</p>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 p-3 rounded-md flex items-center">
                            <p class="text-sm text-red-500 font-medium">{{ $errors->first() }}</p>
                        </div>
                    @endif
                    
                    @if (session('status'))
                        <div class="mb-6 bg-green-50 border border-green-200 p-3 rounded-md flex items-center text-green-700 text-sm font-medium">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <div class="flex justify-between items-baseline mb-1.5">
                                <label for="email" class="block text-sm font-bold text-[#0c2f82]">Email Kantor</label>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0c2f82] focus:ring-[#0c2f82] text-sm text-gray-800 {{ $errors->has('email') ? 'border-red-300' : '' }}" placeholder="Masukkan Email">
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-bold text-[#0c2f82] mb-1.5">Password</label>
                            <div class="relative" x-data="{ showPassword: false }">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" class="pl-10 pr-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0c2f82] focus:ring-[#0c2f82] text-sm text-gray-800 {{ $errors->has('password') ? 'border-red-300' : '' }}" placeholder="Masukkan Kata Sandi">
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

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#0c2f82] shadow-sm focus:ring-[#0c2f82] w-4 h-4" name="remember">
                                <span class="ms-2 text-[13px] text-gray-600">Ingat Saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-[13px] font-bold text-[#0c2f82] hover:text-blue-900" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <div class="pt-3">
                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-[15px] font-bold text-white bg-[#0c2f82] hover:bg-[#0a235c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0c2f82] transition-colors">
                                Masuk
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-8 bg-[#f5f8ff] p-4 rounded-md flex items-start border border-blue-100">
                        <div class="flex-shrink-0 text-[#0c2f82] mr-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[12px] text-gray-600 leading-relaxed">
                            Sistem ini memantau seluruh aktivitas login untuk keamanan data perusahaan. Pastikan Anda keluar setelah selesai bertugas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#f4f7fb] border-t border-gray-200 px-8 py-5 text-[13px] text-gray-500 flex flex-col md:flex-row justify-between items-center mt-auto z-10">
        <div class="mb-4 md:mb-0">
            <span class="font-bold text-gray-700 block mb-0.5">CV.MIB</span>
            <span>&copy; 2024 CV.MIB. Seluruh hak cipta dilindungi.</span>
        </div>
        <div class="flex space-x-6 font-medium">
            <a href="#" class="hover:text-gray-900">Kebijakan Privasi</a>
            <a href="#" class="hover:text-gray-900">Syarat & Ketentuan</a>
            <a href="#" class="hover:text-gray-900">Hubungi Kami</a>
        </div>
    </footer>

    @include('components.help-modal')
</body>
</html>
