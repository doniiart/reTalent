<x-app-layout>
    <div class="max-w-[800px] mx-auto w-full pb-12">
        <div class="mb-8 flex items-center">
            <a href="{{ route('superadmin.announcements.index') }}" class="mr-4 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-extrabold text-[#111827] tracking-tight">Edit Pengumuman</h2>
                <p class="text-[13.5px] text-gray-500 mt-1 font-medium">Ubah informasi pengumuman yang sudah ada.</p>
            </div>
        </div>

        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('superadmin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-[13px] font-bold text-gray-700 mb-1.5">Judul Pengumuman <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required class="w-full border-gray-300 text-[14px] rounded-lg shadow-sm focus:border-[#4a6bdf] focus:ring focus:ring-[#4a6bdf] focus:ring-opacity-20 transition-shadow" placeholder="Masukkan judul pengumuman...">
                        @error('title') <p class="mt-1 text-[12px] text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-[13px] font-bold text-gray-700 mb-1.5">Isi Pengumuman <span class="text-red-500">*</span></label>
                        <textarea name="content" id="content" rows="6" required class="w-full border-gray-300 text-[14px] rounded-lg shadow-sm focus:border-[#4a6bdf] focus:ring focus:ring-[#4a6bdf] focus:ring-opacity-20 transition-shadow" placeholder="Tuliskan isi detail pengumuman di sini...">{{ old('content', $announcement->content) }}</textarea>
                        @error('content') <p class="mt-1 text-[12px] text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="department_id" class="block text-[13px] font-bold text-gray-700 mb-1.5">Target Departemen</label>
                            <select name="department_id" id="department_id" class="w-full border-gray-300 text-[14px] rounded-lg shadow-sm focus:border-[#4a6bdf] focus:ring focus:ring-[#4a6bdf] focus:ring-opacity-20 transition-shadow">
                                <option value="">Semua Departemen (Umum)</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $announcement->department_id) == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-[11.5px] text-gray-500">Biarkan kosong agar pengumuman bisa dilihat oleh semua karyawan.</p>
                            @error('department_id') <p class="mt-1 text-[12px] text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            @if($announcement->images)
                                <div class="mb-3">
                                    <span class="text-[12px] text-gray-500 block mb-2">Gambar saat ini:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($announcement->images as $path)
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden">
                                                <img src="{{ Storage::url($path) }}" alt="Preview" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <label for="images" class="block text-[13px] font-bold text-gray-700 mb-1.5">Foto/Gambar Lampiran Baru (Opsional, Maksimal 10 Gambar)</label>
                            <input type="file" name="images[]" id="images" accept="image/*" multiple max="10" class="w-full text-[13px] text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-[13px] file:font-bold file:bg-[#eef2fc] file:text-[#4a6bdf] hover:file:bg-[#d1def8] transition-colors border border-gray-200 rounded-lg">
                            <p class="mt-1 text-[11.5px] text-gray-500">Pilih gambar baru jika ingin mengganti gambar yang ada.</p>
                            @error('images') <p class="mt-1 text-[12px] text-red-600">{{ $message }}</p> @enderror
                            @error('images.*') <p class="mt-1 text-[12px] text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-[#0c2f82] shadow-sm focus:border-[#4a6bdf] focus:ring focus:ring-[#4a6bdf] focus:ring-opacity-20" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-[13.5px] font-bold text-gray-700">Aktif (Tampilkan Pengumuman)</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-[#0c2f82] text-white text-[13.5px] font-bold rounded-lg hover:bg-[#0a235c] transition-colors shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
