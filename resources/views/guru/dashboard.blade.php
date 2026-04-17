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

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    @php
        $adminWaNumber = '6282119096623';
        $adminWaMessage = rawurlencode('Halo Admin SMK Mutiara, saya ingin melaporkan gangguan, kesalahan, atau respon yang lambat pada aplikasi pengaduan.');
    @endphp

    <div class="relative min-h-screen bg-slate-50 overflow-hidden font-sans selection:bg-indigo-200 selection:text-indigo-900 pb-20"
         x-data="{ 
            openModal: false, 
            profileModal: false, 
            selectedData: { id: '', status: '', feedback: '', feedback_foto: '' }, 
            studentProfile: { name: '', nis: '', kelas: '', jurusan: '', hp: '', avatar: '' },
            validationError: '',
            validateForm() {
                this.validationError = '';
                if ((this.selectedData.status === 'Proses' || this.selectedData.status === 'Selesai') && (!this.selectedData.feedback || this.selectedData.feedback.trim() === '')) {
                    this.validationError = 'Feedback wajib diisi ketika status diubah ke Proses atau Selesai.';
                    return false;
                }
                return true;
            }
         }">
        
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-indigo-300/20 rounded-full blur-[120px] animate-float"></div>
            <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-purple-300/20 rounded-full blur-[100px] animate-float-delayed"></div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center w-full relative z-10">
                <h2 class="font-extrabold text-2xl text-slate-800 flex items-center gap-3 tracking-tight">
                    <span class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-2.5 rounded-xl shadow-lg shadow-indigo-500/30 text-base transform hover:scale-110 transition duration-300">👨‍🏫</span> 
                    Meja Kerja Guru & Teknisi
                </h2>

                <div class="flex items-center gap-3">
                    <a href="https://wa.me/{{ $adminWaNumber }}?text={{ $adminWaMessage }}" target="_blank" class="px-4 py-2.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A11.8 11.8 0 0 0 12.01 0C5.43 0 .08 5.34.08 11.92c0 2.1.55 4.14 1.6 5.94L0 24l6.32-1.66a11.9 11.9 0 0 0 5.69 1.45h.01c6.57 0 11.91-5.34 11.91-11.92 0-3.18-1.24-6.17-3.41-8.39ZM12.01 21.5h-.01a9.6 9.6 0 0 1-4.89-1.34l-.35-.2-3.75.99.99-3.65-.23-.37a9.58 9.58 0 0 1-1.47-5.11c0-5.31 4.32-9.63 9.64-9.63 2.57 0 4.98 1 6.8 2.82a9.55 9.55 0 0 1 2.81 6.81c0 5.32-4.31 9.68-9.54 9.68Zm5.57-7.19c-.3-.15-1.76-.87-2.03-.97-.27-.1-.46-.15-.65.15s-.75.97-.92 1.17-.34.23-.63.08a8.03 8.03 0 0 1-2.37-1.46 8.94 8.94 0 0 1-1.65-2.05c-.17-.3-.02-.46.13-.61.13-.13.3-.34.45-.51.15-.17.2-.29.3-.49.1-.2.05-.38-.02-.53-.07-.15-.65-1.56-.9-2.14-.24-.57-.49-.49-.65-.5-.17-.01-.36-.01-.55-.01s-.5.07-.76.38-.98.96-.98 2.34.99 2.72 1.13 2.91c.15.2 1.97 3.02 4.77 4.24.67.29 1.2.47 1.61.6.68.22 1.3.19 1.79.11.55-.08 1.76-.72 2.01-1.42.25-.7.25-1.3.18-1.42-.08-.12-.27-.2-.57-.35Z"/></svg>
                        Hubungi Admin
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-white text-red-500 hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </x-slot>

        <div class="py-8 relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         x-transition class="animate-slide-up bg-white border-l-4 border-green-500 px-6 py-4 rounded-2xl shadow-xl shadow-green-500/10 flex items-center gap-4 relative z-50">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-inner border border-green-200">✅</div> 
                        <span class="font-bold text-slate-800">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="glass-panel rounded-[2rem] shadow-xl shadow-indigo-900/5 p-8 border border-white relative overflow-hidden animate-slide-up">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-indigo-200 to-transparent rounded-bl-full -z-10 opacity-60"></div>
                    
                    <div class="flex flex-col md:flex-row items-center gap-6 justify-between">
                        <div class="flex items-center gap-6">
                            <div class="relative group shrink-0">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="w-20 h-20 rounded-[1.5rem] rotate-3 hover:rotate-0 object-cover border-4 border-white shadow-lg shadow-indigo-500/30 transition-all duration-300">
                                @else
                                    <div class="w-20 h-20 rounded-[1.5rem] rotate-3 hover:rotate-0 border-4 border-white bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-indigo-500/30 transition-all duration-300">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-center md:text-left">
                                <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Selamat Bertugas, {{ Auth::user()->name }}!</h3>
                                <p class="text-slate-500 font-medium">NIP/NIK: <span class="text-indigo-600 font-bold">{{ Auth::user()->nis_nip ?? '-' }}</span> | Berikut adalah daftar laporan kerusakan dari siswa.</p>
                            </div>
                        </div>
                        <div class="hidden md:flex w-16 h-16 bg-indigo-50 rounded-2xl items-center justify-center text-3xl shadow-inner border border-indigo-100 transform hover:scale-110 transition duration-300 rotate-12">
                            🛠️
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-[2rem] shadow-lg border border-slate-100 p-6 animate-slide-up" style="animation-delay: 80ms;">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-xl font-black text-slate-900">Filter Laporan</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Saring laporan berdasarkan status, kategori, tanggal, atau kata kunci.</p>
                        </div>
                        <div class="text-sm font-bold text-slate-500">
                            Menampilkan {{ count($pengaduans) }} laporan
                        </div>
                    </div>

                    <form method="GET" action="{{ route('guru.dashboard') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Cari</label>
                            <input type="text" name="search" value="{{ $searchFilter }}" placeholder="Nama siswa, lokasi, atau isi tanggapan" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Status</label>
                            <select name="status" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                <option value="semua" {{ $statusFilter === 'semua' ? 'selected' : '' }}>Semua Status</option>
                                <option value="Menunggu" {{ $statusFilter === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Proses" {{ $statusFilter === 'Proses' ? 'selected' : '' }}>Proses</option>
                                <option value="Selesai" {{ $statusFilter === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Kategori</label>
                            <select name="kategori_id" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ (string) $kategoriFilter === (string) $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ $tanggalFilter }}" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Urutkan</label>
                            <select name="sort" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                <option value="terbaru" {{ $sortFilter === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ $sortFilter === 'terlama' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 xl:col-span-5 flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/10 transition-transform transform hover:-translate-y-0.5">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('guru.dashboard') }}" class="px-6 py-3.5 bg-white hover:bg-slate-50 text-slate-700 rounded-xl font-bold border border-slate-200 shadow-sm text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="glass-panel rounded-[2rem] shadow-lg border border-slate-100 overflow-hidden animate-slide-up" style="animation-delay: 100ms;">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="bg-slate-50/80 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-5 whitespace-nowrap">Data Pelapor</th>
                                    <th class="px-6 py-5">Masalah & Lokasi</th>
                                    <th class="px-6 py-5 text-center whitespace-nowrap">Bukti Foto</th>
                                    <th class="px-6 py-5 text-center">Status & Penilaian</th>
                                    <th class="px-6 py-5 text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white/40">
                                @forelse($pengaduans as $item)
                                <tr class="hover:bg-slate-50/80 transition duration-200 group">
                                    <td class="px-6 py-5 align-top">
                                        <button type="button" 
                                                @click='profileModal = true; studentProfile = @js([
                                                    "name" => $item->user->name,
                                                    "nis" => $item->user->nis_nip ?? "-",
                                                    "kelas" => $item->user->kelas ?? "-",
                                                    "jurusan" => $item->user->jurusan ?? "Umum",
                                                    "hp" => $item->user->no_telp ?? "Tidak ada",
                                                    "avatar" => $item->user->avatar ? asset("storage/" . $item->user->avatar) : ""
                                                ])' 
                                                class="font-extrabold text-indigo-600 hover:text-indigo-800 text-left flex items-center gap-2 transition-colors group-hover:translate-x-1 duration-300">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs shadow-inner">👤</div>
                                            <span class="hover:underline">{{ $item->user->name }}</span>
                                        </button>
                                        <div class="text-xs font-bold text-slate-500 mt-2 ml-10 flex items-center gap-1.5">
                                            <span class="bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">{{ $item->user->kelas ?? '-' }} {{ $item->user->jurusan ?? '-' }}</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1.5 ml-10 font-medium tracking-wide">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border border-indigo-100">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                        <div class="font-extrabold text-slate-900 mt-2 text-base flex items-start gap-1">
                                            <span class="text-red-500 mt-0.5">📍</span> {{ $item->lokasi }}
                                        </div>
                                        <div class="text-sm text-slate-600 mt-1.5 leading-relaxed font-medium bg-white/50 p-2 rounded-lg border border-slate-100 italic">"{{ $item->keterangan }}"</div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-middle">
                                        @if($item->foto) 
                                            <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="inline-flex flex-col items-center gap-1 p-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl font-bold text-xs text-slate-600 transition-all hover:shadow-md hover:-translate-y-0.5 group/foto">
                                                <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100">
                                                    <img src="{{ asset('storage/' . $item->foto) }}" class="w-full h-full object-cover group-hover/foto:scale-110 transition-transform duration-500">
                                                </div>
                                                Lihat Foto
                                            </a>
                                        @else 
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-400 text-xs font-bold rounded-lg border border-slate-100">
                                                🚫 Tanpa Foto
                                            </span> 
                                        @endif
                                    </td>
<td class="px-6 py-5 text-center align-middle">
    {{-- 1. TAMPILAN BADGE STATUS --}}
    @if($item->status == 'Menunggu') 
        <span class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-1 w-max mx-auto"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu</span>
    @elseif($item->status == 'Proses') 
        <span class="bg-blue-100 border border-blue-200 text-blue-700 px-3 py-1.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-1 w-max mx-auto"><span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Proses</span>
    @else 
        <span class="bg-green-100 border border-green-200 text-green-700 px-3 py-1.5 rounded-xl text-xs font-black shadow-sm flex items-center justify-center gap-1 w-max mx-auto"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai</span> 
    @endif
    
    {{-- 2. TOMBOL & DAFTAR ULASAN (DESAIN PROFESIONAL) --}}
    @if($item->status == 'Selesai')
        <div x-data="{ openReviews: false }" class="mt-3 relative w-full flex flex-col items-center">
            
            @if($item->ulasans->count() > 0)
                {{-- Tombol Trigger yang Lebih Elegan --}}
                <button @click="openReviews = !openReviews" 
                        class="group inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-white border border-slate-200 hover:border-blue-400 hover:bg-blue-50/50 text-slate-600 hover:text-blue-700 rounded-xl text-[11px] font-bold transition-all duration-200 shadow-sm mx-auto focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <span class="flex items-center gap-1.5">
                        <span class="text-amber-400 text-sm drop-shadow-sm">★</span>
                        <span>Lihat Ulasan ({{ $item->ulasans->count() }})</span>
                    </span>
                    <svg :class="{'rotate-180 text-blue-500': openReviews}" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                {{-- Dropdown Container (Desain Card Modern) --}}
                <div x-show="openReviews" 
                     x-transition:enter="transition ease-out duration-300 origin-top" 
                     x-transition:enter-start="opacity-0 scale-y-90 -translate-y-2" 
                     x-transition:enter-end="opacity-100 scale-y-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-200 origin-top" 
                     x-transition:leave-start="opacity-100 scale-y-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 scale-y-90 -translate-y-2"
                     x-cloak
                     class="mt-2 w-full max-w-[280px] bg-white border border-slate-200 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden text-left z-10 relative">
                    
                    {{-- Header Dropdown --}}
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Detail Penilaian</p>
                        <p class="text-[10px] font-bold text-slate-400">{{ $item->ulasans->count() }} Review</p>
                    </div>

                    {{-- Scrollable Area ulasan --}}
                    <div class="p-2 space-y-1 max-h-[180px] overflow-y-auto custom-scrollbar">
                        @foreach($item->ulasans as $ulasan)
                        <div class="p-2.5 hover:bg-slate-50 rounded-xl transition-colors border border-transparent hover:border-slate-100 group/item">
                            <div class="flex items-start gap-3">
                                {{-- Avatar Inisial Bulat --}}
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-700 flex items-center justify-center text-xs font-black shrink-0 border border-blue-200/50 shadow-inner">
                                    {{ strtoupper(substr($ulasan->user->name ?? 'S', 0, 1)) }}
                                </div>
                                
                                {{-- Konten Ulasan --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <p class="text-[11px] font-extrabold text-slate-800 truncate pr-2">{{ $ulasan->user->name ?? 'Siswa' }}</p>
                                        <div class="text-amber-400 text-[10px] flex shrink-0 drop-shadow-sm" title="{{ $ulasan->rating }} Bintang">
                                            @for($i=0; $i<$ulasan->rating; $i++) ★ @endfor
                                        </div>
                                    </div>
                                    <p class="text-[11px] text-slate-600 font-medium leading-relaxed italic line-clamp-3">
                                        "{{ $ulasan->komentar }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                        {{-- Garis Pemisah jika ada lebih dari 1 ulasan --}}
                        @if(!$loop->last) <hr class="border-slate-100 mx-3 my-0.5"> @endif
                        @endforeach
                    </div>
                </div>
            @else
                {{-- Tampilan Menunggu (Empty State yang lebih rapi) --}}
                <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-200 border-dashed text-slate-400 rounded-lg text-[10px] font-bold mt-2 mx-auto">
                    <svg class="w-3.5 h-3.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Menunggu Penilaian
                </div>
            @endif
        </div>
    @endif
</td>
                                    <td class="px-6 py-5 text-center align-middle">
                                        <button @click="openModal = true; selectedData = { id: '{{ $item->id }}', status: '{{ $item->status }}', feedback: '{{ addslashes($item->feedback ?? '') }}', feedback_foto: '{{ $item->feedback_foto ? asset('storage/' . $item->feedback_foto) : '' }}' }" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-5 py-2.5 rounded-xl text-xs font-extrabold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5 whitespace-nowrap flex items-center justify-center gap-1.5 mx-auto">
                                            ✏️ Tanggapi
                                        </button>
                                        @if($item->feedback || $item->feedback_foto)
                                            <div class="mt-3 flex flex-col items-center gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-full border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Balasan tersimpan
                                                </span>
                                                @if($item->feedback_foto)
                                                    <a href="{{ asset('storage/' . $item->feedback_foto) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-600 text-[10px] font-bold rounded-xl border border-slate-200 transition">
                                                        📷 Foto tanggapan
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-slate-500 font-bold bg-white/50 border-t border-slate-100">
                                        <span class="text-5xl block mb-4 animate-bounce">🎉</span>
                                        <p class="text-lg text-slate-800 font-extrabold">Bagus! Belum ada laporan kerusakan.</p>
                                        <p class="text-sm font-medium mt-1">Semua fasilitas sekolah dalam keadaan baik-baik saja.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-slate-600 font-medium">
                            Menampilkan <span class="font-bold">{{ $pengaduans->firstItem() }}</span> - <span class="font-bold">{{ $pengaduans->lastItem() }}</span> dari <span class="font-bold">{{ $pengaduans->total() }}</span> laporan
                        </div>
                        <div class="flex justify-center w-full md:w-auto">
                            {{ $pengaduans->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Ubah Status --}}
        <div x-cloak x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div @click="openModal = false" x-show="openModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            
            <div x-show="openModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative z-10 overflow-y-auto max-h-[90vh] border border-slate-100">
                
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Proses Laporan</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Ubah status dan berikan catatan perbaikan.</p>
                    </div>
                    <button @click="openModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form :action="'/guru/pengaduan/' + selectedData.id" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" @submit="return validateForm()">
                    @csrf 
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-extrabold text-slate-700 mb-2">Ubah Status Perbaikan</label>
                        <select name="status" x-model="selectedData.status" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-bold text-slate-700 transition-shadow">
                            <option value="Menunggu">🟠 Menunggu Antrean</option>
                            <option value="Proses">🔵 Sedang Dikerjakan / Diperbaiki</option>
                            <option value="Selesai">🟢 Telah Selesai Diperbaiki</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-extrabold text-slate-700 mb-2">Catatan / Pesan untuk Siswa</label>
                        <textarea name="feedback" x-model="selectedData.feedback" rows="4" placeholder="Ketikkan tindak lanjut yang telah dilakukan..." class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium text-slate-700 transition-shadow resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-extrabold text-slate-700 mb-2">Foto Tanggapan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="file" name="feedback_foto" accept="image/*" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200 focus:outline-none transition-all">
                        <p class="text-xs text-slate-500 mt-2 font-medium">* JPG, PNG, atau WEBP. Maksimal 3MB.</p>
                    </div>

                    <template x-if="selectedData.feedback_foto">
                        <a :href="selectedData.feedback_foto" target="_blank" class="block rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-white">
                            <img :src="selectedData.feedback_foto" alt="Foto tanggapan saat ini" class="w-full h-44 object-cover">
                        </a>
                    </template>
                    
                    <div class="pt-2">
                        <p x-show="validationError" x-text="validationError" class="text-red-500 text-sm font-bold mb-3"></p>
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-extrabold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5">
                            Simpan Pembaruan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Profil Siswa --}}
        <div x-cloak x-show="profileModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div @click="profileModal = false" x-show="profileModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            
            <div x-show="profileModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-sm relative z-10 overflow-hidden border border-slate-100">
                
                <div class="h-32 bg-gradient-to-br from-indigo-500 via-purple-500 to-indigo-600 relative">
                    <button @click="profileModal = false" class="absolute top-4 right-4 text-white/80 hover:text-white bg-black/20 hover:bg-black/40 w-8 h-8 rounded-full flex items-center justify-center font-bold transition backdrop-blur-sm">&times;</button>
                </div>
                
                <div class="px-8 pb-8 relative text-center">
                    
                    <div class="w-28 h-28 bg-white rounded-[1.5rem] border-4 border-white shadow-xl absolute -top-16 left-1/2 transform -translate-x-1/2 flex items-center justify-center text-4xl font-black overflow-hidden z-20">
                        <template x-if="studentProfile.avatar">
                            <img :src="studentProfile.avatar" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!studentProfile.avatar">
                            <div class="w-full h-full bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center text-white">
                                <span x-text="studentProfile.name.charAt(0)"></span>
                            </div>
                        </template>
                    </div>
                    
                    <div class="pt-16">
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">Identitas Pelapor</p>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight leading-tight" x-text="studentProfile.name"></h3>
                        <p class="text-sm font-bold text-slate-500 mt-1" x-text="'NIS: ' + studentProfile.nis"></p>
                        
                        <div class="space-y-3 mt-6 text-left">
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl shrink-0">🎓</div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Tingkat Kelas</p>
                                    <p class="text-sm font-bold text-slate-800 mt-0.5" x-text="studentProfile.kelas"></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 text-xl shrink-0">💻</div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Program / Jurusan</p>
                                    <p class="text-sm font-bold text-slate-800 mt-0.5" x-text="studentProfile.jurusan"></p>
                                </div>
                            </div>
                            
                            <a x-show="studentProfile.hp && studentProfile.hp !== 'Tidak ada'" 
                               :href="'https://wa.me/' + studentProfile.hp.replace(/[^0-9]/g, '')" 
                               target="_blank" 
                               class="flex items-center gap-4 p-4 bg-green-50 hover:bg-green-100 rounded-2xl border border-green-200 transition group cursor-pointer mt-2">
                                <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center text-white text-xl shrink-0 group-hover:scale-110 transition-transform">📞</div>
                                <div>
                                    <p class="text-[10px] font-black text-green-600/70 uppercase tracking-wider">Hubungi via WhatsApp</p>
                                    <p class="text-sm font-bold text-green-700 mt-0.5" x-text="studentProfile.hp"></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
