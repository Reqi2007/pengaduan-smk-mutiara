<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
        .animate-float-delayed { animation: float 6s ease-in-out 2.5s infinite; }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up { animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        /* Custom Scrollbar untuk Kolom Komentar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @php
        $adminWaNumber = '6282119096623';
        $adminWaMessage = rawurlencode('Halo Admin SMK Mutiara, saya ingin melaporkan gangguan, kesalahan, atau respon yang lambat pada aplikasi pengaduan.');
    @endphp

    @php
        $kinerjaStatus = request('kinerja_status', 'semua');
        $kinerjaSort = request('kinerja_sort', 'terbaru');
        $kinerjaTanggal = request('kinerja_tanggal', '');
    @endphp

    <div x-data="{ 
        activeTab: 'kinerja', 
        openModal: false, 
        showSuccess: true, 
        rateModal: false, 
        rateData: { pengaduan_id: '', rating: 0, hover: 0, komentar: '' },
        editRateModal: false,
        editRateData: { id: '', rating: 0, hover: 0, komentar: '' }
    }" class="relative min-h-screen bg-slate-50 overflow-hidden font-sans selection:bg-blue-200 selection:text-blue-900 pb-20">
        
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-300/20 rounded-full blur-[120px] animate-float"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] bg-indigo-300/20 rounded-full blur-[100px] animate-float-delayed"></div>
        </div>

        <div class="py-8 relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                @if(session('success'))
                    <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" 
                         x-transition class="bg-white border-l-4 border-green-500 px-6 py-4 rounded-2xl shadow-xl shadow-green-500/10 flex items-center gap-4 relative z-50">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-inner">✅</div> 
                        <span class="font-bold text-slate-800">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" 
                         x-transition class="bg-white border-l-4 border-red-500 px-6 py-4 rounded-2xl shadow-xl shadow-red-500/10 flex items-center gap-4 relative z-50">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xl shadow-inner">⚠️</div> 
                        <span class="font-bold text-slate-800">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="glass-panel rounded-[2rem] shadow-xl shadow-blue-900/5 p-8 border border-white relative overflow-hidden animate-slide-up">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-blue-100 to-transparent rounded-bl-full -z-10 opacity-70"></div>
                    
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative group shrink-0">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-xl shadow-blue-500/20">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-blue-500/20 border-4 border-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-sm" title="Online"></div>
                        </div>

                        <div class="text-center md:text-left flex-1">
                            <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-1">Selamat Datang Kembali 👋</h3>
                            <h1 class="text-3xl font-black text-slate-900 mb-2">{{ Auth::user()->name }}</h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mt-2">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg border border-slate-200">📧 {{ Auth::user()->email }}</span>
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg border border-indigo-100">NIS: {{ Auth::user()->nis_nip ?? '-' }}</span>
                                <span class="px-3 py-1 bg-sky-50 text-sky-700 text-xs font-bold rounded-lg border border-sky-100">🎓 Kelas: {{ Auth::user()->kelas ?? '-' }} {{ Auth::user()->jurusan ?? '' }}</span>
                            </div>
                        </div>

                        <div class="shrink-0 mt-4 md:mt-0 flex flex-col gap-3 w-full md:w-auto">
                            <button @click="openModal = true" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Buat Laporan Baru
                            </button>
                            <a href="https://wa.me/{{ $adminWaNumber }}?text={{ $adminWaMessage }}" target="_blank" class="w-full px-6 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl font-extrabold shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A11.8 11.8 0 0 0 12.01 0C5.43 0 .08 5.34.08 11.92c0 2.1.55 4.14 1.6 5.94L0 24l6.32-1.66a11.9 11.9 0 0 0 5.69 1.45h.01c6.57 0 11.91-5.34 11.91-11.92 0-3.18-1.24-6.17-3.41-8.39ZM12.01 21.5h-.01a9.6 9.6 0 0 1-4.89-1.34l-.35-.2-3.75.99.99-3.65-.23-.37a9.58 9.58 0 0 1-1.47-5.11c0-5.31 4.32-9.63 9.64-9.63 2.57 0 4.98 1 6.8 2.82a9.55 9.55 0 0 1 2.81 6.81c0 5.32-4.31 9.68-9.54 9.68Zm5.57-7.19c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.65.15s-.75.97-.92 1.17-.34.23-.63.08a8.03 8.03 0 0 1-2.37-1.46 8.94 8.94 0 0 1-1.65-2.05c-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.29.3-.49.1-.2.05-.38-.02-.53-.07-.15-.65-1.56-.9-2.14-.24-.57-.49-.49-.65-.5-.17-.01-.36-.01-.55-.01s-.5.07-.76.38-.98.96-.98 2.34.99 2.72 1.13 2.91c.15.2 1.97 3.02 4.77 4.24.67.29 1.2.47 1.61.6.68.22 1.3.19 1.79.11.55-.08 1.76-.72 2.01-1.42.25-.7.25-1.3.18-1.42-.08-.12-.27-.2-.57-.35Z"/></svg>
                                Hubungi Admin
                            </a>
                            <a href="{{ route('profile.edit') }}" class="w-full text-center px-6 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-bold shadow-sm hover:shadow-md transition-all">
                                ⚙️ Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="glass-panel rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 transition-all animate-slide-up hover:-translate-y-1" style="animation-delay: 100ms;">
                            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-inner">📚</div>
                            <h3 class="text-slate-500 font-bold text-sm mb-1">Total Laporanku</h3>
                            <p class="text-3xl font-black text-slate-800">{{ $pengaduans->count() }} <span class="text-sm font-semibold text-blue-500 ml-1">Laporan</span></p>
                        </div>
                        <div class="glass-panel rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-indigo-900/5 transition-all animate-slide-up hover:-translate-y-1" style="animation-delay: 200ms;">
                            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-2xl mb-4 shadow-inner">✅</div>
                            <h3 class="text-slate-500 font-bold text-sm mb-1">Laporan Selesai</h3>
                            <p class="text-3xl font-black text-slate-800">{{ $pengaduans->where('status', 'Selesai')->count() }} <span class="text-sm font-semibold text-slate-400 ml-1">Telah diperbaiki</span></p>
                        </div>
                    </div>

                    <div class="glass-panel rounded-[2rem] shadow-sm p-6 border border-slate-100 relative overflow-hidden animate-slide-up" style="animation-delay: 300ms;">
                        <header class="mb-4 flex justify-between items-center border-b border-slate-100 pb-3">
                            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">📢 Info Sekolah</h2>
                        </header>
                        <div class="space-y-3 h-32 overflow-y-auto custom-scrollbar">
                            <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50/50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0 text-xs">👨‍🏫</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Gunakan Fasilitas dengan Baik</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Mari bersama-sama menjaga kebersihan dan sarpras sekolah kita.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-slate-50/50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 shrink-0 text-xs">🎉</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Fitur Ulasan Aktif</h4>
                                    <p class="text-xs text-slate-500 font-medium mt-0.5">Sekarang kamu bisa memberikan rating pada laporan yang sudah selesai.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center md:justify-start animate-slide-up" style="animation-delay: 400ms;">
                    <div class="flex space-x-2 bg-white/60 backdrop-blur-md p-1.5 rounded-2xl shadow-sm border border-slate-200/60 w-full md:w-auto overflow-x-auto">
                        <button @click="activeTab = 'kinerja'" :class="activeTab === 'kinerja' ? 'bg-slate-800 text-white shadow-lg shadow-slate-800/20' : 'text-slate-500 hover:bg-white hover:text-slate-800'" class="px-6 py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            📂 Feed Kinerja
                        </button>
                        <button @click="activeTab = 'laporanku'" :class="activeTab === 'laporanku' ? 'bg-slate-800 text-white shadow-lg shadow-slate-800/20' : 'text-slate-500 hover:bg-white hover:text-slate-800'" class="px-6 py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                            🕒 Riwayat Pribadi
                        </button>
                    </div>
                </div>

                <div x-show="activeTab === 'kinerja'" 
                     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                     class="space-y-4 max-w-5xl mx-auto">

                    <div class="glass-panel rounded-[2rem] p-5 sm:p-6 shadow-sm border border-slate-100">
                        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 pb-5 mb-5 border-b border-slate-100">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Feed Kinerja Publik</h3>
                                <p class="text-sm text-slate-500 font-medium mt-1">Laporan berstatus <span class="font-black text-blue-600">Proses</span> dan <span class="font-black text-green-600">Selesai</span> dapat dilihat seluruh murid.</p>
                            </div>
                            <div class="text-sm font-bold text-slate-500">
                                @if($laporanKinerja->total())
                                    Menampilkan {{ $laporanKinerja->firstItem() }}-{{ $laporanKinerja->lastItem() }} dari {{ $laporanKinerja->total() }} laporan
                                @else
                                    Tidak ada laporan yang cocok dengan filter saat ini.
                                @endif
                            </div>
                        </div>

                        <form method="GET" action="{{ route('murid.dashboard') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Status Feed</label>
                                <select name="kinerja_status" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium">
                                    <option value="semua" {{ $kinerjaStatus === 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Proses" {{ $kinerjaStatus === 'Proses' ? 'selected' : '' }}>Sedang Diproses</option>
                                    <option value="Selesai" {{ $kinerjaStatus === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Urutkan</label>
                                <select name="kinerja_sort" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium">
                                    <option value="terbaru" {{ $kinerjaSort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="terlama" {{ $kinerjaSort === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Tanggal Tertentu</label>
                                <input type="date" name="kinerja_tanggal" value="{{ $kinerjaTanggal }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium">
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 xl:justify-end">
                                <button type="submit" class="w-full sm:w-auto px-5 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/15 transition-transform transform hover:-translate-y-0.5">
                                    Terapkan
                                </button>
                                <a href="{{ route('murid.dashboard') }}" class="w-full sm:w-auto px-5 py-3.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-bold border border-slate-200 shadow-sm text-center">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                    
                    @forelse($laporanKinerja as $post)
                        @php
                            $avgRating = $post->ulasans->avg('rating');
                            $reviewCount = $post->ulasans->count();
                            $isSelesai = $post->status === 'Selesai';
                        @endphp
                        
                        <div x-data="{ expanded: false }" class="glass-panel rounded-3xl shadow-sm border border-slate-200 overflow-hidden transition-all duration-300" :class="expanded ? 'ring-2 ring-blue-500/40 shadow-xl shadow-blue-900/10 my-6' : 'hover:shadow-md'">
                            
                            <button @click="expanded = !expanded" class="w-full text-left p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white/60 hover:bg-slate-50/80 transition-colors focus:outline-none">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0 transition-transform duration-500 shadow-sm border border-slate-100 bg-white" :class="expanded ? 'rotate-[-15deg] scale-110' : ''">🗂️</div>
                                    <div>
                                        <h4 class="text-lg font-extrabold text-slate-900 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $post->lokasi }}</h4>
                                        <p class="text-sm font-semibold text-slate-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                            <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md text-[11px] font-black uppercase tracking-wider border border-indigo-100">{{ $post->kategori->nama_kategori ?? 'Umum' }}</span>
                                            <span>•</span>
                                            <span>Oleh: {{ $post->user->name }}</span>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-6 w-full sm:w-auto justify-between sm:justify-end border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0">
                                    <div class="text-right flex flex-col sm:items-end gap-2">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider border shadow-sm w-max {{ $isSelesai ? 'bg-green-100 text-green-700 border-green-200' : 'bg-blue-100 text-blue-700 border-blue-200' }}">
                                            {{ $isSelesai ? 'Selesai' : 'Sedang Diproses' }}
                                        </span>
                                        @if($isSelesai)
                                            @if($reviewCount > 0)
                                                <div class="flex items-center gap-1.5 text-amber-400 text-xl drop-shadow-sm">
                                                    ★ <span class="text-slate-800 font-black text-lg ml-0.5">{{ number_format($avgRating, 1) }}</span>
                                                </div>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $reviewCount }} Ulasan Siswa</p>
                                            @else
                                                <div class="text-slate-400 font-bold text-sm bg-slate-100 px-3 py-1 rounded-full border border-slate-200">Belum ada nilai</div>
                                            @endif
                                        @else
                                            <div class="text-blue-600 font-bold text-sm bg-blue-50 px-3 py-1 rounded-full border border-blue-100">Pantau progresnya di sini</div>
                                        @endif
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 transform transition-transform duration-500" :class="expanded ? 'rotate-180 bg-blue-600 text-white shadow-md' : ''">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </button>

                            <div x-show="expanded" x-collapse x-transition class="border-t border-slate-200 bg-slate-50/50">
                                <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 bg-white/60">
                                    <div class="space-y-3">
                                        <h5 class="text-[11px] font-black uppercase text-slate-400 tracking-widest flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-red-400 animate-pulse"></span> Detail Laporan Awal
                                        </h5>
                                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
                                            <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full -z-10"></div>
                                            <p class="text-sm text-slate-700 leading-relaxed font-semibold italic">"{{ $post->keterangan }}"</p>
                                        </div>
                                        @if($post->foto)
                                            <div class="rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                                                <img src="{{ asset('storage/' . $post->foto) }}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-700">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-4 flex flex-col h-full">
                                        <h5 class="text-[11px] font-black uppercase text-slate-400 tracking-widest flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $isSelesai ? 'bg-green-400' : 'bg-blue-400' }} animate-pulse"></span>
                                            {{ $isSelesai ? 'Tindak Lanjut Sekolah' : 'Status Perkembangan' }}
                                        </h5>
                                        <div class="{{ $isSelesai ? 'bg-green-50 border-green-200' : 'bg-blue-50 border-blue-200' }} p-5 rounded-2xl border flex-1 flex flex-col relative overflow-hidden shadow-sm">
                                            <div class="absolute -bottom-4 -right-4 text-7xl opacity-10">🛠️</div>
                                            <h6 class="{{ $isSelesai ? 'text-green-800' : 'text-blue-800' }} font-extrabold mb-2 text-sm flex items-center gap-2">
                                                {{ $isSelesai ? '👨‍🏫 Respon Guru/Petugas:' : '🔵 Laporan Sedang Diproses:' }}
                                            </h6>
                                            <p class="{{ $isSelesai ? 'text-green-700' : 'text-blue-700' }} text-sm leading-relaxed font-medium flex-1">
                                                @if($post->feedback)
                                                    {{ $post->feedback }}
                                                @else
                                                    {{ $isSelesai ? 'Laporan ini telah direspon dan sarpras terkait sudah selesai diperbaiki oleh tim sekolah.' : 'Laporan ini sedang diproses oleh tim sekolah. Semua murid bisa memantau progresnya di feed ini.' }}
                                                @endif
                                            </p>
                                            @if($post->feedback_foto)
                                                <a href="{{ asset('storage/' . $post->feedback_foto) }}" target="_blank" class="mt-3 block rounded-2xl overflow-hidden border {{ $isSelesai ? 'border-green-200' : 'border-blue-200' }} bg-white shadow-sm">
                                                    <img src="{{ asset('storage/' . $post->feedback_foto) }}" alt="Foto tanggapan guru" class="w-full h-52 object-cover">
                                                </a>
                                            @endif
                                            <p class="{{ $isSelesai ? 'text-green-600 bg-green-100' : 'text-blue-600 bg-blue-100' }} text-[11px] font-black mt-4 flex items-center gap-1 w-max px-2 py-1 rounded-md">
                                                {{ $isSelesai ? '✅ Selesai:' : '🔄 Diperbarui:' }} {{ $post->updated_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        
                                        @if($isSelesai)
                                            @php $myReview = $post->ulasans->where('user_id', Auth::id())->first(); @endphp
                                            @if(!$myReview)
                                                <button @click="rateModal = true; rateData.pengaduan_id = '{{ $post->id }}'; rateData.rating = 0; rateData.komentar = ''" class="w-full py-4 bg-gradient-to-r from-amber-400 to-orange-400 hover:from-amber-500 hover:to-orange-500 text-white rounded-2xl font-extrabold shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                                                    <span class="text-2xl drop-shadow-md">⭐</span> Beri Nilai Perbaikan Ini
                                                </button>
                                            @else
                                                <div class="w-full py-3 bg-slate-100 text-slate-500 rounded-2xl font-bold border border-slate-200 flex justify-center items-center gap-2">
                                                    ✔️ Kamu sudah memberikan nilai
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full py-4 bg-blue-50 text-blue-700 rounded-2xl font-extrabold border border-blue-100 flex justify-center items-center gap-2">
                                                🔒 Ulasan tersedia setelah laporan selesai
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($isSelesai)
                                    <div class="bg-slate-100/50 border-t border-slate-200 p-6">
                                        <h5 class="text-sm font-black text-slate-800 mb-4 flex items-center justify-between">
                                            <span class="flex items-center gap-2">💬 Ulasan & Komentar Siswa Lain</span>
                                        </h5>
                                        
                                        <div class="space-y-3 max-h-72 overflow-y-auto custom-scrollbar pr-3">
                                            @forelse($post->ulasans as $ulasan)
                                                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex gap-4 transition hover:shadow-md relative">
                                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-black text-sm border border-slate-200 flex-shrink-0">
                                                        {{ substr($ulasan->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0">
                                                            <div>
                                                                <p class="text-sm font-extrabold text-slate-900">{{ $ulasan->user->name }} <span class="text-[11px] font-semibold text-slate-400 ml-2">{{ $ulasan->created_at->diffForHumans() }}</span></p>
                                                                <div class="flex text-amber-400 text-sm mt-0.5 mb-2 tracking-widest drop-shadow-sm">
                                                                    @for($i=0; $i<$ulasan->rating; $i++) ★ @endfor
                                                                    @for($i=$ulasan->rating; $i<5; $i++) <span class="text-slate-200">★</span> @endfor
                                                                </div>
                                                            </div>
                                                            @if($ulasan->user_id == Auth::id())
                                                                <div class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 absolute right-4 top-4 sm:relative sm:right-auto sm:top-auto">
                                                                    <button @click="editRateModal = true; editRateData = { id: '{{ $ulasan->id }}', rating: {{ $ulasan->rating }}, komentar: '{{ addslashes($ulasan->komentar) }}' }" class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center gap-1">✏️ Edit</button>
                                                                    <div class="w-px h-3 bg-slate-300"></div>
                                                                    <form action="{{ route('murid.ulasan.destroy', $ulasan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?');" class="inline m-0 p-0">
                                                                        @csrf @method('DELETE')
                                                                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1">🗑️</button>
                                                                    </form>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-slate-700 font-medium italic mt-1 bg-slate-50 p-3 rounded-xl border border-slate-100/50">"{{ $ulasan->komentar }}"</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center py-8 bg-white/50 border-2 border-dashed border-slate-200 rounded-2xl">
                                                    <span class="text-4xl block mb-2 opacity-60">✍️</span>
                                                    <p class="text-sm text-slate-500 font-bold">Belum ada siswa yang memberikan ulasan.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-slate-100/50 border-t border-slate-200 p-6">
                                        <div class="text-center py-8 bg-white/50 border-2 border-dashed border-blue-200 rounded-2xl">
                                            <span class="text-4xl block mb-2 opacity-60">🔄</span>
                                            <p class="text-sm text-slate-500 font-bold">Ulasan akan muncul setelah laporan selesai diproses.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 glass-panel rounded-[3rem] border border-slate-200 shadow-sm animate-slide-up">
                            <span class="text-6xl block mb-4 animate-bounce">📭</span>
                            <h3 class="text-xl font-extrabold text-slate-800 mb-1">Belum Ada Laporan yang Cocok</h3>
                            <p class="text-slate-500 font-medium text-sm">Coba ubah filter status atau tanggal untuk melihat feed kinerja lainnya.</p>
                        </div>
                    @endforelse

                    @if($laporanKinerja->hasPages())
                        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 glass-panel rounded-2xl border border-slate-100 p-5 shadow-sm">
                            <div class="text-sm text-slate-600 font-medium">
                                Menampilkan <span class="font-bold">{{ $laporanKinerja->firstItem() }}</span> - <span class="font-bold">{{ $laporanKinerja->lastItem() }}</span> dari <span class="font-bold">{{ $laporanKinerja->total() }}</span> laporan
                            </div>
                            <div class="flex justify-center">
                                {{ $laporanKinerja->links() }}
                            </div>
                        </div>
                    @endif
                </div>

                <div x-show="activeTab === 'laporanku'" 
                     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" 
                     style="display: none;" class="max-w-5xl mx-auto">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($pengaduans as $item)
                            <div class="glass-panel rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 flex flex-col overflow-hidden border border-white group">
                                <div class="relative h-52 bg-slate-100 overflow-hidden">
                                    @if($item->foto) 
                                        <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700" src="{{ asset('storage/' . $item->foto) }}">
                                    @else 
                                        <div class="w-full h-full flex items-center justify-center bg-slate-200/50 text-slate-400 font-bold text-sm">📷 Tanpa Foto</div> 
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent pointer-events-none"></div>
                                    <div class="absolute top-4 right-4 z-10">
                                        @if($item->status == 'Selesai') 
                                            <span class="bg-green-500 text-white px-3 py-1.5 rounded-xl text-xs font-black shadow-lg shadow-green-500/30 border border-green-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-white"></span> Selesai</span>
                                        @elseif($item->status == 'Proses') 
                                            <span class="bg-blue-500 text-white px-3 py-1.5 rounded-xl text-xs font-black shadow-lg shadow-blue-500/30 border border-blue-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Proses</span>
                                        @else 
                                            <span class="bg-amber-400 text-white px-3 py-1.5 rounded-xl text-xs font-black shadow-lg shadow-amber-400/30 border border-amber-300 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-white"></span> Menunggu</span> 
                                        @endif
                                    </div>
                                    <div class="absolute bottom-3 left-4 text-white text-xs font-bold drop-shadow-md">
                                        📅 {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col bg-white/60">
                                    <h4 class="font-extrabold text-lg text-slate-900 mb-2 flex items-start gap-1.5">
                                        <span class="text-red-500 shrink-0">📍</span> <span class="line-clamp-1">{{ $item->lokasi }}</span>
                                    </h4>
                                    <p class="text-slate-500 text-sm mb-4 line-clamp-2 leading-relaxed font-medium">{{ $item->keterangan }}</p>
                                    
                                    @if($item->status != 'Menunggu' && ($item->feedback || $item->feedback_foto))
                                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-blue-600 font-bold text-sm">💬 Respon Guru</span>
                                                <span class="text-xs text-blue-500 font-medium">{{ $item->updated_at->diffForHumans() }}</span>
                                            </div>
                                            @if($item->feedback)
                                                <p class="text-slate-700 text-sm font-medium leading-relaxed">{{ $item->feedback }}</p>
                                            @endif
                                            @if($item->feedback_foto)
                                                <a href="{{ asset('storage/' . $item->feedback_foto) }}" target="_blank" class="inline-block mt-2">
                                                    <img src="{{ asset('storage/' . $item->feedback_foto) }}" alt="Foto respon guru" class="w-20 h-20 object-cover rounded-lg border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="mt-auto pt-4 border-t border-slate-100">
                                        <span class="inline-flex items-center text-[10px] font-black uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-1 rounded-md">
                                            Kategori: {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center glass-panel rounded-[3rem] border border-slate-200">
                                <span class="text-5xl block mb-4">📝</span>
                                <h3 class="text-xl font-extrabold text-slate-800 mb-1">Riwayat Kosong</h3>
                                <p class="text-slate-500 font-medium text-sm">Kamu belum pernah membuat laporan kerusakan.</p>
                                <button @click="openModal = true" class="mt-6 px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold transition-colors">Buat Laporan Sekarang</button>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <div x-cloak x-show="openModal" class="fixed inset-0 z-[100] flex items-start sm:items-center justify-center p-4 sm:p-6 overflow-y-auto">
            <div x-show="openModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div x-show="openModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]">
                
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Buat Laporan Baru</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Laporkan kerusakan sarpras dengan detail.</p>
                    </div>
                    <button @click="openModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <div x-data="{ 
    num1: Math.floor(Math.random() * 10) + 1, 
    num2: Math.floor(Math.random() * 10) + 1, 
    captchaInput: '', 
    captchaError: false,
    showConfirmModal: false,
    
    checkCaptcha() {
        // Jika jawaban salah
        if (parseInt(this.captchaInput) !== (this.num1 + this.num2)) {
            this.captchaError = true;
            this.num1 = Math.floor(Math.random() * 10) + 1; // Reset soal
            this.num2 = Math.floor(Math.random() * 10) + 1;
            this.captchaInput = ''; // Kosongkan input
            return false;
        }
        this.captchaError = false;
        return true;
    },

    attemptSubmit() {
        // Jika Captcha benar, munculkan Modal Konfirmasi
        if (this.checkCaptcha()) {
            this.showConfirmModal = true;
        }
    },

    submitForm() {
        // Eksekusi pengiriman formulir sebenarnya
        document.getElementById('formPengaduan').submit();
    }
}">

    <form id="formPengaduan" action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" @submit.prevent="attemptSubmit">
        @csrf

        <div>
            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Kategori Kerusakan <span class="text-red-500">*</span></label>
            <select name="kategori_id" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium" required>
                <option value="" disabled selected>-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Lokasi Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="lokasi" placeholder="Contoh: Lab Komputer 2, Meja No 5" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium transition-shadow" required>
        </div>

        <div>
            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Detail Kerusakan <span class="text-red-500">*</span></label>
            <textarea name="keterangan" rows="4" placeholder="Jelaskan secara detail kerusakan yang terjadi..." class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-medium transition-shadow resize-none" required></textarea>
        </div>

        <div>
            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Bukti Foto <span class="text-slate-400 font-normal">(Opsional)</span></label>
            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 focus:outline-none transition-all">
            <p class="text-xs text-slate-500 mt-2 font-medium">* Format JPG/PNG, maksimal 2MB.</p>
        </div>

        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100">
            <label class="block text-sm font-extrabold text-slate-800 mb-2">Verifikasi Keamanan <span class="text-red-500">*</span></label>
            <p class="text-xs text-slate-500 mb-3 font-medium">Berapakah hasil penjumlahan dari angka berikut?</p>
            
            <div class="flex items-center gap-4">
                <div class="bg-white px-6 py-3.5 rounded-xl font-extrabold text-blue-700 border-2 border-blue-200 text-xl tracking-wider select-none shadow-sm flex items-center justify-center min-w-[120px]" x-text="num1 + ' + ' + num2 + ' = ?'"></div>
                
                <input type="number" x-model="captchaInput" placeholder="Jawaban Anda" class="flex-1 px-5 py-3.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 font-bold transition-shadow text-lg" required>
            </div>
            
            <p x-show="captchaError" class="text-red-500 text-sm mt-3 font-bold flex items-center gap-1.5" x-cloak>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Jawaban salah! Silakan hitung ulang.
            </p>
        </div>

        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-lg">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
    Kirim Laporan
</button>
    </form>

    <div x-show="showConfirmModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
        <div x-show="showConfirmModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <div x-show="showConfirmModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="relative bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full border border-slate-100 text-center">
            
            <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-800 mb-2">Kirim Laporan Sekarang?</h3>
            <p class="text-slate-500 font-medium mb-8 leading-relaxed">Pastikan lokasi, kategori, dan keterangan yang Anda isi sudah benar. Laporan yang sudah dikirim tidak dapat diubah.</p>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" @click="showConfirmModal = false" class="w-full py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Periksa Kembali</button>
                <button type="button" @click="submitForm()" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">Ya, Kirim Laporan</button>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="rateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div x-show="rateModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="rateModal = false"></div>
            
            <div x-show="rateModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                 class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-8 relative z-10 text-center border border-slate-100">
                
                <button @click="rateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-full transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 text-4xl shadow-inner border border-amber-200 animate-bounce">🌟</div>
                <h3 class="text-2xl font-black text-slate-900 mb-1">Berikan Penilaianmu</h3>
                <p class="text-sm text-slate-500 font-medium mb-6">Bagaimana hasil perbaikan sarpras ini?</p>
                
                <form :action="'/murid/pengaduan/' + rateData.pengaduan_id + '/ulasan'" method="POST">
                    @csrf
                    
                    <div class="flex justify-center gap-2 mb-8" @mouseleave="rateData.hover = 0">
                        <template x-for="star in 5">
                            <button type="button" @click="rateData.rating = star" @mouseover="rateData.hover = star" 
                                    class="text-5xl transition-all duration-200 focus:outline-none transform hover:scale-125" 
                                    :class="(rateData.hover >= star || rateData.rating >= star) ? 'text-amber-400 drop-shadow-md' : 'text-slate-200'">
                                ★
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="rateData.rating" required>
                    
                    <textarea name="komentar" rows="3" placeholder="Tuliskan ulasan jujurmu di sini..." class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl mb-6 focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition-shadow resize-none font-medium placeholder:text-slate-400 text-sm" required></textarea>
                    
                    <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/20 transition-all transform hover:-translate-y-0.5">
                        Kirim Ulasan Sekarang
                    </button>
                </form>
            </div>
        </div>

        <div x-cloak x-show="editRateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div x-show="editRateModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="editRateModal = false"></div>
            
            <div x-show="editRateModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md p-8 relative z-10 text-center border border-slate-100">
                
                <button @click="editRateModal = false" class="absolute top-5 right-5 text-slate-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-full transition-colors focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl shadow-inner border border-blue-200">✏️</div>
                <h3 class="text-2xl font-black text-slate-900 mb-6">Edit Ulasan</h3>
                
                <form :action="'/murid/ulasan/' + editRateData.id" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="flex justify-center gap-2 mb-8" @mouseleave="editRateData.hover = 0">
                        <template x-for="star in 5">
                            <button type="button" @click="editRateData.rating = star" @mouseover="editRateData.hover = star" 
                                    class="text-5xl transition-all duration-200 focus:outline-none transform hover:scale-125" 
                                    :class="(editRateData.hover >= star || editRateData.rating >= star) ? 'text-amber-400 drop-shadow-md' : 'text-slate-200'">
                                ★
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="editRateData.rating" required>
                    
                    <textarea name="komentar" x-model="editRateData.komentar" rows="3" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl mb-6 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow resize-none font-medium text-sm" required></textarea>
                    
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
