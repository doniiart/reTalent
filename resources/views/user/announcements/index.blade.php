<x-app-layout>
    <div class="max-w-[1200px] mx-auto w-full pb-12">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h2 class="text-3xl font-extrabold text-[#111827] tracking-tight">Pengumuman</h2>
                <p class="text-[14px] text-gray-500 mt-1 font-medium">Informasi dan pengumuman terbaru perusahaan.</p>
            </div>
        </div>

        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <form method="GET" action="{{ route('user.announcements.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div class="w-full sm:w-auto flex-1 max-w-xs">
                        <label for="department_id" class="block text-[12px] font-bold text-gray-700 mb-1">Filter Departemen</label>
                        <select name="department_id" id="department_id" class="filter-select w-full">
                            <option value="">Semua Pengumuman Anda</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="filter-btn bg-white border-gray-300 text-gray-700 hover:bg-gray-50">
                        Filter
                    </button>
                    @if(request()->has('department_id'))
                        <a href="{{ route('user.announcements.index') }}" class="px-5 py-2.5 bg-red-50 text-red-600 text-[13px] font-bold rounded-lg hover:bg-red-100 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto custom-scrollbar touch-scroll">
                <table class="w-full text-left border-collapse min-w-[650px]">
                    <thead>
                        <tr class="text-gray-500 text-[12px] font-bold border-b border-gray-100 uppercase tracking-wider bg-gray-50/30">
                            <th class="py-4 px-7">JUDUL & KONTEN</th>
                            <th class="py-4 px-4">TARGET DEPARTEMEN</th>
                            <th class="py-4 px-4">TANGGAL</th>
                            <th class="py-4 px-7 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($announcements as $announcement)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-5 px-7">
                                    <div class="flex items-start">
                                        @if($announcement->images && count($announcement->images) > 0)
                                            <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex-shrink-0 mr-4 overflow-hidden">
                                                <img src="{{ Storage::url($announcement->images[0]) }}" alt="Thumbnail" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-[#eef2fc] text-[#4a6bdf] flex-shrink-0 mr-4 flex items-center justify-center border border-[#d1def8]">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900 text-[14px] mb-1">{{ $announcement->title }}</div>
                                            <div class="text-[13px] text-gray-500 max-w-md line-clamp-2 leading-relaxed">
                                                {{ strip_tags($announcement->content) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    @if($announcement->department)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                            {{ $announcement->department->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                            Umum (Semua)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-5 px-4">
                                    <div class="text-[13px] font-bold text-gray-700">{{ $announcement->created_at->translatedFormat('d M Y') }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $announcement->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-5 px-7">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('user.announcements.show', $announcement) }}" class="p-1.5 text-[#4a6bdf] hover:bg-[#eef2fc] rounded-md transition-colors" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <h3 class="text-[14px] font-bold text-gray-900 mb-1">Belum Ada Pengumuman</h3>
                                    <p class="text-[13px] text-gray-500 max-w-sm mx-auto">Belum ada pengumuman untuk Anda atau departemen Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($announcements->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
