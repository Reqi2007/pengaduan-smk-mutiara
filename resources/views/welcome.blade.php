<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Pengaduan Sarana - SMK Mutiara Bandung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-md py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-full font-bold text-xl mr-3 shadow-lg">M</div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">SMK MUTIARA</h1>
            </div>
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:text-blue-800 font-bold transition">Ke Dashboard &rarr;</a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-semibold shadow-md transition transform hover:-translate-y-1">Log in</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mt-16 mx-auto max-w-7xl px-4 sm:mt-24 sm:px-6 lg:mt-32">
        <div class="text-center">
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="block">Layanan Aspirasi & Pengaduan</span>
                <span class="block text-blue-600 mt-2">Fasilitas Sekolah</span>
            </h1>
            <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                Mari bersama menjaga fasilitas SMK Mutiara Bandung. Sampaikan laporan kerusakan sarana dan prasarana dengan mudah, cepat, dan terpantau secara real-time.
            </p>
            <div class="mt-8 max-w-md mx-auto sm:flex sm:justify-center md:mt-10">
                <div class="rounded-md shadow">
                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 transition duration-300">
                        Buat Laporan Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 pb-20">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="text-4xl mb-4">📝</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Tulis Laporan</h3>
                <p class="text-gray-600 text-sm">Sampaikan keluhan atau kerusakan fasilitas yang kamu temukan di area sekolah.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="text-4xl mb-4">⚙️</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Proses Tindak Lanjut</h3>
                <p class="text-gray-600 text-sm">Laporanmu akan langsung diverifikasi dan diproses oleh Guru / Teknisi sekolah.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-xl transition duration-300 transform hover:-translate-y-2">
                <div class="text-4xl mb-4">✅</div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Selesai & Nyaman</h3>
                <p class="text-gray-600 text-sm">Fasilitas kembali berfungsi dengan baik untuk menunjang kegiatan belajar mengajar.</p>
            </div>
        </div>
    </main>

    <footer class="bg-gray-800 text-white text-center py-6">
        <p>&copy; 2024 Aplikasi Pengaduan Sarpras SMK Mutiara Bandung. Dibuat oleh <strong>Refan Al-Kholqi</strong> untuk Ujikom RPL.</p>
    </footer>

</body>
</html>