@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Cuti</h2>
        <a href="{{ route('user.leave-apply') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Ajukan Cuti Baru
        </a>
    </div>

    <!-- Ringkasan Kartu Cuti -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <!-- Cuti Tahunan -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Cuti Tahunan</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-1">12 <span class="text-sm font-normal text-gray-400">/ 12 Hari</span></h3>
            <p class="text-green-500 text-xs mt-2 flex items-center">✓ Kuota Penuh</p>
        </div>
        <!-- Cuti Sakit -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Cuti Sakit</p>
            <h3 class="text-3xl font-bold text-blue-600 mt-1">Unlimited</h3>
            <p class="text-gray-400 text-xs mt-2">Sesuai Surat Dokter</p>
        </div>
        <!-- Cuti Khusus -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-sm text-gray-500 font-medium">Cuti Khusus</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-1">Tersedia</h3>
            <p class="text-gray-400 text-xs mt-2">Nikah, Berduka, dll.</p>
        </div>
    </div>

    <!-- Tabel Riwayat Pengajuan -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Riwayat Pengajuan</h3>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 text-sm">
                <tr>
                    <th class="px-6 py-4 font-medium">Jenis Cuti</th>
                    <th class="px-6 py-4 font-medium">Tanggal</th>
                    <th class="px-6 py-4 font-medium">Durasi</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($riwayatCuti as $cuti)
                <tr class="border-b border-gray-100">

                    <td class="py-3 px-4">{{ $cuti->leave_type }}</td>
                    <td class="py-3 px-4">{{ $cuti->start_date }} s/d {{ $cuti->end_date }}</td>

                    <td class="py-3 px-4">{{ $cuti->duration }} Hari</td>

                    <td class="py-3 px-4">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                            {{ $cuti->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">Belum ada riwayat pengajuan cuti.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection