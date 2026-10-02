{{-- Halaman permintaan reset kata sandi (Lupa Password) --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - ReTalent HRIS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-[#f4f7fb] min-h-screen flex flex-col" x-data="{ helpOpen: false }">

    <!-- Top Navbar -->
    <nav class="bg-white px-8 py-4 flex justify-between items-center z-10 shadow-sm border-b border-gray-100">
        <div class="text-2xl font-bold text-[#0c2f82]">ReTalent <span class="font-normal">HRIS</span></div>
        <button type="button" @click="helpOpen = true" class="text-sm font-medium text-gray-600 hover:text-gray-900 cursor-pointer">Bantuan</button>
    </nav>

    <!-- Main Content Box -->
    <main class="flex-grow flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-[1100px] bg-white rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.08)] overflow-hidden flex flex-col md:flex-row min-h-[580px]">
            
            <!-- Left Side (Blue Background) -->
            <div class="w-full md:w-[45%] bg-[#0a297a] text-white p-10 flex flex-col justify-center">
                <h1 class="text-[28px] font-extrabold mb-5 tracking-wide">CV.MIB</h1>
                <p class="text-[13px] leading-relaxed mb-10 text-gray-200">
                    CV.MIB adalah perusahaan holding di sukoharjo Jawa Tengah yang memiliki lini bisnis dibidang produksi dan distribusi bahan kimia supply laundry seperti penyedia bahan baku keperluan laundry, parfum laundry, car wash, sabun cuci piring, produk kebersihan rumah tangga lainnya dan lini bisnis software house pengembang aplikasi teknologi. Perusahaan ini di isi oleh talenta muda berintegritas yang terus bertumbuh dalam nilai-nilai keimanan.
                </p>

                <div class="space-y-7">
                    <!-- Feature 1 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-[#284999] p-2.5 rounded-lg mr-4 border border-[#3f5fab]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-bold mb-1">Reset Sandi Aman</h3>
                            <p class="text-[12px] text-gray-300 leading-tight">Proses verifikasi kata sandi aman melalui tautan khusus yang dikirimkan langsung ke email resmi Anda.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-[#284999] p-2.5 rounded-lg mr-4 border border-[#3f5fab]">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-bold mb-1">Seluruh Role Terintegrasi</h3>
                            <p class="text-[12px] text-gray-300 leading-tight">Berlaku untuk akun SuperAdmin, Admin, maupun Karyawan secara otomatis dan terstruktur.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side (Forgot Password Form) -->
            <div class="w-full md:w-[55%] p-10 md:p-14 flex flex-col justify-center">
                <div class="w-full max-w-[400px] mx-auto">
                    <h2 class="text-3xl font-bold text-[#0c2f82] mb-1">Lupa Password?</h2>
                    <p class="text-[13px] text-gray-500 mb-8">Masukkan email Anda. Kami akan mengirimkan tautan untuk menyetel ulang kata sandi Anda.</p>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 p-3 rounded-md flex items-center">
                            <p class="text-sm text-red-500 font-medium">{{ $errors->first() }}</p>
                        </div>
                    @endif
                    
                    <!-- Session Status Message -->
                    @if (session('status'))
                        <div class="mb-6 bg-green-50 border border-green-200 p-3 rounded-md flex items-center text-green-700 text-sm font-medium">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-[#0c2f82] mb-1.5">Email Kantor</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                                </div>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#0c2f82] focus:ring-[#0c2f82] text-sm text-gray-800 {{ $errors->has('email') ? 'border-red-300' : '' }}" placeholder="Masukkan Email Terdaftar">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-[15px] font-bold text-white bg-[#0c2f82] hover:bg-[#0a235c] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0c2f82] transition-colors">
                                Kirim Link Reset Password
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-[#0c2f82] hover:text-blue-900 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Halaman Login
                        </a>
                    </div>

                    <div class="mt-8 bg-[#f5f8ff] p-4 rounded-md flex items-start border border-blue-100">
                        <div class="flex-shrink-0 text-[#0c2f82] mr-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[12px] text-gray-600 leading-relaxed">
                            Tautan reset kata sandi berlaku selama 60 menit. Jika email tidak masuk ke Kotak Masuk (Inbox), silakan periksa folder Spam/Junk Anda.
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
