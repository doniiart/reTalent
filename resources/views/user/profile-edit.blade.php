@extends('layouts.app')

@section('content')
<div class="p-8 max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Edit Profil Saya</h2>
    
    <div class="bg-white p-8 rounded-2xl border shadow-sm">
        <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ $user->no_telepon }}" class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ $user->jabatan }}" class="w-full p-3 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="alamat" class="w-full p-3 border rounded-xl">{{ auth()->user()->alamat }}</textarea>
                </div>
                
                <input type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700">
                    Simpan Perubahan
                </input>
            </div>
        </form>
    </div>
</div>
@endsection