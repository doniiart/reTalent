@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola profil, keamanan, dan preferensi akun Anda.</p>
    </div>

    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm text-center">
                <!-- Foto Profil (Sementara menggunakan inisial otomatis dari UI Avatars jika belum ada sistem upload) -->
                @if(auth()->user()->foto_profil)
                    <!-- Jika di database ada foto, maka tampilkan foto tersebut -->
                    <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="Foto Profil" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-2 border-gray-200">
                @else
                    <!-- Jika kosong, tampilkan inisial nama secara default -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="Inisial Profil" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-2 border-gray-200">
                @endif
                <!-- Nama dan Email Dinamis -->
                <h3 class="font-bold text-gray-800">{{ auth()->user()->name }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ auth()->user()->email }}</p>

                <!-- Jabatan Dinamis -->
                <span class="inline-block mt-3 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                    {{ auth()->user()->jabatan ?? 'Karyawan' }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <nav class="flex flex-col">
                    <a href="#" class="flex justify-between items-center px-6 py-4 border-l-4 border-[#1e3a8a] bg-blue-50 text-[#1e3a8a] font-medium">
                        <div class="flex items-center"><i data-feather="user" class="w-5 h-5 mr-3"></i> Profil Pribadi</div>
                        <i data-feather="chevron-right" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="flex justify-between items-center px-6 py-4 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 transition">
                        <div class="flex items-center"><i data-feather="lock" class="w-5 h-5 mr-3"></i> Keamanan & Sandi</div>
                        <i data-feather="chevron-right" class="w-4 h-4"></i>
                    </a>
                    <a href="#" class="flex justify-between items-center px-6 py-4 border-l-4 border-transparent text-gray-600 hover:bg-gray-50 transition">
                        <div class="flex items-center"><i data-feather="bell" class="w-5 h-5 mr-3"></i> Preferensi Notifikasi</div>
                        <i data-feather="chevron-right" class="w-4 h-4"></i>
                    </a>
                </nav>
            </div>
        </div>

        <div class="col-span-8 space-y-8">
    
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        
        <form id="profileForm" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Informasi Profil</h3>
                <button type="submit" class="bg-[#1e3a8a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition flex items-center shadow-sm">
                    <i data-feather="save" class="w-4 h-4 mr-2"></i> Simpan Perubahan
                </button>
            </div>
            
            <div class="p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <strong class="font-bold">Gagal Menyimpan!</strong>
                        <ul class="list-disc pl-5 mt-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 mr-2"></i> {{ session('success') }}
                    </div>
                @endif
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Perusahaan</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-gray-50 border border-gray-300 text-gray-500 py-2.5 px-4 rounded-lg cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1 italic">Email tidak dapat diubah oleh karyawan.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="no_telepon" id="no_telepon" value="{{ auth()->user()->no_telepon }}" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" id="jabatan" value="{{ auth()->user()->jabatan }}" readonly class="w-full bg-blue-50/50 border border-blue-100 text-gray-600 py-2.5 px-4 rounded-lg cursor-not-allowed outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Tinggal</label>
                    <textarea name="alamat" id="alamat" rows="3" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ auth()->user()->alamat }}</textarea>
                </div>

                <div class="col-span-2 mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Unggah Foto Profil Baru</label>
                <input type="file" name="foto_profil" accept="image/png, image/jpeg, image/jpg" class="w-full border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <p class="text-xs text-gray-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
            </div>
            </div>

            
        </form> 
    </div>

    

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <form action="{{ route('user.password.update') }}" method="POST">
                @csrf
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Keamanan & Kata Sandi</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Kata Sandi Saat Ini</label>
                            <p class="text-xs text-gray-500">Verifikasi identitas Anda.</p>
                        </div>
                        <div class="col-span-8">
                            <input type="password" name="current_password" class="w-full border border-gray-300 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                            <p class="text-xs text-gray-500">Minimal 8 karakter.</p>
                        </div>
                        <div class="col-span-8">
                            <input type="password" name="new_password" placeholder="Masukkan kata sandi baru" class="w-full border border-gray-300 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="border border-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Ubah Kata Sandi</button>
                    </div>
                </div>
            </div>
        </form>

        <form action="{{ route('user.preferences.update') }}" method="POST">
            @csrf
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Preferensi Notifikasi</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Notifikasi Email</p>
                            <p class="text-xs text-gray-500">Dapatkan ringkasan harian dan pengingat lewat email.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notif_email" value="1" class="sr-only peer" @checked(auth()->user()->notif_email)>
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#1e3a8a] after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                        </label>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Status Kehadiran</p>
                            <p class="text-xs text-gray-500">Notifikasi saat absen masuk/keluar berhasil atau gagal.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notif_kehadiran" value="1" class="sr-only peer" @checked(auth()->user()->notif_kehadiran)>
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#1e3a8a] after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                        </label>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Persetujuan Cuti</p>
                            <p class="text-xs text-gray-500">Update instan saat pengajuan cuti Anda diproses.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="notif_cuti" value="1" class="sr-only peer" @checked(auth()->user()->notif_cuti)>
                            <div class="w-12 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#1e3a8a] after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-6"></div>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection