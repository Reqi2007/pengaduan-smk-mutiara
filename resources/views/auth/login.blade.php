<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Sistem Pengaduan SMK Mutiara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Pattern Background Sama dengan Welcome */
        .bg-pattern { 
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); 
            background-size: 32px 32px; 
        }

        /* --- BACKGROUND FLOATING ANIMATIONS --- */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }

        /* --- SEAMLESS PAGE TRANSITION (Pindah Halaman) --- */
        /* Awal mula saat halaman baru diload (Menyambung dari animasi exit Welcome) */
        body.page-enter { 
            opacity: 0; 
            transform: translateY(20px) scale(0.98); 
        }
        /* Saat animasi berjalan */
        body.page-enter-active { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
            transition: opacity 0.6s ease-out, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        
        /* Animasi saat tombol login ditekan (Keluar) */
        body.page-exit-active { 
            opacity: 0; 
            transform: scale(1.02); 
            transition: all 0.4s ease-in-out; 
            pointer-events: none; 
        }

        /* Animasi Kaca (Glassmorphism) */
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="antialiased font-sans text-slate-800 bg-pattern min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-blue-200 selection:text-blue-900 page-enter">

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden flex justify-center items-center">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full blur-[100px] animate-float"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] bg-indigo-400/20 rounded-full blur-[100px] animate-float-delayed"></div>
    </div>

    <a href="{{ url('/') }}" class="absolute top-6 left-6 z-20 flex items-center gap-2 px-4 py-2 bg-white/70 backdrop-blur-sm rounded-full shadow-sm text-slate-500 font-bold hover:text-blue-600 hover:bg-white transition duration-300 page-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>

    <div class="relative z-10 w-full max-w-md px-6">
        
        <div class="flex justify-center mb-6">
            <div class="relative group">
                <div class="absolute inset-0 bg-blue-500 blur-xl opacity-30 rounded-full group-hover:opacity-50 transition duration-500"></div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Mutiara" class="w-24 h-24 object-contain relative z-10 transform group-hover:scale-105 transition duration-500">
            </div>
        </div>

        <div class="glass-card p-8 rounded-[2rem] shadow-2xl shadow-blue-900/10">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang! 👋</h2>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk menggunakan akun Anda.</p>
            </div>

            <x-auth-session-status class="mb-4 text-sm font-bold text-green-600 bg-green-50 p-3 rounded-xl text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="mb-5">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama Anda..." 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-slate-400">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-500 font-semibold" />
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-slate-400">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500 font-semibold" />
                </div>

                <div class="flex items-center justify-between mb-8">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 group-hover:border-blue-400 transition-colors w-4 h-4">
                        <span class="ms-2 text-sm font-semibold text-slate-600 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition hover:-translate-y-0.5">
                    Masuk Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs font-semibold mt-8 animate-pulse">
            Sistem Pengaduan Sarpras © 2026
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Jalankan Animasi Masuk (Menyambung efek dari Welcome)
            // Membutuhkan sedikit delay requestAnimationFrame agar browser sempat merender state awal
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    document.body.classList.add('page-enter-active');
                });
            });

            // 2. Animasi Keluar Saat Menekan Tombol Kembali
            const pageLinks = document.querySelectorAll('.page-link');
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    const targetUrl = this.href;

                    document.body.classList.remove('page-enter-active');
                    document.body.classList.add('page-exit-active');

                    setTimeout(() => {
                        window.location.href = targetUrl;
                    }, 400); 
                });
            });

            // 3. Animasi Keluar Saat Form Disubmit (Login)
            const loginForm = document.getElementById('login-form');
            loginForm.addEventListener('submit', function(e) {
                // Jangan di preventDefault() karena kita butuh data dikirim ke server.
                // Kita hanya menambahkan animasi fade out sembari browser loading memproses data.
                document.body.classList.remove('page-enter-active');
                document.body.classList.add('page-exit-active');
            });
        });
    </script>
</body>
</html>