<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm sticky top-0 z-50 transition-all duration-300">
    
    <style>
        /* Teto Container */
        .teto-char { position: absolute; width: 48px; height: 48px; top: 0; left: 0; pointer-events: none; }
        
        /* Ahoge (Antena) - Di tengah presisi */
        .teto-ahoge {
            position: absolute; width: 12px; height: 14px;
            border-top: 3.5px solid #D13A54; border-right: 3.5px solid #D13A54;
            border-radius: 0 100% 0 0;
            top: 2px; left: 18px; /* Tepat di tengah (18 + 6 = 24 = tengah dari 48) */
            transform-origin: bottom left; transform: rotate(-5deg);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* Rambut Belakang */
        .teto-backhair {
            position: absolute; width: 36px; height: 30px;
            background: #D13A54; border-radius: 18px 18px 10px 10px;
            top: 10px; left: 6px; z-index: 1;
        }
        
        /* Poni Depan (3 Bagian: Kiri, Tengah, Kanan) */
        .teto-bang-base {
            position: absolute; width: 36px; height: 12px;
            background: #D13A54; border-radius: 18px 18px 0 0;
            top: 9px; left: 6px; z-index: 4;
        }
        .teto-bang-center {
            position: absolute; width: 12px; height: 10px;
            background: #D13A54; border-radius: 0 0 6px 6px;
            top: 19px; left: 18px; z-index: 5;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .teto-bang-side {
            position: absolute; width: 10px; height: 16px;
            background: #D13A54; top: 16px; z-index: 4;
        }
        .teto-bang-side.left { left: 4px; border-radius: 8px 0 0 12px; transform: rotate(15deg); }
        .teto-bang-side.right { right: 4px; border-radius: 0 8px 12px 0; transform: rotate(-15deg); }

/* Twin Drills (Rambut Bor Kiri & Kanan - Diperbesar & Dinaikkan) */
        .teto-drill {
            position: absolute; 
            width: 18px;  /* Diperbesar dari 14px */
            height: 34px; /* Diperpanjang dari 28px */
            background: repeating-linear-gradient(-25deg, #D13A54, #D13A54 6px, #A92640 6px, #A92640 9px); /* Jarak striping disesuaikan dengan ukuran baru */
            border-radius: 50% 50% 50% 50% / 15% 15% 85% 85%;
            top: 18px; /* Dinaikkan ke atas (sebelumnya 22px) */
            z-index: 2;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        
        /* Penyesuaian jarak kiri/kanan agar proporsional dengan wajah */
        .teto-drill.left { 
            left: -4px; /* Digeser keluar agar tidak terlalu menempel ke pipi */
            transform: rotate(10deg); 
            transform-origin: top center; 
        }
        .teto-drill.right { 
            right: -4px; /* Digeser keluar agar seimbang */
            transform: rotate(-10deg) scaleX(-1); 
            transform-origin: top center; 
        }

        /* Wajah & Pipi (Sedikit Diperbesar & Presisi Tengah) */
        .teto-face {
            position: absolute; width: 30px; height: 24px;
            background: #FFE4D6; 
            border-radius: 45% 45% 50% 50%;
            top: 20px; left: 9px; z-index: 3;
        }
        .teto-blush {
            position: absolute; width: 6px; height: 3px;
            background: #FF8BA0; border-radius: 50%;
            top: 13px; opacity: 0.5; transition: all 0.3s;
        }
        .teto-blush.left { left: 2px; transform: rotate(-10deg); }
        .teto-blush.right { right: 2px; transform: rotate(10deg); }

        /* Mata Imut (Pupil Putih Besar) */
        .teto-eye {
            position: absolute; width: 6px; height: 8px;
            background: #8A152B; /* Merah padat tidak horror */
            border-radius: 50%;
            top: 7px; overflow: hidden; transition: all 0.2s;
            animation: teto-blink 4s infinite;
        }
        .teto-eye.left { left: 5px; }
        .teto-eye.right { right: 5px; }
        
        .teto-pupil {
            position: absolute; width: 3.5px; height: 3.5px; /* Bagian putih lebih besar */
            background: white; border-radius: 50%;
            top: 1px; left: 1px; transition: transform 0.05s linear;
        }
        
        /* Mulut */
        .teto-mouth {
            position: absolute; width: 6px; height: 3px;
            background: #A92640; border-radius: 0 0 5px 5px;
            top: 17px; left: 12px; transition: all 0.2s;
        }

        /* ANIMASI DEFAULT */
        @keyframes teto-blink { 0%, 94%, 98%, 100% { transform: scaleY(1); } 96% { transform: scaleY(0.1); } }
        @keyframes teto-wag { 0% { transform: rotate(-15deg); } 100% { transform: rotate(5deg); } }

        /* EFEK HOVER: SENANG */
        .group:hover .teto-eye { height: 3px; border-radius: 50% 50% 0 0; margin-top: 4px; animation: none; background: #8A152B; }
        .group:hover .teto-pupil { opacity: 0; }
        .group:hover .teto-mouth { width: 8px; height: 4px; border-radius: 0 0 8px 8px; top: 16px; left: 11px; }
        .group:hover .teto-blush { opacity: 0.9; transform: scale(1.2); }
        .group:hover .teto-drill.left { transform: rotate(20deg) translateY(-2px); }
        .group:hover .teto-drill.right { transform: rotate(-20deg) scaleX(-1) translateY(-2px); }
        .group:hover .teto-ahoge { animation: teto-wag 0.25s infinite alternate; }

        /* EFEK CLICK/TOUCH: KAGET */
        .teto-surprised .teto-eye { height: 8px; width: 6px; border-radius: 50%; margin-top: 0; animation: none; background: #8A152B; }
        .teto-surprised .teto-pupil { top: 2px; left: 1.5px; transform: scale(0.8) !important; opacity: 1; }
        .teto-surprised .teto-mouth { width: 5px; height: 6px; border-radius: 50%; left: 12.5px; top: 16px; background: #8A152B; }
        .teto-surprised .teto-ahoge { transform: rotate(-35deg) scaleY(1.3); animation: none; }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none" id="teto-link">
                        
                        <div id="teto-logo" class="w-12 h-12 bg-blue-500 border border-blue-400 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 transform group-hover:scale-105 transition-all duration-300 relative overflow-hidden">
                            <div class="teto-char">
                                <div class="teto-ahoge"></div>
                                <div class="teto-backhair"></div>
                                <div class="teto-drill left"></div>
                                <div class="teto-drill right"></div>
                                
                                <div class="teto-bang-base"></div>
                                <div class="teto-bang-side left"></div>
                                <div class="teto-bang-side right"></div>
                                <div class="teto-bang-center"></div>
                                
                                <div class="teto-face">
                                    <div class="teto-blush left"></div>
                                    <div class="teto-blush right"></div>
                                    <div class="teto-eye left"><div class="teto-pupil"></div></div>
                                    <div class="teto-eye right"><div class="teto-pupil"></div></div>
                                    <div class="teto-mouth"></div>
                                </div>
                            </div>
                        </div>

                        <div class="hidden sm:flex flex-col">
                            <span class="font-black text-xl tracking-tight text-slate-800 group-hover:text-blue-600 transition-colors leading-none">Ruang Aspirasi</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sistem Pelaporan</span>
                        </div>
                    </a>
                </div>

                <div class="hidden sm:flex sm:space-x-2">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-4 py-2 rounded-xl font-bold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} border-none !ring-0">
                        🏠 {{ __('Beranda') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-2 py-1.5 border border-slate-200 hover:border-blue-300 rounded-[2rem] bg-white/50 hover:bg-blue-50/50 transition-all duration-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 group">
                            
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover shadow-md shadow-blue-500/20 border border-slate-100 group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-sm font-black shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="font-extrabold text-sm text-slate-700">
                                {{ explode(' ', Auth::user()->name)[0] }}
                            </div>

                            <div class="pr-2 text-slate-400 group-hover:text-blue-500 transition-colors">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="p-2 space-y-1">
                            <div class="px-4 py-3 border-b border-slate-100 mb-1">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Login sebagai</p>
                                <p class="text-sm font-black text-slate-800 truncate">{{ Auth::user()->name }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 flex items-center gap-2">
                                👤 {{ __('Profil Saya') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" 
                                        class="rounded-xl font-bold text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center gap-2 mt-1">
                                    🚪 {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none focus:bg-blue-50 focus:text-blue-600 transition duration-150 ease-in-out border border-transparent hover:border-blue-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-md border-t border-slate-100 absolute w-full shadow-xl">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl font-bold flex items-center gap-2 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600' }}">
                🏠 {{ __('Beranda') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-4 border-t border-slate-100 bg-slate-50/50">
            <div class="px-6 flex items-center gap-4">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover shadow-md">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-xl font-black shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <div class="font-extrabold text-base text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-1 px-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-bold text-slate-600 flex items-center gap-2">
                    👤 {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="rounded-xl font-bold text-red-600 hover:bg-red-50 flex items-center gap-2">
                        🚪 {{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tetoLink = document.getElementById('teto-link');
            const tetoLogo = document.getElementById('teto-logo');
            const pupils = document.querySelectorAll('.teto-pupil');

            // 1. Mata Mengikuti Kursor
            document.addEventListener('mousemove', (e) => {
                if (tetoLink.matches(':hover') || tetoLogo.classList.contains('teto-surprised')) {
                    pupils.forEach(p => p.style.transform = `translate(0px, 0px)`);
                    return;
                }

                const rect = tetoLogo.getBoundingClientRect();
                const tetoX = rect.left + rect.width / 2;
                const tetoY = rect.top + rect.height / 2;

                const deltaX = e.clientX - tetoX;
                const deltaY = e.clientY - tetoY;

                const angle = Math.atan2(deltaY, deltaX);
                const distance = Math.min(1.5, Math.hypot(deltaX, deltaY) / 60); 

                const moveX = Math.cos(angle) * distance;
                const moveY = Math.sin(angle) * distance;

                pupils.forEach(pupil => {
                    pupil.style.transform = `translate(${moveX}px, ${moveY}px)`;
                });
            });

            // 2. Ekspresi Kaget saat Disentuh / Diklik
            tetoLink.addEventListener('click', (e) => {
                e.preventDefault(); 
                tetoLogo.classList.add('teto-surprised');
                
                setTimeout(() => {
                    window.location.href = tetoLink.href;
                }, 300);
            });
            
            tetoLink.addEventListener('touchstart', () => {
                tetoLogo.classList.add('teto-surprised');
            });
        });
    </script>
</nav>