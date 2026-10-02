<x-app-layout>
    <div class="max-w-[1200px] mx-auto w-full">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h2 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Manajemen Penggajian</h2>
                <p class="text-[14px] text-gray-500 mt-1.5 font-medium">Periode Pembayaran: Oktober 2023</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="px-5 py-2.5 bg-white border border-[#4a6bdf] text-[#4a6bdf] text-[14px] font-bold rounded-lg hover:bg-blue-50 transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Ekspor Laporan
                </button>
                <button class="px-5 py-2.5 bg-[#18398a] border border-[#18398a] text-white text-[14px] font-bold rounded-lg hover:bg-[#22449c] transition-colors flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Proses Gaji
                </button>
            </div>
        </div>

        <!-- 4 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
            <!-- Total Payroll -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-2.5 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="bg-[#ebf8f0] text-[#15a34a] px-2 py-1 rounded text-[10px] font-bold">
                        +12.5%
                    </div>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Total Gaji</p>
                    <h3 class="text-[18px] sm:text-[19px] xl:text-[21px] font-extrabold text-gray-900 leading-none whitespace-nowrap truncate tracking-tight">
                        <span class="text-[14px] font-extrabold text-gray-700 mr-0.5">Rp</span>{{ number_format($totalPayroll ?? 0, 0, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- Total Karyawan -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-2.5 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Total Karyawan</p>
                    <h3 class="text-[24px] font-extrabold text-gray-900 leading-tight">0 Orang</h3>
                </div>
            </div>

            <!-- Belum Dibayar -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-2.5 bg-[#f3f4f6] text-gray-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div class="bg-[#fef2f2] text-[#dc2626] px-2 py-1 rounded text-[10px] font-bold">
                        Urgent
                    </div>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Belum Dibayar</p>
                    <h3 class="text-[24px] font-extrabold text-gray-900 leading-tight">0 Karyawan</h3>
                </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200 p-6 flex flex-col justify-between">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-2.5 bg-[#eef2fc] text-[#4a6bdf] rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>
                <div>
                    <p class="text-[13px] font-bold text-gray-500 mb-1">Status Pembayaran</p>
                    <h3 class="text-[22px] font-extrabold text-gray-400 leading-tight">Belum Ada</h3>
                </div>
            </div>
        </div>

        <!-- Table Rincian Gaji -->
        <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 overflow-hidden mb-7"
             x-data="{
                page: 1,
                perPage: 10,
                get rows() { return Array.from(document.querySelectorAll('.payroll-row')); },
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
                <h3 class="font-extrabold text-gray-900 text-[17px]">Daftar Rincian Gaji</h3>
                <div class="flex space-x-2">
                    <select class="border border-gray-200 text-gray-700 text-[13px] font-bold rounded-lg shadow-sm py-2 px-3 focus:ring-0 outline-none">
                        <option>Semua Departemen</option>
                    </select>
                    <button class="border border-gray-200 p-2 rounded-lg text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar touch-scroll">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-[#f4f7fb]/60 text-gray-600 text-[13px] font-bold border-b border-gray-200">
                            <th class="py-4 px-6">Nama Karyawan</th>
                            <th class="py-4 px-4">Gaji Pokok</th>
                            <th class="py-4 px-4">Tunjangan</th>
                            <th class="py-4 px-4">Potongan</th>
                            <th class="py-4 px-4">Total Gaji</th>
                            <th class="py-4 px-4">Status</th>
                            <th class="py-4 px-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[13.5px] text-gray-500 font-medium">
                                Belum ada data rincian gaji.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="p-5 px-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
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
                    <span>(Menampilkan <span class="font-bold text-gray-900" x-text="total > 0 ? (startItem === endItem ? startItem : startItem + ' - ' + endItem) : '0'"></span> dari <span class="font-bold text-[#18398a]" x-text="total"></span> Karyawan)</span>
                </div>
                <div class="flex space-x-2">
                    <button type="button"
                            @click="prevPage()"
                            :disabled="page <= 1"
                            :class="page <= 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 cursor-pointer'"
                            class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-[13.5px] font-bold rounded-lg transition-colors shadow-sm">
                        Sebelumnya
                    </button>
                    <button type="button"
                            @click="nextPage()"
                            :disabled="page >= totalPages"
                            :class="page >= totalPages ? 'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400 border border-gray-200' : 'bg-[#18398a] border border-[#18398a] text-white hover:bg-[#22449c] cursor-pointer'"
                            class="px-4 py-2 text-[13.5px] font-bold rounded-lg transition-colors shadow-sm">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
            <!-- Distribusi Gaji -->
            <div class="lg:col-span-2 bg-white rounded-[14px] shadow-sm border border-gray-200 p-7">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-extrabold text-gray-900 text-[17px]">Distribusi Gaji per Departemen</h3>
                    <a href="#" class="text-[13px] font-bold text-[#18398a] hover:underline">Detail Lengkap</a>
                </div>
                <div class="space-y-6">
                    <div class="py-8 text-center text-[13px] text-gray-500 font-medium border-t border-gray-100">
                        Belum ada distribusi gaji.
                    </div>
                </div>
            </div>

            <!-- Side Widgets -->
            <div class="lg:col-span-1 flex flex-col space-y-6">


                <!-- Info Penting -->
                <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center text-[#dc2626] font-bold text-[14px] mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Info Penting
                    </div>
                    <ul class="text-[13px] text-gray-600 font-medium space-y-3 list-disc pl-5">
                        <li>Batas pelaporan gaji adalah tanggal 25 setiap bulannya.</li>
                        <li>4 karyawan baru belum memiliki nomor rekening aktif.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
