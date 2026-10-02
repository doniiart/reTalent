@extends('layouts.app')

@section('content')
    <!-- Header Penggajian -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Penggajian</h2>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Kolom Utama -->
        <div class="col-span-8 space-y-6">
            <!-- Card Gaji Terakhir -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-1">GAJI TERAKHIR (OKTOBER 2023)</p>
                    <div class="flex items-end space-x-3">
                        <h3 class="text-3xl font-bold text-[#1e3a8a]">Rp {{ number_format($gajiTerakhir->total_gaji ?? 0, 0, ',', '.') }}</h3>
                        <span class="text-green-500 text-sm font-medium flex items-center mb-1">
                            <i data-feather="arrow-up" class="w-3 h-3 mr-1"></i> 2.5%
                        </span>
                    </div>
                    <div class="flex space-x-8 mt-6">
                        <div>
                            <p class="text-xs text-gray-500">Gaji Pokok</p>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($gajiTerakhir->gaji_pokok ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tunjangan</p>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($gajiTerakhir->tunjangan ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Potongan</p>
                            <p class="font-semibold text-red-600">Rp {{ number_format($gajiTerakhir->potongan ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <button class="bg-[#1e3a8a] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition flex items-center shadow-sm h-fit">
                    <a href="{{ route('user.payroll.download') }}" class="bg-[#1e3a8a] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition flex items-center shadow-sm">
                        <i data-feather="download" class="w-4 h-4 mr-2"></i> Unduh Slip Gaji
                    </a>                
                </button>
            </div>

            <!-- Tabel Daftar Slip Gaji -->
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
    <h3 class="font-bold text-gray-800">Daftar Slip Gaji</h3>
    
    <form method="GET" action="{{ url()->current() }}">
        <select name="periode" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 py-1.5 px-3 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
            <option value="">Semua Periode</option>
            <option value="10-2026" {{ request('periode') == '10-2026' ? 'selected' : '' }}>Oktober 2026</option>
            <option value="09-2026" {{ request('periode') == '09-2026' ? 'selected' : '' }}>September 2026</option>
            <option value="08-2026" {{ request('periode') == '08-2026' ? 'selected' : '' }}>Agustus 2026</option>
            <option value="07-2026" {{ request('periode') == '07-2026' ? 'selected' : '' }}>Juli 2026</option>
            <option value="06-2026" {{ request('periode') == '06-2026' ? 'selected' : '' }}>Juni 2026</option>
    </select>
        </select>
    </form>
</div>
        </div>

        <!-- Kolom Samping -->
        <div class="col-span-4 space-y-6">
            <!-- Peringatan Keamanan -->
            <div class="bg-[#1e3a8a] text-white p-6 rounded-xl shadow-sm">
                <div class="flex items-center space-x-3 mb-3">
                    <i data-feather="shield" class="w-5 h-5"></i>
                    <h4 class="font-bold">Keamanan Data</h4>
                </div>
                <p class="text-sm text-blue-100 mb-4">Informasi penggajian bersifat rahasia. Jangan membagikan detail slip gaji atau login Anda kepada siapa pun.</p>
                <a href="#" class="text-sm text-white underline">Lihat Kebijakan Privasi</a>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
    <h4 class="font-bold text-gray-800 mb-5 flex items-center">
        <i data-feather="plus-circle" class="w-5 h-5 text-blue-600 mr-2"></i> Rincian Pendapatan
    </h4>
    
    <div class="space-y-4 text-sm text-gray-600">
        <div class="flex justify-between">
            <span>Gaji Pokok</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($gajiTerakhir->gaji_pokok ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tunjangan Teknis</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($gajiTerakhir->tunjangan_teknis ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tunjangan Laptop</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($gajiTerakhir->tunjangan_laptop ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Tunjangan Keluarga</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($gajiTerakhir->tunjangan_keluarga ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Reward Disiplin</span>
            <span class="font-medium text-green-600">+ Rp {{ number_format($gajiTerakhir->reward_disiplin ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>Reward Sholat</span>
            <span class="font-medium text-green-600">+ Rp {{ number_format($gajiTerakhir->reward_sholat ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span>JHT</span>
            <span class="font-medium text-gray-800">Rp {{ number_format($gajiTerakhir->jht ?? 0, 0, ',', '.') }}</span>
        </div>
        
        <hr class="my-3 border-gray-200">
        
        <div class="flex justify-between items-center font-bold text-lg text-gray-800">
            <span>Total Pendapatan</span>
            <span class="text-blue-700">Rp {{ number_format($gajiTerakhir->total_pendapatan ?? 0, 0, ',', '.') }}</span>
        </div>
    </div>
</div>
        </div>
    </div>
@endsection