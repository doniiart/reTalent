<x-app-layout>
<div class="max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight">Penggajian</h1>
    </div>

    @if($latestPayroll)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Gaji Terakhir Card -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden relative">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-[12px] font-bold text-gray-500 uppercase tracking-widest mb-1">GAJI TERAKHIR ({{ strtoupper($latestPayroll->month) }} {{ $latestPayroll->year }})</h2>
                        <div class="flex items-baseline gap-3">
                            <span class="text-[36px] font-extrabold text-[#18398a] leading-none">Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('admin.payroll.download', $latestPayroll) }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-[#18398a] hover:bg-[#122b6b] text-white text-[14px] font-bold rounded-lg transition-colors shadow-sm whitespace-nowrap">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh Slip Gaji
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <span class="block text-[12px] font-medium text-gray-500 mb-1">Gaji Pokok</span>
                        <span class="text-[18px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-[12px] font-medium text-gray-500 mb-1">Tunjangan & Reward</span>
                        <span class="text-[18px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->total_allowance + $latestPayroll->total_reward + $latestPayroll->premium, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-[12px] font-medium text-gray-500 mb-1">Potongan</span>
                        <span class="text-[18px] font-bold text-red-600">Rp {{ number_format($latestPayroll->jht_deduction, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Watermark Icon Background -->
            <div class="absolute -bottom-10 -right-4 opacity-[0.03] pointer-events-none">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"></path></svg>
            </div>
        </div>

        <!-- Keamanan Data Card -->
        <div class="bg-[#18398a] rounded-xl shadow-sm p-6 text-white flex flex-col justify-center relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center mr-3 backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-[15px] font-bold">Keamanan Data</h3>
                </div>
                <p class="text-blue-100/90 text-[13px] leading-relaxed mb-5">
                    Informasi penggajian bersifat rahasia. Jangan membagikan detail slip gaji atau login Anda kepada siapapun.
                </p>
                <a href="#" class="text-[12px] font-semibold text-white hover:text-blue-200 inline-flex items-center">
                    Lihat Kebijakan Privasi
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
            <!-- Decorative circle -->
            <div class="absolute -top-16 -right-16 w-48 h-48 bg-white opacity-[0.04] rounded-full blur-2xl"></div>
        </div>
    </div>

    <!-- Charts & Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Riwayat Penghasilan Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 flex flex-col h-[350px]">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-[16px] font-bold text-gray-900">Riwayat Penghasilan</h3>
                <span class="text-[12px] font-medium text-gray-500 bg-gray-50 px-3 py-1 rounded-md border border-gray-100">{{ $chartPayrolls->count() }} Bulan Terakhir</span>
            </div>
            
            <div class="flex-1 flex items-end justify-around pb-2 relative">
                <!-- Helper lines -->
                <div class="absolute inset-0 flex flex-col justify-between pb-8 z-0 opacity-10 pointer-events-none">
                    <div class="border-t border-gray-400 w-full"></div>
                    <div class="border-t border-gray-400 w-full"></div>
                    <div class="border-t border-gray-400 w-full"></div>
                    <div class="border-t border-gray-400 w-full"></div>
                </div>
                
                @php
                    $maxSalary = $chartPayrolls->max('net_salary') > 0 ? $chartPayrolls->max('net_salary') : 1;
                @endphp
                
                @foreach($chartPayrolls as $p)
                    @php
                        $percentage = ($p->net_salary / $maxSalary) * 100;
                        // Minimum height for visibility
                        $percentage = max($percentage, 10);
                        // Abbreviate month name
                        $monthAbbr = substr($p->month, 0, 3);
                    @endphp
                    <div class="flex flex-col items-center z-10 group relative w-12 sm:w-16">
                        <!-- Tooltip -->
                        <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[11px] font-bold py-1 px-2 rounded whitespace-nowrap z-20 pointer-events-none">
                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                        </div>
                        <div class="w-full bg-blue-100 rounded-t-sm relative transition-all duration-300 group-hover:bg-blue-200" style="height: {{ $percentage }}%;">
                            <div class="absolute bottom-0 w-full bg-[#18398a] rounded-t-sm transition-all duration-300 group-hover:bg-blue-700" style="height: 100%;"></div>
                        </div>
                        <span class="text-[11px] font-bold text-gray-500 mt-3 {{ $loop->last ? 'text-[#18398a]' : '' }}">{{ strtoupper($monthAbbr) }}</span>
                    </div>
                @endforeach
                
                @if($chartPayrolls->count() == 0)
                    <div class="absolute inset-0 flex items-center justify-center text-gray-400 text-sm font-medium z-10">
                        Belum ada riwayat gaji.
                    </div>
                @endif
            </div>
        </div>

        <!-- Rincian Pendapatan & Potongan -->
        <div class="space-y-6">
            <!-- Pendapatan -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center mb-5">
                    <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-[14px] font-bold text-gray-900">Rincian Pendapatan</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-[13px] text-gray-500">Gaji Pokok</span>
                        <span class="text-[13px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-[13px] text-gray-500">Tunjangan & Reward</span>
                        <span class="text-[13px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->total_allowance + $latestPayroll->total_reward, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-gray-500">Bonus Khusus (Premium)</span>
                        <span class="text-[13px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->premium, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Potongan -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center mb-5">
                    <div class="w-6 h-6 rounded-full bg-red-50 text-red-500 flex items-center justify-center mr-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </div>
                    <h3 class="text-[14px] font-bold text-gray-900">Rincian Potongan</h3>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-[13px] text-gray-500">Potongan JHT (2%)</span>
                        <span class="text-[13px] font-bold text-gray-900">Rp {{ number_format($latestPayroll->jht_deduction, 0, ',', '.') }}</span>
                    </div>
                    <!-- Assuming other deductions are 0 as per logic, only JHT exists currently -->
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Slip Gaji Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-[16px] font-bold text-gray-900">Daftar Slip Gaji</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-[11px] font-bold text-gray-500 uppercase tracking-wider border-y border-gray-200">
                        <th class="px-6 py-4">Bulan & Tahun</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 hidden md:table-cell">Metode Transfer</th>
                        <th class="px-6 py-4">Total Bersih</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-[13px] divide-y divide-gray-100">
                    @foreach($userPayrolls as $payroll)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ ucfirst($payroll->month) }} {{ $payroll->year }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($payroll->status == 'paid')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-green-100 text-green-700">
                                    Dibayarkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-700">
                                    Diproses
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 hidden md:table-cell">
                            Transfer Bank
                        </td>
                        <td class="px-6 py-4 font-extrabold text-[#18398a]">
                            Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.payroll.download', $latestPayroll) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#18398a] hover:bg-[#122b6b] text-white transition-colors" title="Unduh PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($userPayrolls->count() == 0)
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium">
                            Belum ada riwayat gaji yang tercatat.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- Tampilan Jika Karyawan Belum Memiliki Gaji Sama Sekali -->
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-sm">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Data Gaji</h3>
        <p class="text-gray-500">Anda belum memiliki riwayat slip gaji yang diterbitkan oleh HR/Admin.</p>
    </div>
    @endif
</div>
</x-app-layout>
