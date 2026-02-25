<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group focus:outline-none">
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl shadow-lg shadow-blue-500/30 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            🎒
                        </div>
                        <div class="hidden sm:flex flex-col">
                            <span class="font-black text-xl tracking-tight text-slate-800 group-hover:text-blue-600 transition-colors leading-none">RuangAspirasi</span>
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
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-sm font-black shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            
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
                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-xl font-black shadow-md">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
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
</nav>