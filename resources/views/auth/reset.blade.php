@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="w-full max-w-md mx-auto px-8 relative">
    
    <h2 class="text-3xl font-bold text-gray-900 mb-2">Atur Ulang Kata Sandi</h2>
    <p class="text-gray-500 text-sm mb-8">Silakan masukkan kata sandi baru Anda untuk akun dengan NIP <span class="font-semibold text-[#0d2a7a]">2024010052</span>.</p>

    <form action="{{ route('password.reset.process') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru</label>
            <div class="relative">
                <input type="hidden" name="email" value="karyawan1@retalent.id">
                <input type="password" name="password" required class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2a7a] focus:border-transparent text-sm" placeholder="Masukkan kata sandi baru" required>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
                    <i data-feather="eye" class="w-4 h-4 text-gray-400 hover:text-gray-600"></i>
                </div>
            </div>
            <p class="mt-2 text-xs text-gray-500">Minimal 8 karakter dengan kombinasi huruf dan angka.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" required class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d2a7a] focus:border-transparent text-sm" placeholder="Ulangi kata sandi baru" required>
        </div>

        <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-[#0d2a7a] hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d2a7a]">
            Reset Kata Sandi
        </button>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-[#0d2a7a] hover:text-blue-800">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Login
            </a>
        </div>
    </form>
    
    <div class="fixed bottom-20 right-8 lg:absolute lg:-bottom-32 lg:right-0">
        <button class="flex items-center space-x-2 bg-blue-100 hover:bg-blue-200 text-[#0d2a7a] px-4 py-2 rounded-full font-medium transition shadow-sm border border-blue-200">
            <i data-feather="help-circle" class="w-5 h-5"></i>
            <span>Bantuan</span>
        </button>
    </div>
</div>
@endsection