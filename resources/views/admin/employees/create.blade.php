<x-app-layout>
    <x-slot name="header">
        Add New Employee
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
        <!-- Info Password Default -->
        <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 p-4 rounded-lg flex items-center text-sm font-medium">
            <svg class="w-5 h-5 mr-3 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Password default untuk akun pengguna baru ini adalah: <strong class="font-extrabold text-[#0c2f82]">12345</strong></span>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Informasi Pribadi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIP</label>
                    <input type="text" name="nip" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor HP</label>
                    <input type="text" name="phone" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Profil</label>
                    <input type="file" name="photo" class="w-full border-gray-300 rounded-lg shadow-sm p-2 text-sm">
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Detail Pekerjaan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Departemen</label>
                    <select name="department_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan</label>
                    <select name="position_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis Pekerjaan</label>
                    <select name="job_type_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="">Pilih Jenis Pekerjaan</option>
                        @foreach($jobTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Bergabung</label>
                    <input type="date" name="join_date" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status Karyawan</label>
                    <select name="employment_status" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="tetap">Tetap</option>
                        <option value="kontrak">Kontrak</option>
                        <option value="intership">Intership</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Kerja</label>
                    <select name="work_location" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                        <option value="Kantor">Kantor</option>
                        <option value="Remote">Remote</option>
                    </select>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Struktur Upah</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gaji Pokok</label>
                    <input type="number" name="basic_salary" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Premi</label>
                    <input type="number" name="premium" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">JHT</label>
                    <input type="number" name="jht" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-3 font-bold text-gray-800 mt-2">Tunjangan & Reward</div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">T. Teknis</label>
                    <input type="number" name="tech_allowance" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">T. Laptop</label>
                    <input type="number" name="laptop_allowance" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">T. Keluarga</label>
                    <input type="number" name="family_allowance" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Reward Disiplin</label>
                    <input type="number" name="discipline_reward" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Reward Solat</label>
                    <input type="number" name="prayer_reward" value="0" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500">
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Pengaturan Akun</h3>
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Peran / Role</label>
                <select name="role" class="w-full md:w-1/3 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500" required>
                    <option value="user">Karyawan (User)</option>
                    <option value="admin">Admin</option>
                    <option value="superadmin">SuperAdmin</option>
                </select>
            </div>

            <div class="flex justify-end border-t pt-6">
                <a href="{{ route('admin.employees.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold mr-4 hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 shadow-md">Simpan Data Karyawan</button>
            </div>
        </form>
    </div>
</x-app-layout>
