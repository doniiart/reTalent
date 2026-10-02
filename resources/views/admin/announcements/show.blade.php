<x-app-layout>
    <div class="max-w-[800px] mx-auto w-full pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.announcements.index') }}" class="mr-4 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-extrabold text-[#111827] tracking-tight">Detail Pengumuman</h2>
                </div>
            </div>
            <a href="{{ route('admin.announcements.edit', $announcement) }}" class="px-4 py-2 bg-yellow-50 text-yellow-700 border border-yellow-200 text-[13px] font-bold rounded-lg hover:bg-yellow-100 transition-colors flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit
            </a>
        </div>

        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden">
            @if($announcement->images && count($announcement->images) > 0)
                <div x-data="{ activeIndex: 0, images: {{ json_encode(array_map(fn($path) => Storage::url($path), $announcement->images)) }} }" class="relative w-full h-64 sm:h-96 bg-gray-100 border-b border-gray-200">
                    <template x-for="(image, index) in images" :key="index">
                        <img x-show="activeIndex === index" :src="image" alt="Announcement Image" class="w-full h-full object-contain" x-transition.opacity.duration.300ms>
                    </template>
                    
                    <template x-if="images.length > 1">
                        <div class="absolute inset-0 flex items-center justify-between p-4">
                            <button @click="activeIndex = activeIndex === 0 ? images.length - 1 : activeIndex - 1" class="p-2 rounded-full bg-white/70 hover:bg-white text-gray-800 shadow transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="activeIndex = activeIndex === images.length - 1 ? 0 : activeIndex + 1" class="p-2 rounded-full bg-white/70 hover:bg-white text-gray-800 shadow transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </template>
                    
                    <template x-if="images.length > 1">
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                            <template x-for="(image, index) in images" :key="index">
                                <button @click="activeIndex = index" :class="{'bg-[#4a6bdf]': activeIndex === index, 'bg-gray-300': activeIndex !== index}" class="w-2.5 h-2.5 rounded-full shadow-sm transition-colors"></button>
                            </template>
                        </div>
                    </template>
                </div>
            @endif
            
            <div class="p-8">
                <div class="flex items-center space-x-3 mb-6">
                    @if($announcement->department)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-100">
                            {{ $announcement->department->name }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            Umum (Semua)
                        </span>
                    @endif
                    <span class="text-[13px] text-gray-500 font-medium">
                        {{ $announcement->created_at->translatedFormat('d M Y - H:i') }} WIB
                    </span>
                    <span class="text-gray-300">•</span>
                    <span class="text-[13px] text-gray-500 font-medium">Oleh: {{ $announcement->user->name ?? 'Admin' }}</span>
                </div>

                <h1 class="text-2xl font-extrabold text-gray-900 mb-6">{{ $announcement->title }}</h1>
                
                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed text-[14.5px]">
                    {!! nl2br(e($announcement->content)) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
