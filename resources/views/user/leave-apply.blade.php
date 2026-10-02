@extends('layouts.app')

@section('content')
    <div class="flex items-center mb-8 space-x-3">
        <a href="{{ route('user.leave') }}" class="text-gray-400 hover:text-[#1e3a8a] transition">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Ajukan Cuti</h2>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Formulir Pengajuan Cuti</h3>
                    <p class="text-sm text-gray-500">Lengkapi data di bawah ini untuk mengajukan permohonan cuti Anda.</p>
                </div>
                
                <form action="{{ route('user.leave.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Cuti</label>
                            <div class="relative">
                                <select name="leave_type" class="w-full appearance-none bg-white border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option>Cuti Tahunan</option>
                                    <option>Izin Sakit</option>
                                    <option>Cuti Melahirkan</option>
                                </select>
                                <i data-feather="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Durasi (Hari)</label>
                            <div class="relative">
                                <input type="number" name="duration" min="1" placeholder="0" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <span class="absolute right-4 top-2.5 text-sm text-gray-400">Hari Kerja</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <div class="relative">
                                <input type="date" name="start_date" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                            <div class="relative">
                                <input type="date" name="end_date" class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Cuti</label>
                        <textarea name="reason" rows="4" placeholder="Jelaskan alasan pengambilan cuti secara singkat dan jelas..." class="w-full border border-gray-300 text-gray-700 py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Pendukung (Opsional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition cursor-pointer">
                            <input type="file" id="file-upload" name="document_path" accept=".pdf,.jpg,.jpeg,.png" class="hidden">  
                            <p id="file-name" class="text-sm text-blue-600 font-semibold mt-2"></p>                          
                            <p class="text-sm text-gray-600 font-medium">Klik untuk unggah atau seret file ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">PDF, JPG, atau PNG (Maks. 5MB)</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('user.leave') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-[#1e3a8a] text-white rounded-lg text-sm font-medium hover:bg-blue-800 transition shadow-sm">Ajukan Sekarang</button>
                    </div>
                </form>
            </div>
            
            <div class="flex justify-between mt-6 text-sm text-gray-500 px-2">
                <div class="flex items-center"><i data-feather="headphones" class="w-4 h-4 mr-2"></i> Butuh Bantuan?</div>
                <div class="flex items-center"><i data-feather="shield" class="w-4 h-4 mr-2"></i> Data Aman</div>
                <div class="flex items-center"><i data-feather="server" class="w-4 h-4 mr-2"></i> Arsip Digital</div>
            </div>
        </div>

        <div class="col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h4 class="font-semibold text-gray-800 mb-4">Sisa Kuota Cuti</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded"><i data-feather="calendar" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Cuti Tahunan</p>
                                <p class="text-xs text-gray-500">Berlaku s/d 31 Des 2026</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-gray-800">12 <span class="text-xs font-normal text-gray-500">Hari</span></span>
                    </div>
                    
                    <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-50 text-blue-400 p-2 rounded"><i data-feather="briefcase" class="w-4 h-4"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Cuti Sakit</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-gray-800">Unlimited</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
                <div class="flex items-center space-x-2 mb-4 text-blue-800">
                    <i data-feather="info" class="w-5 h-5"></i>
                    <h4 class="font-semibold">Kebijakan Cuti</h4>
                </div>
                <ul class="text-sm text-blue-900 space-y-3 list-disc pl-4">
                    <li>Pengajuan maksimal H-7 untuk cuti tahunan.</li>
                    <li>Lampirkan surat dokter jika cuti sakit > 2 hari.</li>
                    <li>Persetujuan dilakukan oleh Human Growth & Manager.</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
    document.querySelector('.border-dashed').addEventListener('click', function() {
        document.getElementById('file-upload').click();
    });

    const fileInput = document.getElementById('file-upload');
    const fileNameDisplay = document.getElementById('file-name');

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileNameDisplay.textContent = "File terpilih: " + this.files[0].name;
        }
    });
</script>
@endsection