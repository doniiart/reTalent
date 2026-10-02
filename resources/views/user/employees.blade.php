@extends('layouts.app')

@section('content')
    <!-- Header Rekan Kerja -->
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rekan Kerja</h2>
            <p class="text-gray-500 text-sm mt-1">Kenali rekan setim Anda lebih dekat untuk kolaborasi yang lebih baik.</p>
        </div>

        <form method="GET" action="{{ url()->current() }}">
            <select name="departemen" onchange="this.form.submit()" class="bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm font-medium cursor-pointer">
                <option value="">All Departments</option>
                <option value="Tim Finance" {{ request('departemen') == 'Tim Finance' ? 'selected' : '' }}>Tim Finance</option>
                <option value="Tim Sales" {{ request('departemen') == 'Tim Sales' ? 'selected' : '' }}>Tim Sales</option>
                <option value="Tim Konten" {{ request('departemen') == 'Tim Konten' ? 'selected' : '' }}>Tim Konten</option>
                <option value="Tim HG" {{ request('departemen') == 'Tim HG' ? 'selected' : '' }}>Tim HG</option>
                <option value="Tim Marketing" {{ request('departemen') == 'Tim Marketing' ? 'selected' : '' }}>Tim Marketing</option>
            </select>
        </form>
    </div>

    <!-- Grid Kartu Karyawan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse ($employees as $employee)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 text-center">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=random" class="w-20 h-20 rounded-full mx-auto mb-4">
            <h3 class="font-bold text-gray-800">{{ $employee->name }}</h3>
            <p class="text-xs text-gray-500">{{ $employee->jabatan ?? 'Karyawan' }}</p>
            <span class="inline-block mt-2 bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold">
                {{ $employee->departemen ?? 'Belum ada tim' }}
            </span>
            <button class="w-full mt-4 border border-gray-300 text-gray-700 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                Kirim Email
            </button>
        </div>
    @empty
        <div class="col-span-3 text-center py-8 text-gray-500">
            Tidak ada rekan kerja yang ditemukan di departemen ini.
        </div>
    @endforelse
</div>

    <!-- Summary Footer -->
    <div class="bg-[#eff6ff] border border-blue-100 rounded-xl p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="bg-[#1e3a8a] text-white p-3 rounded-lg"><i data-feather="users" class="w-6 h-6"></i></div>
            <div>
                <h4 class="text-lg font-bold text-gray-800">23 Rekan Kerja</h4>
                <p class="text-sm text-gray-500">Berada di Kantor</p>
            </div>
        </div>
        <div class="flex space-x-8 text-center divide-x divide-gray-300">
            <div class="px-4">
                <p class="text-xs text-gray-500 font-semibold mb-1">ENGINEERING</p>
                <p class="text-xl font-bold text-[#1e3a8a]">42</p>
            </div>
            <div class="px-4">
                <p class="text-xs text-gray-500 font-semibold mb-1">PRODUCT</p>
                <p class="text-xl font-bold text-[#1e3a8a]">18</p>
            </div>
        </div>
    </div>
@endsection