<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi Pengaduan Sarana - SMK Mutiara Bandung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Pattern Background */
        .bg-pattern { 
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px); 
            background-size: 32px 32px; 
        }

        /* --- PRELOADER & ENTRANCE ANIMATIONS --- */
        @keyframes pulseLogo {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        @keyframes loadingBar {
            0% { width: 0%; }
            40% { width: 60%; }
            100% { width: 100%; }
        }
        .animate-pulse-logo { animation: pulseLogo 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        .animate-loading-bar { animation: loadingBar 2s ease-in-out forwards; }
        #preloader { transition: opacity 0.8s ease-out, transform 0.8s ease-out, visibility 0.8s; }
        .preloader-hidden { opacity: 0; visibility: hidden; transform: translateY(-30px) scale(1.02); pointer-events: none; }
        .content-hidden { opacity: 0; transform: translateY(30px); }
        .content-visible { opacity: 1; transform: translateY(0); transition: opacity 1s ease-out, transform 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }

        /* --- BACKGROUND FLOATING ANIMATIONS --- */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 3s infinite; }

        /* --- PAGE TRANSITION --- */
        body.page-exit-active { opacity: 0; transform: translateY(-20px) scale(0.98); transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; }
        .btn-press-effect:active { transform: scale(0.92); box-shadow: 0 0 0 rgba(0,0,0,0); }

        /* ================= NEW ANIMATIONS FOR SECTION FITUR ================= */
        
        /* Animasi Kursor Mesin Ketik */
        .typing-cursor::after {
            content: '|';
            animation: blink 0.7s infinite;
            color: #2563eb;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

        /* Desain Sticky Note */
        .sticky-note {
            position: relative;
            box-shadow: 3px 5px 15px rgba(0,0,0,0.1), inset -2px -2px 10px rgba(0,0,0,0.02);
            border-bottom-right-radius: 30px 5px; /* Efek kertas terlipat sedikit di ujung */
            opacity: 0; /* Tersembunyi sebelum animasi */
        }

        /* Animasi Kertas Ditempelkan */
        @keyframes pasteNote {
            0% { transform: scale(1.5) translateZ(50px) rotate(10deg); opacity: 0; }
            40% { transform: scale(0.95) rotate(var(--target-rot)); opacity: 1; }
            100% { transform: scale(1) rotate(var(--target-rot)); opacity: 1; }
        }
        .animate-paste {
            animation: pasteNote 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Animasi Pin Menancap */
        @keyframes dropPin {
            0% { transform: translate(-50%, -40px) scale(2); opacity: 0; }
            100% { transform: translate(-50%, 0) scale(1); opacity: 1; }
        }
        .animate-pin {
            animation: dropPin 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        /* Pin Base Design */
        .push-pin {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            opacity: 0; /* Tersembunyi sebelum animasi */
            z-index: 10;
        }
    </style>
</head>
<body class="antialiased font-sans text-slate-800 bg-pattern overflow-x-hidden selection:bg-blue-200 selection:text-blue-900">

    <div id="preloader" class="fixed inset-0 z-[100] bg-white flex flex-col items-center justify-center">
        <div class="relative mb-6">
            <div class="absolute inset-0 bg-blue-400 blur-2xl opacity-20 rounded-full animate-pulse"></div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Mutiara" class="w-32 h-32 md:w-40 md:h-40 object-contain relative z-10 animate-pulse-logo">
        </div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-widest mb-2 text-center">SMK MUTIARA</h2>
        <p class="text-sm font-semibold text-blue-600 tracking-widest uppercase mb-8">Bandung</p>
        <div class="w-48 md:w-64 h-1.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full animate-loading-bar relative">
                <div class="absolute top-0 right-0 bottom-0 left-0 bg-white/30 animate-pulse"></div>
            </div>
        </div>
    </div>

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden flex justify-center items-center">
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-300/20 rounded-full blur-[100px] animate-float"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-indigo-300/20 rounded-full blur-[100px] animate-float-delayed"></div>
    </div>

    <div id="main-wrapper" class="relative z-10 min-h-screen flex flex-col opacity-0 transition-opacity duration-700">
        
        <nav class="bg-white/70 backdrop-blur-md border-b border-white/50 sticky top-0 z-50 shadow-sm reveal-item content-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-110 group-hover:rotate-6 transition duration-300">
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight hidden sm:block">SMK MUTIARA</h1>
                </div>
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="page-link text-blue-600 font-bold hover:text-blue-800 transition px-5 py-2.5 rounded-xl hover:bg-blue-50 flex items-center gap-2">
                            Ke Dashboard <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="page-link bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1 hover:shadow-xl flex items-center gap-2 text-sm md:text-base">
                            Masuk Portal
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 pt-10 pb-20 overflow-hidden">
            <div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="text-left space-y-8 z-10">
                    <div class="reveal-item content-hidden delay-100 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-slate-200 shadow-sm text-blue-600 font-bold text-xs uppercase tracking-wider">
                        <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span></span>
                        Platform Ujikom RPL 2026
                    </div>
                    
                    <h1 class="reveal-item content-hidden delay-200 text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
                        Aspirasi <br>
                        <span class="text-blue-600 relative inline-block">
                            Fasilitas Sekolah
                            <svg class="absolute w-full h-3 -bottom-1 left-0 text-blue-200 -z-10" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0,15 Q50,0 100,15 L100,20 L0,20 Z" fill="currentColor"/></svg>
                        </span><br>
                        Lebih Mudah.
                    </h1>
                    
                    <p class="reveal-item content-hidden delay-300 text-lg md:text-xl text-slate-600 leading-relaxed max-w-lg">
                        Sistem pelaporan kerusakan sarana dan prasarana terpadu. Pantau progres perbaikan secara real-time demi kenyamanan belajar di SMK Mutiara Bandung.
                    </p>
                    
                    <div class="reveal-item content-hidden delay-400 flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('login') }}" class="page-link px-8 py-4 bg-slate-900 text-white font-bold rounded-full shadow-xl hover:shadow-2xl hover:-translate-y-1 btn-press-effect flex items-center gap-2 group">
                            Mulai Lapor Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        
                        <button id="btn-pelajari" class="px-8 py-4 bg-white text-slate-700 font-bold rounded-full shadow-sm hover:shadow-md border border-slate-200 hover:bg-slate-50 btn-press-effect flex items-center gap-2 transition-all">
                            Pelajari Alur
                            <svg class="w-5 h-5 text-slate-400 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="relative h-[450px] hidden lg:block reveal-item content-hidden delay-300">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 border-2 border-dashed border-slate-300 rounded-full animate-[spin_30s_linear_infinite] z-0"></div>
                    <div class="absolute top-10 right-4 w-72 bg-white p-5 rounded-3xl shadow-xl border border-slate-100 animate-float z-20 hover:scale-105 transition-transform cursor-default">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-2xl font-bold shrink-0">🪑</div>
                            <div>
                                <h4 class="font-extrabold text-slate-800 text-base leading-tight mb-1">Meja Kelas X Patah</h4>
                                <p class="text-xs text-slate-500 font-medium">Oleh: Andi • Baru saja</p>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mb-2"><div class="bg-red-500 h-2 rounded-full w-1/4"></div></div>
                        <p class="text-xs text-right text-red-500 font-bold">Menunggu Validasi</p>
                    </div>
                    <div class="absolute bottom-16 left-0 w-80 bg-white p-5 rounded-3xl shadow-xl border border-slate-100 animate-float-delayed z-30 hover:scale-105 transition-transform cursor-default">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl font-bold shrink-0">💻</div>
                            <div>
                                <h4 class="font-extrabold text-slate-800 text-base leading-tight mb-1">Proyektor Lab Rusak</h4>
                                <p class="text-xs text-slate-500 font-medium">Ditangani: Bapak Budi Sarpras</p>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mb-2"><div class="bg-blue-600 h-2 rounded-full w-3/4 animate-pulse"></div></div>
                        <p class="text-xs text-right text-blue-600 font-bold">Sedang Dikerjakan</p>
                    </div>
                </div>
            </div>
        </main>

        <section id="fitur" class="py-24 relative z-20 mt-10 hidden">
            <div class="absolute inset-0 bg-amber-900/5 border-t-8 border-amber-800/20 -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 h-28"> <h2 id="typed-title" class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 min-h-[40px]"></h2>
                    <p id="typed-desc" class="text-slate-600 max-w-2xl mx-auto text-lg min-h-[60px]"></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8 mt-12">
                    
                    <div id="note-1" class="sticky-note bg-yellow-100 p-8 pt-10 group" style="--target-rot: -3deg;">
                        <svg id="pin-1" class="push-pin text-red-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C10.9 2 10 2.9 10 4V12.1L8.3 14H15.7L14 12.1V4C14 2.9 13.1 2 12 2M11 16V22H13V16H11Z" filter="drop-shadow(2px 2px 2px rgba(0,0,0,0.3))"/>
                            <circle cx="12" cy="5" r="3" fill="#ef4444" />
                        </svg>
                        
                        <div class="w-14 h-14 bg-black/5 text-slate-800 rounded-xl flex items-center justify-center text-2xl mb-4 font-black font-mono">1</div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3 border-b-2 border-black/10 pb-2 inline-block">Tulis Laporan</h3>
                        <p class="text-slate-700 leading-relaxed font-medium">Temukan fasilitas yang rusak, foto, dan tulis deskripsi singkat mengenai kerusakan tersebut melalui dashboard akun Anda.</p>
                    </div>

                    <div id="note-2" class="sticky-note bg-teal-100 p-8 pt-10 group" style="--target-rot: 2deg;">
                        <svg id="pin-2" class="push-pin text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C10.9 2 10 2.9 10 4V12.1L8.3 14H15.7L14 12.1V4C14 2.9 13.1 2 12 2M11 16V22H13V16H11Z" filter="drop-shadow(2px 2px 2px rgba(0,0,0,0.3))"/>
                            <circle cx="12" cy="5" r="3" fill="#3b82f6" />
                        </svg>

                        <div class="w-14 h-14 bg-black/5 text-slate-800 rounded-xl flex items-center justify-center text-2xl mb-4 font-black font-mono">2</div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3 border-b-2 border-black/10 pb-2 inline-block">Proses Perbaikan</h3>
                        <p class="text-slate-700 leading-relaxed font-medium">Laporan divalidasi oleh pihak sarpras dan teknisi segera ditugaskan untuk melakukan perbaikan di lokasi secepatnya.</p>
                    </div>

                    <div id="note-3" class="sticky-note bg-pink-100 p-8 pt-10 group" style="--target-rot: -1deg;">
                        <svg id="pin-3" class="push-pin text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C10.9 2 10 2.9 10 4V12.1L8.3 14H15.7L14 12.1V4C14 2.9 13.1 2 12 2M11 16V22H13V16H11Z" filter="drop-shadow(2px 2px 2px rgba(0,0,0,0.3))"/>
                            <circle cx="12" cy="5" r="3" fill="#eab308" />
                        </svg>

                        <div class="w-14 h-14 bg-black/5 text-slate-800 rounded-xl flex items-center justify-center text-2xl mb-4 font-black font-mono">3</div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-3 border-b-2 border-black/10 pb-2 inline-block">Selesai & Ulasan</h3>
                        <p class="text-slate-700 leading-relaxed font-medium">Setelah fasilitas diperbaiki, Anda akan mendapat notifikasi dan dapat memberikan rating bintang atas hasil perbaikan.</p>
                    </div>

                </div>
            </div>
        </section>
        </div>

    <script>
        // --- LOGIKA MESIN KETIK (TYPEWRITER) ---
        function typeWriter(element, text, speed, callback) {
            element.innerHTML = '';
            element.classList.add('typing-cursor');
            let i = 0;
            
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                } else {
                    element.classList.remove('typing-cursor');
                    if(callback) callback();
                }
            }
            type();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const preloader = document.getElementById('preloader');
            const mainWrapper = document.getElementById('main-wrapper');
            const revealItems = document.querySelectorAll('.reveal-item');
            
            // Waktu loading awal
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
                mainWrapper.classList.remove('opacity-0');
                
                setTimeout(() => {
                    revealItems.forEach(item => {
                        item.classList.remove('content-hidden');
                        item.classList.add('content-visible');
                    });
                }, 300);
            }, 2000);

            // --- LOGIKA TOMBOL PELAJARI ALUR ---
            const btnPelajari = document.getElementById('btn-pelajari');
            const fiturSection = document.getElementById('fitur');
            const titleEl = document.getElementById('typed-title');
            const descEl = document.getElementById('typed-desc');
            let isRevealed = false; // Mencegah animasi berulang jika ditekan berkali-kali

            btnPelajari.addEventListener('click', () => {
                if (isRevealed) {
                    // Jika sudah pernah ditekan, cukup scroll saja ke bawah
                    fiturSection.scrollIntoView({ behavior: 'smooth' });
                    return;
                }
                
                isRevealed = true;
                
                // 1. Munculkan Section (Ubah display: none jadi block)
                fiturSection.classList.remove('hidden');
                fiturSection.style.display = 'block';

                // 2. Scroll Halus ke Section Tersebut
                setTimeout(() => {
                    fiturSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);

                // 3. Mulai Animasi Ketik (Jeda sebentar agar scroll selesai)
                setTimeout(() => {
                    // Ketik Judul
                    typeWriter(titleEl, "Bagaimana Cara Kerjanya?", 50, () => {
                        // Ketik Deskripsi
                        typeWriter(descEl, "Tiga langkah mudah untuk menciptakan lingkungan sekolah yang lebih baik dan nyaman.", 30, () => {
                            
                            // 4. Setelah Ketik Selesai, Tempelkan Kertas Note Berurutan
                            const notes = [1, 2, 3];
                            notes.forEach((num, index) => {
                                setTimeout(() => {
                                    // Tempel Kertas Note
                                    const noteEl = document.getElementById(`note-${num}`);
                                    noteEl.classList.add('animate-paste');
                                    
                                    // Tancapkan Pin setelah kertas menempel (jeda 300ms dari kertas)
                                    setTimeout(() => {
                                        const pinEl = document.getElementById(`pin-${num}`);
                                        pinEl.classList.add('animate-pin');
                                    }, 300);

                                }, index * 600); // Kertas muncul bergantian setiap 0.6 detik
                            });

                        });
                    });
                }, 600);
            });

            // --- LOGIKA PINDAH HALAMAN (SMOOTH EXIT) ---
            const pageLinks = document.querySelectorAll('.page-link');
            pageLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    document.body.classList.add('page-exit-active');
                    setTimeout(() => { window.location.href = this.href; }, 500); 
                });
            });
        });
    </script>
</body>
</html>