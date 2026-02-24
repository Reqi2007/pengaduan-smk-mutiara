<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Pengaduan Sarana - SMK Mutiara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animasi Kustom Sederhana */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .bg-pattern { background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px; }
    </style>
</head>
<body class="antialiased bg-slate-50 font-sans relative overflow-x-hidden" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">

    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute top-[20%] right-[-5%] w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="min-h-screen bg-pattern relative z-10 flex flex-col justify-between">
        <nav class="bg-white/70 backdrop-blur-md shadow-sm sticky top-0 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center rounded-xl font-bold text-2xl shadow-lg group-hover:rotate-12 transition duration-300">M</div>
                    <h1 class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-700 to-indigo-700 tracking-tight">SMK MUTIARA</h1>
                </div>
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-blue-600 font-bold hover:text-indigo-800 transition px-4 py-2 rounded-lg hover:bg-blue-50">Ke Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-2.5 rounded-full font-bold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1 hover:scale-105">Log in Portal</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl" 
                 x-show="mounted" 
                 x-transition:enter="transition ease-out duration-1000" 
                 x-transition:enter-start="opacity-0 translate-y-10" 
                 x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                
                <span class="px-4 py-1.5 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm tracking-wide uppercase mb-6 inline-block border border-blue-200 shadow-sm animate-bounce">Platform Resmi Ujikom RPL 2026</span>
                
                <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                    Aspirasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Fasilitas Sekolah</span><br>Kini Lebih Mudah.
                </h1>
                <p class="mt-4 text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Sistem pelaporan kerusakan sarana dan prasarana terpadu. Pantau progres perbaikan secara real-time demi kenyamanan belajar bersama.
                </p>
                <div class="flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-slate-900 text-white font-bold rounded-full overflow-hidden shadow-2xl shadow-slate-500/50 hover:scale-105 transition-all duration-300">
                        <span class="relative z-10 flex items-center gap-2">Buat Laporan <svg class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg></span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>