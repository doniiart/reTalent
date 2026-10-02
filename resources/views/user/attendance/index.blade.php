<x-app-layout>
    <!-- Leaflet CSS & JS for Admin Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="max-w-[1200px] mx-auto w-full">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Detail Kehadiran</h2>
                <p class="text-[14px] text-gray-500 mt-1.5 font-medium">{{ $selectedDate->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <form action="{{ route('user.attendance.index') }}" method="GET" class="relative" id="filterForm">
                    <select name="department_id" onchange="document.getElementById('filterForm').submit()" class="appearance-none px-3 py-1.5 sm:py-2 bg-white border border-gray-200 text-gray-700 text-xs sm:text-[13px] font-semibold rounded-lg hover:bg-gray-50 transition-colors shadow-sm pr-8 focus:ring-1 focus:ring-blue-500 outline-none cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </form>
                <a href="{{ route('user.attendance.export') }}" class="px-3 sm:px-3.5 py-1.5 sm:py-2 bg-[#18398a] border border-[#18398a] text-white text-xs sm:text-[13px] font-semibold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Rekap Absensi
                </a>
            </div>
        </div>

        <!-- 4 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
            <!-- Total Karyawan -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Total Karyawan</p>
                    <div class="p-2.5 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-[26px] font-extrabold text-gray-900 leading-tight">{{ $totalKaryawan }}</h3>
                </div>
            </div>

            <!-- Hadir Hari Ini -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Hadir {{ $isActualToday ? 'Hari Ini' : 'Tanggal ' . $selectedDate->format('d/m') }}</p>
                    <div class="text-[#15a34a] text-[12px] font-bold flex items-center">
                        <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        {{ $hadirPercentage }}%
                    </div>
                </div>
                <div>
                    <h3 class="text-[26px] font-extrabold text-[#15a34a] leading-tight">{{ $hadir }}</h3>
                </div>
            </div>

            <!-- Izin/Sakit -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Izin/Sakit</p>
                    <div class="p-2.5 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-[26px] font-extrabold text-[#22449c] leading-tight">{{ $izinSakit }}</h3>
                </div>
            </div>

            <!-- Alpa -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Alpa</p>
                    <div class="p-2.5 bg-[#fef2f2] text-[#dc2626] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-[26px] font-extrabold text-[#dc2626] leading-tight">{{ $alpa }}</h3>
                </div>
            </div>
        </div>

        <!-- Layout Kiri dan Kanan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
            <!-- Daftar Kehadiran Karyawan (Left Col) -->
            <div class="lg:col-span-2 bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden flex flex-col"
                 x-data="{
                    page: 1,
                    perPage: 10,
                    get rows() { return Array.from(document.querySelectorAll('.employee-row')); },
                    get total() { return this.rows.length; },
                    get totalPages() { return Math.max(1, Math.ceil(this.total / this.perPage)); },
                    get startItem() { return this.total === 0 ? 0 : (this.page - 1) * this.perPage + 1; },
                    get endItem() { return Math.min(this.page * this.perPage, this.total); },
                    updateRows() {
                        this.rows.forEach((row, index) => {
                            if (index >= (this.page - 1) * this.perPage && index < this.page * this.perPage) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    },
                    changePerPage() {
                        this.page = 1;
                        this.updateRows();
                    },
                    init() {
                        this.$nextTick(() => { this.updateRows(); });
                    },
                    prevPage() {
                        if (this.page > 1) {
                            this.page--;
                            this.updateRows();
                        }
                    },
                    nextPage() {
                        if (this.page < this.totalPages) {
                            this.page++;
                            this.updateRows();
                        }
                    }
                 }">
                <div class="p-5 px-6 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div>
                        <h3 class="font-extrabold text-gray-900 text-[17px]">Daftar Kehadiran Karyawan</h3>
                        <p class="text-[12px] text-gray-400 font-medium">Klik baris karyawan untuk memfilter Kalender Absen</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="border border-gray-200 p-2 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        </button>
                        <button class="border border-gray-200 p-2 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar touch-scroll flex-1">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-[#f4f7fb]/60 text-gray-500 text-[12px] font-bold border-b border-gray-200 tracking-wider">
                                <th class="py-4 px-6 uppercase">Karyawan</th>
                                <th class="py-4 px-4 uppercase">Jam<br/>Masuk</th>
                                <th class="py-4 px-4 uppercase">Jam<br/>Keluar</th>
                                <th class="py-4 px-4 uppercase">Status</th>
                                <th class="py-4 px-6 uppercase">Lokasi</th>
                                <th class="py-4 px-4 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($attendancesToday as $att)
                            <tr id="emp-row-{{ $att->user->id }}"
                                onclick="filterCalendarByUser({{ $att->user->id }}, '{{ addslashes($att->user->name) }}')"
                                class="employee-row hover:bg-blue-50/60 cursor-pointer transition-all duration-200 select-none"
                                title="Klik untuk memfilter kalender {{ $att->user->name }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[12px] mr-3 shrink-0">
                                            {{ substr($att->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-[14px] font-bold text-gray-900 flex items-center gap-1.5">
                                                {{ $att->user->name }}
                                                <svg class="w-3.5 h-3.5 text-gray-400 opacity-0 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                            <div class="text-[12px] text-gray-500">{{ $att->user->employee->department->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-[14px] font-medium text-gray-900">{{ $att->time_in ? \Carbon\Carbon::parse($att->time_in)->format('H:i') : '-' }}</td>
                                <td class="py-4 px-4 text-[14px] font-medium text-gray-900">{{ $att->time_out ? \Carbon\Carbon::parse($att->time_out)->format('H:i') : '-' }}</td>
                                <td class="py-4 px-4">
                                    @if($att->status == 'present')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-[#ebf8f0] text-[#15a34a]">Hadir</span>
                                    @elseif($att->status == 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-[#fef9c3] text-[#a16207]">Terlambat</span>
                                    @elseif($att->status == 'on_leave')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-blue-100 text-blue-700">Izin</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[12px] font-bold bg-[#fef2f2] text-[#dc2626]">Alpa</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-[13px] text-gray-500">{{ $att->location ?? 'Kantor Pusat' }}</td>
                                <td class="py-4 px-4">
                                    <button onclick="event.stopPropagation(); showAttendanceDetail({{ $att->lat_in ?? 'null' }}, {{ $att->long_in ?? 'null' }}, '{{ $att->photo_in ? Storage::url($att->photo_in) : '' }}', '{{ $att->user->name }}')" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[12px] font-bold hover:bg-blue-100 transition">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[13.5px] text-gray-500 font-medium">
                                    Belum ada data kehadiran hari ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div class="p-5 px-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white mt-auto">
                    <div class="flex items-center space-x-2.5 text-[12.5px] text-gray-500 font-medium">
                        <span>Tampilkan</span>
                        <select x-model.number="perPage"
                                @change="changePerPage()"
                                class="border border-gray-200 text-gray-800 text-[12.5px] font-bold rounded-lg py-1.5 px-3 focus:ring-1 focus:ring-[#18398a] focus:border-[#18398a] outline-none bg-white cursor-pointer shadow-sm">
                            <option value="10">10 data</option>
                            <option value="20">20 data</option>
                            <option value="30">30 data</option>
                            <option value="50">50 data</option>
                        </select>
                        <span>(Menampilkan <span class="font-bold text-gray-900" x-text="total > 0 ? (startItem === endItem ? startItem : startItem + ' - ' + endItem) : '0'"></span> dari <span class="font-bold text-[#18398a]">{{ number_format($totalKaryawan) }}</span> karyawan)</span>
                    </div>
                    <div class="flex space-x-2">
                        <button type="button"
                                @click="prevPage()"
                                :disabled="page <= 1"
                                :class="page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'"
                                class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-[13px] font-bold rounded-lg transition-colors shadow-sm">
                            Sebelumnya
                        </button>
                        <button type="button"
                                @click="nextPage()"
                                :disabled="page >= totalPages"
                                :class="page >= totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'"
                                class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-[13px] font-bold rounded-lg transition-colors shadow-sm">
                            Selanjutnya
                        </button>
                    </div>
                </div>
            </div>

            <!-- Side Widgets (Right Col) -->
            <div class="lg:col-span-1 flex flex-col space-y-6">
                <!-- Kalender Absen -->
                <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6 flex flex-col">
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-extrabold text-gray-900 text-[16px]">Kalender Absen</h3>
                                <p class="text-[13px] font-bold text-[#4a6bdf] mt-0.5" id="calendarMonthYearDisplay">
                                    {{ $selectedDate->locale('id')->translatedFormat('F Y') }}
                                </p>
                            </div>
                            <div class="flex space-x-1">
                                <a href="{{ route('user.attendance.index', ['date' => $selectedDate->copy()->subMonth()->format('Y-m-d'), 'department_id' => request('department_id')]) }}" class="text-gray-400 hover:text-gray-700 p-1 hover:bg-gray-100 rounded-md transition" title="Bulan Sebelumnya">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </a>
                                <a href="{{ route('user.attendance.index', ['date' => $selectedDate->copy()->addMonth()->format('Y-m-d'), 'department_id' => request('department_id')]) }}" class="text-gray-400 hover:text-gray-700 p-1 hover:bg-gray-100 rounded-md transition" title="Bulan Selanjutnya">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        <div id="selectedEmployeeBanner" class="hidden mt-2.5 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-3 py-1.5 transition-all">
                            <div class="flex items-center space-x-1.5 overflow-hidden">
                                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                                <span class="text-[12px] font-bold text-blue-900 truncate" id="selectedEmployeeName">Nama Karyawan</span>
                            </div>
                            <button onclick="resetCalendarFilter(event)" class="text-[11px] font-bold text-blue-600 hover:text-blue-800 underline shrink-0 ml-2">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1 text-center mb-2">
                        <div class="text-[11px] font-bold text-gray-400">Min</div>
                        <div class="text-[11px] font-bold text-gray-400">Sen</div>
                        <div class="text-[11px] font-bold text-gray-400">Sel</div>
                        <div class="text-[11px] font-bold text-gray-400">Rab</div>
                        <div class="text-[11px] font-bold text-gray-400">Kam</div>
                        <div class="text-[11px] font-bold text-gray-400">Jum</div>
                        <div class="text-[11px] font-bold text-gray-400">Sab</div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1 text-center text-[13px] font-semibold text-gray-700 mb-6">
                        @php
                            $firstDayOfMonth = $selectedDate->copy()->startOfMonth();
                            $daysInMonth = $firstDayOfMonth->daysInMonth;
                            $dayOfWeek = $firstDayOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                            
                            $attendancesByDay = [];
                            $attGrouped = $adminMonthAttendances->groupBy(function($item) {
                                return \Carbon\Carbon::parse($item->date)->day;
                            });
                        @endphp
                        
                        <!-- Empty slots before 1st day -->
                        @for($i = 0; $i < $dayOfWeek; $i++)
                            <div class="py-1.5 text-gray-300"></div>
                        @endfor
                        
                        <!-- Days -->
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $loopDate = $firstDayOfMonth->copy()->addDays($day - 1);
                                $isSelected = $day == $selectedDate->day;
                                $dayAtts = $attGrouped[$day] ?? collect();
                                $hasHadir = $dayAtts->whereIn('status', ['present', 'late'])->count() > 0;
                                $hasIzin = $dayAtts->whereIn('status', ['on_leave'])->count() > 0;
                                $hasAlpa = $dayAtts->where('status', 'absent')->count() > 0;
                            @endphp
                            <a href="{{ route('user.attendance.index', ['date' => $loopDate->format('Y-m-d'), 'department_id' => request('department_id')]) }}" 
                               id="cal-day-{{ $day }}"
                               data-day="{{ $day }}"
                               data-is-selected="{{ $isSelected ? '1' : '0' }}"
                               data-all-hadir="{{ $hasHadir ? '1' : '0' }}"
                               data-all-izin="{{ $hasIzin ? '1' : '0' }}"
                               data-all-alpa="{{ $hasAlpa ? '1' : '0' }}"
                               class="calendar-day-cell py-1.5 relative rounded-sm transition hover:bg-gray-50 
                                      {{ $isSelected ? 'bg-[#18398a] text-white shadow-sm font-bold hover:bg-[#122b6b]' : '' }}
                                      {{ !$isSelected && $hasHadir && !$hasIzin && !$hasAlpa ? 'bg-[#ebf8f0] text-[#15a34a] border border-[#15a34a]/20' : '' }}
                                      {{ !$isSelected && $hasIzin && !$hasAlpa ? 'bg-[#fef9c3] text-[#a16207] border border-[#a16207]/20' : '' }}
                                      {{ !$isSelected && $hasAlpa ? 'bg-[#fef2f2] text-[#dc2626] border border-[#dc2626]/20' : '' }}">
                                {{ $day }}
                                
                                @if($isSelected && ($hasHadir || $hasIzin || $hasAlpa))
                                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-white rounded-full"></div>
                                @endif
                            </a>
                        @endfor
                        
                        <!-- Empty slots after last day -->
                        @php
                            $remainingSlots = (7 - (($dayOfWeek + $daysInMonth) % 7)) % 7;
                        @endphp
                        @for($i = 0; $i < $remainingSlots; $i++)
                            <div class="py-1.5 text-gray-300"></div>
                        @endfor
                    </div>

                    <div class="flex items-center space-x-4 text-[11px] font-bold text-gray-700 mt-auto pt-2 border-t border-gray-100">
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-[#15a34a] mr-1.5"></div> Hadir
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-[#eab308] mr-1.5"></div> Izin
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 rounded-full bg-[#dc2626] mr-1.5"></div> Alpa
                        </div>
                    </div>
                </div>

                <!-- Catatan Administrasi -->
                <div class="bg-[#22449c] rounded-[14px] p-6 text-white shadow-sm flex flex-col items-start relative overflow-hidden">
                    <div class="flex items-center font-bold text-[14px] mb-4 relative z-10">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Catatan Administrasi
                    </div>
                    <p class="text-[13px] text-blue-100 font-medium leading-relaxed mb-6 relative z-10">
                        @php
                            $userPendingLeavesCount = \App\Models\Leave::where('user_id', auth()->id())->where('status', 'pending')->count();
                        @endphp
                        @if($userPendingLeavesCount > 0)
                            Anda memiliki <span class="font-extrabold text-white text-[15px] bg-white/20 px-2 py-0.5 rounded">{{ $userPendingLeavesCount }}</span> pengajuan cuti yang sedang diproses.
                        @else
                            Kelola pengajuan cuti dan lihat sisa jatah cuti Anda di sini.
                        @endif
                    </p>
                    <a href="{{ route('user.leave.index') }}" class="w-full py-2.5 bg-white text-[#22449c] font-bold text-[13px] rounded-lg shadow-sm hover:bg-blue-50 transition-colors relative z-10 text-center block">
                        Buka Panel Cuti
                    </a>
                    <!-- decorative pattern -->
                    <div class="absolute -right-4 -bottom-4 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zm0 7.5l-10-5v10l10 5 10-5v-10l-10 5z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Detail Modal -->
    <div id="adminDetailModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeAdminModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-extrabold text-gray-900 mb-4" id="adminModalTitle">Detail Absensi</h3>
                            
                            <div class="space-y-4">
                                <!-- Photo -->
                                <div>
                                    <p class="text-[12px] font-bold text-gray-500 mb-2">Foto Karyawan</p>
                                    <div class="w-full bg-gray-100 rounded-lg overflow-hidden relative aspect-video flex items-center justify-center border border-gray-200">
                                        <img id="adminModalPhoto" class="w-full h-full object-cover hidden">
                                        <span id="adminModalNoPhoto" class="text-[12px] font-bold text-gray-400">Tidak ada foto (Absensi Kantor)</span>
                                    </div>
                                </div>

                                <!-- Map -->
                                <div>
                                    <p class="text-[12px] font-bold text-gray-500 mb-2">Lokasi GPS</p>
                                    <div id="adminModalMap" class="w-full h-40 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden relative z-0"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" onclick="closeAdminModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let adminMapInstance = null;
        let adminMarker = null;

        function showAttendanceDetail(lat, lng, photoUrl, employeeName) {
            document.getElementById('adminDetailModal').classList.remove('hidden');
            document.getElementById('adminModalTitle').innerText = 'Detail Absensi - ' + employeeName;
            
            // Handle Photo
            const imgEl = document.getElementById('adminModalPhoto');
            const noPhotoEl = document.getElementById('adminModalNoPhoto');
            if (photoUrl) {
                imgEl.src = photoUrl;
                imgEl.classList.remove('hidden');
                noPhotoEl.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                imgEl.src = '';
                noPhotoEl.classList.remove('hidden');
            }

            // Handle Map
            setTimeout(() => {
                if (lat !== null && lng !== null) {
                    if (!adminMapInstance) {
                        adminMapInstance = L.map('adminModalMap').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(adminMapInstance);
                        adminMarker = L.marker([lat, lng]).addTo(adminMapInstance);
                    } else {
                        adminMapInstance.setView([lat, lng], 15);
                        adminMarker.setLatLng([lat, lng]);
                        adminMapInstance.invalidateSize();
                    }
                } else {
                    if (adminMapInstance) {
                        adminMapInstance.remove();
                        adminMapInstance = null;
                    }
                    document.getElementById('adminModalMap').innerHTML = '<div class="w-full h-full flex items-center justify-center text-[12px] font-bold text-gray-400">Lokasi GPS tidak direkam</div>';
                }
            }, 300);
        }

        function closeAdminModal() {
            document.getElementById('adminDetailModal').classList.add('hidden');
        }

        // --- Interactive Employee Calendar Filtering ---
        let currentSelectedUserId = null;

        const monthAttendances = @json($adminMonthAttendances->map(function($item) {
            return [
                'user_id' => (int) $item->user_id,
                'day' => (int) \Carbon\Carbon::parse($item->date)->day,
                'status' => strtolower($item->status)
            ];
        }));

        function filterCalendarByUser(userId, userName) {
            if (currentSelectedUserId === userId) {
                resetCalendarFilter();
                return;
            }

            currentSelectedUserId = userId;

            // Highlight clicked row
            document.querySelectorAll('.employee-row').forEach(row => {
                row.classList.remove('bg-blue-50/90', 'ring-2', 'ring-blue-500/60', 'font-semibold');
            });
            const targetRow = document.getElementById('emp-row-' + userId);
            if (targetRow) {
                targetRow.classList.add('bg-blue-50/90', 'ring-2', 'ring-blue-500/60', 'font-semibold');
            }

            // Show Employee Name Banner
            const banner = document.getElementById('selectedEmployeeBanner');
            const nameEl = document.getElementById('selectedEmployeeName');
            if (banner && nameEl) {
                nameEl.innerText = userName;
                banner.classList.remove('hidden');
            }

            // Update Calendar day cells for selected employee
            document.querySelectorAll('.calendar-day-cell').forEach(cell => {
                const day = parseInt(cell.getAttribute('data-day'));
                const isSelectedDate = cell.getAttribute('data-is-selected') === '1';

                const userAtt = monthAttendances.find(att => att.user_id === userId && att.day === day);
                const baseClasses = "calendar-day-cell py-1.5 relative rounded-sm transition hover:bg-gray-50 ";

                if (isSelectedDate) {
                    cell.className = baseClasses + "bg-[#18398a] text-white shadow-sm font-bold hover:bg-[#122b6b]";
                } else if (userAtt) {
                    if (userAtt.status === 'present') {
                        cell.className = baseClasses + "bg-[#ebf8f0] text-[#15a34a] border border-[#15a34a]/40 font-bold";
                    } else if (userAtt.status === 'late') {
                        cell.className = baseClasses + "bg-[#fef9c3] text-[#a16207] border border-[#a16207]/40 font-bold";
                    } else if (userAtt.status === 'on_leave') {
                        cell.className = baseClasses + "bg-blue-100 text-blue-700 border border-blue-300 font-bold";
                    } else if (userAtt.status === 'absent') {
                        cell.className = baseClasses + "bg-[#fef2f2] text-[#dc2626] border border-[#dc2626]/40 font-bold";
                    } else {
                        cell.className = baseClasses + "text-gray-700";
                    }
                } else {
                    cell.className = baseClasses + "text-gray-400 opacity-60";
                }
            });
        }

        function resetCalendarFilter(e) {
            if (e) e.stopPropagation();
            currentSelectedUserId = null;

            document.querySelectorAll('.employee-row').forEach(row => {
                row.classList.remove('bg-blue-50/90', 'ring-2', 'ring-blue-500/60', 'font-semibold');
            });

            const banner = document.getElementById('selectedEmployeeBanner');
            if (banner) banner.classList.add('hidden');

            document.querySelectorAll('.calendar-day-cell').forEach(cell => {
                const isSelectedDate = cell.getAttribute('data-is-selected') === '1';
                const hasHadir = cell.getAttribute('data-all-hadir') === '1';
                const hasIzin = cell.getAttribute('data-all-izin') === '1';
                const hasAlpa = cell.getAttribute('data-all-alpa') === '1';

                const baseClasses = "calendar-day-cell py-1.5 relative rounded-sm transition hover:bg-gray-50 ";

                if (isSelectedDate) {
                    cell.className = baseClasses + "bg-[#18398a] text-white shadow-sm font-bold hover:bg-[#122b6b]";
                } else if (hasHadir && !hasIzin && !hasAlpa) {
                    cell.className = baseClasses + "bg-[#ebf8f0] text-[#15a34a] border border-[#15a34a]/20";
                } else if (hasIzin && !hasAlpa) {
                    cell.className = baseClasses + "bg-[#fef9c3] text-[#a16207] border border-[#a16207]/20";
                } else if (hasAlpa) {
                    cell.className = baseClasses + "bg-[#fef2f2] text-[#dc2626] border border-[#dc2626]/20";
                } else {
                    cell.className = baseClasses + "text-gray-700";
                }
            });
        }
    </script>
</x-app-layout>
