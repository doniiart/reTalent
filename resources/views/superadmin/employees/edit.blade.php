<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6 text-sm font-medium">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="editEmployeeForm" action="{{ route('superadmin.employees.update', $employee) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    @csrf
                    @method('PUT')
                    
                    <!-- Left Column: Photo -->
                    <div class="lg:col-span-4" x-data="{ photoPreview: '{{ $employee->photo ? Storage::url($employee->photo) : '' }}' }">
                        <h3 class="font-bold text-[14px] text-gray-900 mb-4">Foto Profil</h3>
                        <label class="border-2 border-dashed border-gray-300 rounded-2xl p-8 flex flex-col items-center justify-center text-center bg-white hover:bg-blue-50/50 transition-colors cursor-pointer min-h-[220px] relative overflow-hidden">
                            
                            <template x-if="!photoPreview">
                                <div>
                                    <div class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path></svg>
                                    </div>
                                    <p class="text-[13.5px] font-bold text-gray-700 mb-1">Klik atau seret foto ke sini</p>
                                    <p class="text-[12px] text-gray-400 font-medium">Format JPG atau PNG, Maks. 2MB</p>
                                </div>
                            </template>

                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="absolute inset-0 w-full h-full object-cover">
                            </template>

                            <input type="file" name="photo" class="hidden" accept="image/*" @change="
                                const file = $event.target.files[0];
                                if(file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => photoPreview = e.target.result;
                                    reader.readAsDataURL(file);
                                } else {
                                    photoPreview = null;
                                }
                            ">
                        </label>
                    </div>

                    <!-- Right Column: Form Sections -->
                    <div class="lg:col-span-8 space-y-6">
                        <!-- Informasi Pribadi -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Informasi Pribadi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', $employee->user->name) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">NIP</label>
                                    <input type="text" name="nip" value="{{ old('nip', $employee->user->nip) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px] bg-gray-100 cursor-not-allowed" readonly required title="NIP tidak dapat diubah">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $employee->user->email) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">No. HP</label>
                                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Alamat (Domisili)</label>
                                    <input type="text" name="address" value="{{ old('address', $employee->address) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" placeholder="Kecamatan, Kabupaten, Provinsi">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Pendidikan Terakhir</label>
                                    <input type="text" name="last_education" value="{{ old('last_education', $employee->last_education) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]">
                                </div>
                            </div>
                        </div>

                        <!-- Detail Pekerjaan -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Detail Pekerjaan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Jabatan</label>
                                    <select name="position_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Posisi (Jenis Pekerjaan)</label>
                                    <select name="job_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                        <option value="">Pilih Jenis Pekerjaan</option>
                                        @foreach($jobTypes as $type)
                                            <option value="{{ $type->id }}" {{ $employee->job_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Departemen</label>
                                    <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $employee->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Lokasi Kerja</label>
                                    <select name="work_location" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                        <option value="Kantor" {{ $employee->work_location == 'Kantor' ? 'selected' : '' }}>Kantor</option>
                                        <option value="Remote" {{ $employee->work_location == 'Remote' ? 'selected' : '' }}>Remote</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Status Karyawan</label>
                                    <select name="employment_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                        <option value="tetap" {{ $employee->employment_status == 'tetap' ? 'selected' : '' }}>Tetap</option>
                                        <option value="kontrak" {{ $employee->employment_status == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                                        <option value="intership" {{ $employee->employment_status == 'intership' ? 'selected' : '' }}>Intership</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Tanggal Bergabung</label>
                                    <input type="date" name="join_date" value="{{ old('join_date', $employee->join_date) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                </div>
                            </div>
                        </div>

                        <!-- Struktur Upah -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Struktur Upah</h3>
                            <div class="space-y-6">
                                <!-- Upah Dasar & Potongan -->
                                <div>
                                    <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Kompensasi Dasar</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Gaji Pokok</label>
                                            <input type="number" name="basic_salary" value="{{ old('basic_salary', $employee->basic_salary) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Premi</label>
                                            <input type="number" name="premium" value="{{ old('premium', $employee->premium) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">JHT</label>
                                            <input type="number" name="jht" value="{{ old('jht', $employee->jht) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tunjangan -->
                                <div>
                                    <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Tunjangan</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Teknis</label>
                                            <input type="number" name="tunjangan_teknis" value="{{ old('tunjangan_teknis', $employee->tech_allowance) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Laptop</label>
                                            <input type="number" name="tunjangan_laptop" value="{{ old('tunjangan_laptop', $employee->laptop_allowance) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Tunjangan Keluarga</label>
                                            <input type="number" name="tunjangan_keluarga" value="{{ old('tunjangan_keluarga', $employee->family_allowance) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Reward -->
                                <div>
                                    <h4 class="text-[13px] font-bold text-gray-800 mb-3 border-b border-gray-100 pb-2">Reward</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Reward Disiplin</label>
                                            <input type="number" name="reward_disiplin" value="{{ old('reward_disiplin', $employee->discipline_reward) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                        <div>
                                            <label class="block text-[12.5px] font-bold text-gray-700 mb-1.5">Reward Solat</label>
                                            <input type="number" name="reward_solat" value="{{ old('reward_solat', $employee->prayer_reward) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-[13px]" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pengaturan Akun -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                            <h3 class="font-bold text-[15px] text-[#18398a] mb-5">Pengaturan Hak Akses</h3>
                            <div>
                                <label class="block text-[13px] font-bold text-gray-700 mb-1.5">Role Sistem</label>
                                <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-[14px]" required>
                                    <option value="employee" {{ $employee->user->hasRole('employee') ? 'selected' : '' }}>Employee (Standard Access)</option>
                                    <option value="admin" {{ $employee->user->hasRole('admin') ? 'selected' : '' }}>Admin (HR Access)</option>
                                    <option value="superadmin" {{ $employee->user->hasRole('superadmin') ? 'selected' : '' }}>Superadmin (Full Access)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="mt-8 flex justify-end space-x-3 border-t border-gray-100 pt-6">
                    <a href="{{ route('superadmin.employees.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 text-[14px] font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Batal
                    </a>
                    <button type="submit" form="editEmployeeForm" class="px-6 py-2.5 bg-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
