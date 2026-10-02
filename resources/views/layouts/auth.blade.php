<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReTalent HRIS - @yield('title')</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50 flex flex-col h-screen font-sans">
    
    <header class="bg-white px-8 py-4 flex justify-between items-center w-full z-10 absolute top-0">
        <h1 class="text-[#1e3a8a] text-xl font-bold tracking-wide">ReTalent HRIS</h1>
        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Bantuan</a>
    </header>

    <div class="flex flex-1 pt-16">
        <div class="hidden lg:flex lg:w-1/2 bg-[#0d2a7a] text-white flex-col justify-center px-16 py-12 relative overflow-hidden">
            <h2 class="text-4xl font-bold mb-4">CV. MIB</h2>
            <p class="text-blue-100 text-lg leading-relaxed mb-12">
                Memberdayakan sumber daya manusia dengan integritas dan profesionalisme tinggi dalam ekosistem digital.
            </p>

            <div class="space-y-8 relative z-10">
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-800/50 p-2 rounded-lg mt-1"><i data-feather="heart" class="w-6 h-6"></i></div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Bisnis Berkah</h4>
                        <p class="text-sm text-blue-200">Mengutamakan keberkahan dalam setiap transaksi dan operasional perusahaan.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-800/50 p-2 rounded-lg mt-1"><i data-feather="shield" class="w-6 h-6"></i></div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Amanah & Profesional</h4>
                        <p class="text-sm text-blue-200">Menjalankan tugas dengan penuh tanggung jawab dan standar kualitas terbaik.</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="bg-blue-800/50 p-2 rounded-lg mt-1"><i data-feather="home" class="w-6 h-6"></i></div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Prinsip Syariah</h4>
                        <p class="text-sm text-blue-200">Implementasi nilai-nilai keadilan dan transparansi sesuai kaidah syariah.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center bg-gray-50">
            @yield('content')
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 px-8 py-4 flex justify-between items-center text-xs text-gray-500 absolute bottom-0 w-full z-10">
        <div>
            <p class="font-bold text-gray-700">CV. MIB</p>
            <p>&copy; 2026 CV. MIB. Seluruh hak cipta dilindungi.</p>
        </div>
        <div class="space-x-4">
            <a href="#" class="hover:text-gray-700">Kebijakan Privasi</a>
            <a href="#" class="hover:text-gray-700">Syarat & Ketentuan</a>
            <a href="#" class="hover:text-gray-700">Hubungi Kami</a>
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>