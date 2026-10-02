<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg">
                                @if($employee->photo)
                                    <img src="{{ Storage::url($employee->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $employee->user->name }}</h1>
                                <p class="text-gray-500">{{ $employee->position->name ?? 'Belum ada jabatan' }} - {{ $employee->department->name ?? 'Belum ada departemen' }}</p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase">{{ $employee->employment_status }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('superadmin.employees.download', $employee) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors font-semibold text-sm flex items-center shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Download Profil PDF
                            </a>
                            <a href="{{ route('superadmin.employees.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors font-semibold text-sm">Kembali</a>
                        </div>
                    </div>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 border-b pb-2 mb-4">Informasi Pribadi</h3>
                            <dl class="space-y-3">
                                <div><dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt><dd class="text-sm text-gray-900 font-bold">{{ $employee->user->name }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Domisili</dt><dd class="text-sm text-gray-900">{{ $employee->address ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Departemen</dt><dd class="text-sm text-gray-900">{{ $employee->department->name ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Posisi</dt><dd class="text-sm text-gray-900">{{ $employee->jobType->name ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Jabatan</dt><dd class="text-sm text-gray-900">{{ $employee->position->name ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Pendidikan Terakhir</dt><dd class="text-sm text-gray-900">{{ $employee->last_education ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">NIP</dt><dd class="text-sm text-gray-900 font-bold">{{ $employee->user->nip }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="text-sm text-gray-900">{{ $employee->user->email }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">No. HP</dt><dd class="text-sm text-gray-900">{{ $employee->phone ?? '-' }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 border-b pb-2 mb-4">Detail Pekerjaan</h3>
                            <dl class="space-y-3">
                                <div><dt class="text-sm font-medium text-gray-500">Lokasi Kerja</dt><dd class="text-sm text-gray-900">{{ $employee->work_location ?? '-' }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt><dd class="text-sm text-gray-900">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d F Y') : '-' }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
