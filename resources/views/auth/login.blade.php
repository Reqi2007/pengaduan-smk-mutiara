<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Sistem Pengaduan SMK Mutiara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-pattern { 
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); 
            background-size: 32px 32px; 
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }

        body.page-enter { opacity: 0; transform: translateY(20px) scale(0.98); }
        body.page-enter-active { opacity: 1; transform: translateY(0) scale(1); transition: opacity 0.6s ease-out, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        body.page-exit-active { opacity: 0; transform: scale(1.02); transition: all 0.4s ease-in-out; pointer-events: none; }

        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Modal Animations */
        #reset-modal {
            transition: opacity 0.3s ease-in-out, visibility 0.3s;
        }
        #reset-modal .modal-content {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .modal-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .modal-hidden .modal-content {
            transform: translateY(20px) scale(0.95);
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

        <div class="glass-card p-8 rounded-[2rem] shadow-2xl shadow-blue-900/10 relative z-10">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang! 👋</h2>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk menggunakan akun Anda.</p>
            </div>

            <x-auth-session-status class="mb-4 text-sm font-bold text-green-600 bg-green-50 p-3 rounded-xl text-center" :status="session('status')" />
            @if ($errors->any())
                <div class="mb-4 text-sm font-bold text-red-600 bg-red-50 p-3 rounded-xl text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf
                <div class="mb-5">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Masukkan nama Anda..." class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-slate-400">
                    </div>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input id="password" type="password" name="password" required placeholder="••••••••" class="block w-full pl-11 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder:text-slate-400">
                    </div>
                </div>

                <div class="flex items-center justify-between mb-8">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 group-hover:border-blue-400 transition-colors w-4 h-4">
                        <span class="ms-2 text-sm font-semibold text-slate-600 group-hover:text-slate-800 transition-colors">Ingat Saya</span>
                    </label>

                    <button type="button" onclick="toggleModal(true)" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors focus:outline-none">
                        Lupa sandi?
                    </button>
                </div>

                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-500/30 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition hover:-translate-y-0.5">
                    Masuk Sekarang
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
        <p class="text-center text-slate-500 text-xs font-semibold mt-8 animate-pulse">Sistem Pengaduan Sarpras © 2026</p>
    </div>

    <div id="reset-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm modal-hidden">
        <div class="absolute inset-0 cursor-pointer" onclick="toggleModal(false)"></div>
        
        <div class="modal-content relative w-full max-w-sm bg-white p-6 md:p-8 rounded-[2rem] shadow-2xl mx-4 border border-slate-100">
            <button onclick="toggleModal(false)" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="w-12 h-12 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mb-4 shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <h3 class="text-xl font-extrabold text-slate-900 mb-2">Permohonan Reset Sandi</h3>
            <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                Demi keamanan, pengaturan ulang sandi memerlukan persetujuan <span class="font-bold text-slate-700">Superadmin</span>. Masukkan Username/Email Anda untuk mengajukan permohonan.
            </p>

            <form method="POST" action="{{ route('password.admin.request') }}" id="request-reset-form">
                @csrf
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Username / Email Akun</label>
                    <input type="text" name="account_identifier" required placeholder="Cth: andi123 / andi@siswa.com" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                </div>
                
                <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all shadow-lg shadow-amber-500/30 hover:-translate-y-0.5">
                    Kirim Permohonan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
    <script>
        // Logika Transisi Halus Halaman
        document.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => requestAnimationFrame(() => {
                document.body.classList.add('page-enter-active');
            }));

            const pageLinks = document.querySelectorAll('.page-link');
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    document.body.classList.remove('page-enter-active');
                    document.body.classList.add('page-exit-active');
                    setTimeout(() => { window.location.href = this.href; }, 400); 
                });
            });

            document.getElementById('login-form').addEventListener('submit', () => {
                document.body.classList.remove('page-enter-active');
                document.body.classList.add('page-exit-active');
            });
        });

        // Logika Buka/Tutup Modal Lupa Password
        function toggleModal(show) {
            const modal = document.getElementById('reset-modal');
            if (show) {
                modal.classList.remove('modal-hidden');
            } else {
                modal.classList.add('modal-hidden');
            }
        }
    </script>
</body>
</html>