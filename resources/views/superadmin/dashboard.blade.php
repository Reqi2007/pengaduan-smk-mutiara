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

        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    {{-- WRAPPER UTAMA: Mengatur Modal, Role di Modal, dan Tab Aktif --}}
    <div class="relative min-h-screen bg-slate-50 overflow-hidden font-sans selection:bg-slate-200 selection:text-slate-900 pb-20" 
         x-data="{
            openModal: false,
            editModal: false,
            activeTab: '{{ $view }}',
            roleMode: 'murid',
            studentClass: '',
            studentJurusan: '',
            editRoleMode: 'murid',
            editStudentClass: '',
            editStudentJurusan: '',
            editingUser: { id: null, name: '', email: '', role: 'murid', nis_nip: '', kelas: '', jurusan: '' },
            openEditModal(user) {
                this.editingUser = {
                    id: user.id,
                    name: user.name ?? '',
                    email: user.email ?? '',
                    role: user.role ?? 'murid',
                    nis_nip: user.nis_nip ?? '',
                    kelas: user.kelas ?? '',
                    jurusan: user.jurusan ?? ''
                };
                this.editRoleMode = this.editingUser.role ?? 'murid';
                this.editStudentClass = (this.editingUser.kelas ?? '').toString();
                this.editStudentJurusan = (this.editingUser.jurusan ?? '').toString();
                this.editModal = true;
            },
            sanitizeIdentity(event, role) {
                const max = role === 'murid' ? 8 : 10;
                event.target.value = event.target.value.replace(/[^0-9]/g, '').slice(0, max);
            }
         }">
        
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-slate-300/30 rounded-full blur-[120px] animate-float"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[400px] h-[400px] bg-amber-200/20 rounded-full blur-[100px] animate-float-delayed"></div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center w-full relative z-10">
                <h2 class="font-extrabold text-2xl text-slate-800 flex items-center gap-3 tracking-tight">
                    <span class="bg-gradient-to-br from-slate-700 to-slate-900 text-white p-2.5 rounded-xl shadow-lg shadow-slate-900/30 text-base transform hover:scale-110 transition duration-300">🛡️</span> 
                    Pusat Kendali Admin
                </h2>

                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 bg-white text-red-500 hover:bg-red-500 hover:text-white border border-red-100 hover:border-red-500 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </x-slot>

        <div class="py-8 relative z-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                {{-- ALERT SUKSES --}}
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 7000)" class="animate-slide-up bg-white border-l-4 border-green-500 px-6 py-4 rounded-2xl shadow-xl shadow-green-500/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-inner">✅</div>
                            <span class="font-bold text-slate-800">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 font-bold text-2xl">&times;</button>
                    </div>
                @endif

                {{-- ALERT ERROR --}}
                @if ($errors->any())
                    <div class="animate-slide-up bg-white border-l-4 border-red-500 text-slate-700 px-6 py-4 rounded-2xl shadow-xl shadow-red-500/10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-lg shadow-inner">⚠️</div>
                            <p class="font-extrabold text-slate-800 text-lg">Gagal Memproses Data:</p>
                        </div>
                        <ul class="list-disc pl-14 font-semibold text-sm text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- 1. BAGIAN PERMOHONAN RESET SANDI --}}
                <div class="glass-panel rounded-[2rem] shadow-xl shadow-slate-900/5 p-6 border border-white relative overflow-hidden animate-slide-up" style="animation-delay: 100ms;">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/20 rounded-bl-full blur-2xl -z-10"></div>

                    <div class="flex items-center gap-4 mb-6 px-2">
                        <div class="p-3.5 bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 rounded-2xl shadow-inner border border-amber-300/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Permohonan Reset Sandi</h3>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">Persetujuan admin untuk akun yang lupa password.</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar bg-white/40 rounded-2xl border border-slate-100">
                        <table class="w-full text-sm text-left">
                            <thead class="text-[11px] text-slate-500 font-extrabold uppercase bg-slate-50/80 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Identitas Akun</th>
                                    <th class="px-6 py-4">Waktu Pengajuan</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($resetRequests as $request)
                                <tr class="hover:bg-amber-50/40 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($request->user->avatar)
                                                <img src="{{ asset('storage/' . $request->user->avatar) }}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-black shrink-0">
                                                    {{ substr($request->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-extrabold text-slate-800 text-base">{{ $request->user->name }}</div>
                                                <div class="text-xs font-medium text-slate-500 mt-0.5">{{ $request->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            ⏳ {{ $request->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex justify-center gap-2">
                                        <form action="{{ route('superadmin.reset.reject', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak permohonan ini?');" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-xl transition duration-200" title="Tolak & Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('superadmin.reset.approve', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset password user ini?');" class="m-0">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition transform hover:-translate-y-0.5 shadow-md shadow-amber-500/30 text-xs">
                                                Setujui & Reset
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">
                                        <div class="text-4xl mb-3 opacity-50">✨</div>
                                        Tidak ada permohonan reset sandi saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 2. BAGIAN MANAJEMEN PENGGUNA --}}
                <div class="animate-slide-up" style="animation-delay: 200ms;">
                    
                    {{-- HEADER DAN TOMBOL AKSI --}}
                    <div class="glass-panel rounded-[2rem] p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-3.5 bg-gradient-to-br from-slate-700 to-slate-900 text-white rounded-2xl shadow-inner border border-slate-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h3>
                                <p class="text-sm text-slate-500 font-medium mt-0.5">Kelola seluruh akses akun guru dan murid.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                            <a href="{{ route('superadmin.laporan') }}" target="_blank" class="flex-1 md:flex-none justify-center px-6 py-3 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-extrabold shadow-sm hover:border-slate-300 hover:bg-slate-50 transition-all flex items-center gap-2 group">
                                <span class="group-hover:-translate-y-0.5 transition-transform">🖨️</span> Cetak PDF
                            </a>
                            <button @click="openModal = true; roleMode = 'murid'; studentClass = ''; studentJurusan = ''" class="flex-1 md:flex-none justify-center px-6 py-3 bg-gradient-to-r from-slate-800 to-slate-900 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/20 hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Akun
                            </button>
                        </div>
                    </div>

                    {{-- KOTAK DAFTAR AKUN --}}
                    <div class="bg-white/90 backdrop-blur-md border border-slate-200 rounded-3xl shadow-sm overflow-hidden mt-6">
                        
                        {{-- NAVIGASI TAB --}}
                        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50 flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Daftar Akun Pengguna</h3>
                                    <p class="text-xs font-medium text-slate-500 mt-1">Kelola, sortir, dan audit akses siswa maupun guru dari satu tempat.</p>
                                </div>
                                <div class="text-xs font-semibold text-slate-500">Total: {{ $counts['semua'] }} akun</div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Total Akun</p>
                                    <p class="mt-2 text-2xl font-black text-slate-900">{{ $counts['semua'] }}</p>
                                </div>
                                <div class="rounded-2xl border border-blue-100 bg-blue-50/70 px-4 py-3 shadow-sm">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-blue-500">Siswa</p>
                                    <p class="mt-2 text-2xl font-black text-blue-700">{{ $counts['murid'] }}</p>
                                </div>
                                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 px-4 py-3 shadow-sm">
                                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-emerald-500">Guru</p>
                                    <p class="mt-2 text-2xl font-black text-emerald-700">{{ $counts['guru'] }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col lg:flex-row lg:items-end gap-3">
                                <div class="flex flex-wrap bg-slate-200/60 p-1.5 rounded-2xl w-full lg:w-max">
                                    <button @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'bg-white text-slate-800 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'" class="px-4 py-2 text-sm rounded-xl transition-all flex items-center gap-2 outline-none">
                                        Semua
                                        <span :class="activeTab === 'semua' ? 'bg-slate-100 text-slate-700' : 'bg-slate-300/50 text-slate-600'" class="py-0.5 px-2.5 rounded-full text-[10px] font-bold transition-colors">{{ $counts['semua'] }}</span>
                                    </button>
                                    <button @click="activeTab = 'murid'" :class="activeTab === 'murid' ? 'bg-white text-blue-700 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'" class="px-4 py-2 text-sm rounded-xl transition-all flex items-center gap-2 outline-none">
                                        Siswa
                                        <span :class="activeTab === 'murid' ? 'bg-blue-100 text-blue-700' : 'bg-slate-300/50 text-slate-600'" class="py-0.5 px-2.5 rounded-full text-[10px] font-bold transition-colors">{{ $counts['murid'] }}</span>
                                    </button>
                                    <button @click="activeTab = 'guru'" :class="activeTab === 'guru' ? 'bg-white text-emerald-700 shadow-sm font-bold' : 'text-slate-500 hover:text-slate-700 font-medium'" class="px-4 py-2 text-sm rounded-xl transition-all flex items-center gap-2 outline-none">
                                        Guru
                                        <span :class="activeTab === 'guru' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-300/50 text-slate-600'" class="py-0.5 px-2.5 rounded-full text-[10px] font-bold transition-colors">{{ $counts['guru'] }}</span>
                                    </button>
                                </div>

                                <form method="GET" action="{{ route('superadmin.dashboard') }}" class="flex flex-col lg:flex-row gap-3 w-full lg:w-auto">
                                    <input type="hidden" name="view" :value="activeTab">
                                    <div>
                                        <label class="block text-xs font-extrabold text-slate-600 mb-2 uppercase tracking-wider">Urutkan</label>
                                        <select name="sort" onchange="this.form.submit()" class="w-full lg:w-60 px-4 py-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-700 shadow-sm focus:ring-2 focus:ring-slate-800 focus:border-slate-800">
                                            <option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                            <option value="terlama" {{ $sort === 'terlama' ? 'selected' : '' }}>Terlama</option>
                                            <option value="nama_asc" {{ $sort === 'nama_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                            <option value="nama_desc" {{ $sort === 'nama_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                            <option value="identitas_asc" {{ $sort === 'identitas_asc' ? 'selected' : '' }}>NIS/NIP Kecil ke Besar</option>
                                            <option value="identitas_desc" {{ $sort === 'identitas_desc' ? 'selected' : '' }}>NIS/NIP Besar ke Kecil</option>
                                            <option value="kelas_asc" {{ $sort === 'kelas_asc' ? 'selected' : '' }}>Kelas A-Z</option>
                                            <option value="kelas_desc" {{ $sort === 'kelas_desc' ? 'selected' : '' }}>Kelas Z-A</option>
                                            <option value="jurusan_asc" {{ $sort === 'jurusan_asc' ? 'selected' : '' }}>Jurusan A-Z</option>
                                            <option value="jurusan_desc" {{ $sort === 'jurusan_desc' ? 'selected' : '' }}>Jurusan Z-A</option>
                                        </select>
                                    </div>

                                    <div x-show="activeTab === 'murid'" x-cloak>
                                        <label class="block text-xs font-extrabold text-slate-600 mb-2 uppercase tracking-wider">Per Kelas</label>
                                        <select name="kelas" onchange="this.form.submit()" class="w-full lg:w-44 px-4 py-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-700 shadow-sm focus:ring-2 focus:ring-slate-800 focus:border-slate-800">
                                            <option value="semua" {{ $kelas === 'semua' ? 'selected' : '' }}>Semua Kelas</option>
                                            @foreach($kelasOptions as $kelasOption)
                                                <option value="{{ $kelasOption }}" {{ $kelas === $kelasOption ? 'selected' : '' }}>Kelas {{ $kelasOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div x-show="activeTab === 'murid'" x-cloak>
                                        <label class="block text-xs font-extrabold text-slate-600 mb-2 uppercase tracking-wider">Per Jurusan</label>
                                        <select name="jurusan" onchange="this.form.submit()" class="w-full lg:w-48 px-4 py-3 bg-white border border-slate-200 rounded-xl font-semibold text-slate-700 shadow-sm focus:ring-2 focus:ring-slate-800 focus:border-slate-800">
                                            <option value="semua" {{ $jurusan === 'semua' ? 'selected' : '' }}>Semua Jurusan</option>
                                            @foreach($jurusanOptions as $jurusanOption)
                                                <option value="{{ $jurusanOption }}" {{ $jurusan === $jurusanOption ? 'selected' : '' }}>{{ $jurusanOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex items-end">
                                        <button type="submit" class="w-full lg:w-auto px-4 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-extrabold shadow-sm transition">
                                            Terapkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- TAB: DATA SEMUA --}}
                        <div x-show="activeTab === 'semua'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                             class="overflow-x-auto custom-scrollbar">

                            <table class="w-full text-left border-collapse min-w-[900px]">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold">
                                        <th class="px-6 py-4">Nama Akun</th>
                                        <th class="px-6 py-4">Role</th>
                                        <th class="px-6 py-4">NIS / NIP</th>
                                        <th class="px-6 py-4">Kelas / Jurusan</th>
                                        <th class="px-6 py-4 text-center">Status Akses</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $user->role === 'murid' ? 'from-blue-100 to-blue-200 text-blue-700 border-blue-300/50' : 'from-emerald-100 to-emerald-200 text-emerald-700 border-emerald-300/50' }} flex items-center justify-center font-black shadow-inner border shrink-0">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                                        <p class="text-[11px] font-medium text-slate-500">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black border {{ $user->role === 'murid' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                                    {{ $user->role === 'murid' ? 'Siswa' : 'Guru' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold text-slate-700">{{ $user->nis_nip ?? '-' }}</td>
                                            <td class="px-6 py-4">
                                                @if($user->role === 'murid')
                                                    <div class="flex flex-wrap gap-2">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                                            Kelas {{ $user->kelas ?? '-' }}
                                                        </span>
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                            {{ $user->jurusan ?? 'Jurusan belum diisi' }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-500 border border-slate-200">
                                                        Guru / Staff
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center align-middle">
                                                <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-xs font-extrabold px-4 py-2 rounded-xl border shadow-sm transition-all duration-300 {{ $user->is_active ? 'bg-green-500 text-white border-green-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-white text-slate-400 border-slate-200 hover:bg-green-500 hover:text-white hover:border-green-600' }}">
                                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex items-center justify-center gap-2">
                                                    <button type="button" @click="openEditModal(@js($user))" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit info akun">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 14h.01M4 19h16M6 17l10-10 2 2-10 10H6v-2z"></path></svg>
                                                    </button>
                                                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?');" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus Akun">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    <span class="font-medium text-sm">Belum ada data pengguna yang sesuai dengan filter ini.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- TAB: DATA SISWA --}}
                        <div x-show="activeTab === 'murid'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                             class="overflow-x-auto custom-scrollbar">
                             
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold">
                                        <th class="px-6 py-4">Profil Siswa</th>
                                        <th class="px-6 py-4">NIS</th>
                                        <th class="px-6 py-4">Kelas & Jurusan</th>
                                        <th class="px-6 py-4 text-center">Status Akses</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($users->where('role', 'murid') as $user)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-black shadow-inner border border-blue-300/50 shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                                    <p class="text-[11px] font-medium text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 font-extrabold tracking-wide">
                                                {{ $user->nis_nip ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                                    Kelas {{ $user->kelas ?? '-' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-extrabold bg-blue-50 text-blue-700 border border-blue-100">
                                                    {{ $user->jurusan ?? 'Tanpa jurusan' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center align-middle">
                                            {{-- Fitur Ubah Status Aktif/Nonaktif --}}
                                            <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs font-extrabold px-4 py-2 rounded-xl border shadow-sm transition-all duration-300 {{ $user->is_active ? 'bg-green-500 text-white border-green-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-white text-slate-400 border-slate-200 hover:bg-green-500 hover:text-white hover:border-green-600' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="openEditModal(@js($user))" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit info akun">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 14h.01M4 19h16M6 17l10-10 2 2-10 10H6v-2z"></path></svg>
                                                </button>
                                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun siswa ini? Semua data laporan miliknya mungkin akan ikut terhapus.');" class="inline-block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus Akun">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                <span class="font-medium text-sm">Belum ada data siswa terdaftar.</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- TAB: DATA GURU --}}
                        <div x-show="activeTab === 'guru'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                             class="overflow-x-auto custom-scrollbar">
                             
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] uppercase tracking-wider font-extrabold">
                                        <th class="px-6 py-4">Profil Guru</th>
                                        <th class="px-6 py-4">NIP</th>
                                        <th class="px-6 py-4 text-center">Status Akses</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($users->where('role', 'guru') as $user)
                                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 text-emerald-700 flex items-center justify-center font-black shadow-inner border border-emerald-300/50 shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                                                    <p class="text-[11px] font-medium text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 font-extrabold tracking-wide">
                                                {{ $user->nis_nip ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center align-middle">
                                            {{-- Fitur Ubah Status Aktif/Nonaktif --}}
                                            <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs font-extrabold px-4 py-2 rounded-xl border shadow-sm transition-all duration-300 {{ $user->is_active ? 'bg-green-500 text-white border-green-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-white text-slate-400 border-slate-200 hover:bg-green-500 hover:text-white hover:border-green-600' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button" @click="openEditModal(@js($user))" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Edit info akun">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-1 14h.01M4 19h16M6 17l10-10 2 2-10 10H6v-2z"></path></svg>
                                                </button>
                                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun guru ini?');" class="inline-block">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus Akun">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="inline-flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                <span class="font-medium text-sm">Belum ada data guru terdaftar.</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- 3. MODAL TAMBAH AKUN BARU --}}
        <div x-cloak x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div x-show="openModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
            
            <div x-show="openModal" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]">
                
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tambah Akun Baru</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Daftarkan akses untuk Siswa atau Guru.</p>
                    </div>
                    <button @click="openModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-6">
                        @csrf 
                        
                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama..." class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Email Akun</label>
                                <input type="email" name="email" required placeholder="email@sekolah.com" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Peran Akses (Role)</label>
                                <select name="role" x-model="roleMode" @change="if (roleMode !== 'murid') { studentClass = ''; studentJurusan = ''; }" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow appearance-none cursor-pointer">
                                    <option value="murid">Siswa</option>
                                    <option value="guru">Guru / Staff</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-extrabold text-slate-700 mb-2" x-text="roleMode === 'guru' ? 'NIP (10 digit angka)' : 'NIS (8 digit angka)'"></label>
                            <input type="text"
                                   name="nis_nip"
                                   required
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   x-bind:maxlength="roleMode === 'murid' ? 8 : 10"
                                   @input="sanitizeIdentity($event, roleMode)"
                                   placeholder="Hanya angka..."
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5" x-show="roleMode === 'murid'" x-transition x-cloak>
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Kelas</label>
                                <select name="kelas" x-model="studentClass" @change="if (studentClass !== '12') studentJurusan = ''" x-bind:required="roleMode === 'murid'" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    <option value="10">Kelas 10 (X)</option>
                                    <option value="11">Kelas 11 (XI)</option>
                                    <option value="12">Kelas 12 (XII)</option>
                                </select>
                            </div>
                            <div x-show="studentClass === '12'" x-transition x-cloak>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Jurusan</label>
                                <select name="jurusan" x-model="studentJurusan" x-bind:required="studentClass === '12'" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow cursor-pointer">
                                    <option value="" disabled selected>-- Pilih Jurusan --</option>
                                    <option value="RPL">RPL</option>
                                    <option value="MPLB">MPLB</option>
                                    <option value="AKL">AKL</option>
                                    <option value="TKR">TKR</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Password Awal (Min. 8 Karakter)</label>
                            <input type="password" name="password" required placeholder="Masukkan password awal" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
                        </div>

                        <div class="pt-4 mt-6 border-t border-slate-100 flex justify-end gap-3">
                            <button type="button" @click="openModal = false" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
                            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-900 hover:to-black text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/20 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 4. MODAL EDIT INFO AKUN --}}
        <div x-cloak x-show="editModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div x-show="editModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="editModal = false"></div>

            <div x-show="editModal"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-slate-100 flex flex-col max-h-[90vh]">

                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 shrink-0">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Edit Info Akun</h3>
                        <p class="text-sm text-slate-500 font-medium mt-1">Ubah data akun tanpa membuat akun baru.</p>
                    </div>
                    <button @click="editModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <form :action="`{{ url('/superadmin/users') }}/${editingUser.id}`" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" x-model="editingUser.name" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Email Akun</label>
                                <input type="email" name="email" x-model="editingUser.email" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                            </div>

                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Peran Akses (Role)</label>
                                <select name="role" x-model="editRoleMode" @change="if (editRoleMode !== 'murid') { editStudentClass = ''; editStudentJurusan = ''; }" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow appearance-none cursor-pointer">
                                    <option value="murid">Siswa</option>
                                    <option value="guru">Guru / Staff</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-2" x-text="editRoleMode === 'guru' ? 'NIP (10 digit angka)' : 'NIS (8 digit angka)'"></label>
                            <input type="text"
                                   name="nis_nip"
                                   x-model="editingUser.nis_nip"
                                   required
                                   inputmode="numeric"
                                   pattern="[0-9]*"
                                   x-bind:maxlength="editRoleMode === 'murid' ? 8 : 10"
                                   @input="sanitizeIdentity($event, editRoleMode); editingUser.nis_nip = $event.target.value"
                                   placeholder="Hanya angka..."
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-show="editRoleMode === 'murid'" x-transition x-cloak>
                            <div>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Kelas</label>
                                <select name="kelas" x-model="editStudentClass" @change="if (editStudentClass !== '12') editStudentJurusan = ''" x-bind:required="editRoleMode === 'murid'" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow cursor-pointer">
                                    <option value="" disabled>-- Pilih Kelas --</option>
                                    <option value="10">Kelas 10 (X)</option>
                                    <option value="11">Kelas 11 (XI)</option>
                                    <option value="12">Kelas 12 (XII)</option>
                                </select>
                            </div>
                            <div x-show="editStudentClass === '12'" x-transition x-cloak>
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Jurusan</label>
                                <select name="jurusan" x-model="editStudentJurusan" x-bind:required="editStudentClass === '12'" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-bold text-slate-700 transition-shadow cursor-pointer">
                                    <option value="" disabled>-- Pilih Jurusan --</option>
                                    <option value="RPL">RPL</option>
                                    <option value="MPLB">MPLB</option>
                                    <option value="AKL">AKL</option>
                                    <option value="TKR">TKR</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
                        </div>

                        <div class="pt-4 mt-6 border-t border-slate-100 flex justify-end gap-3">
    <button type="button" @click="editModal = false" class="px-6 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
    
    {{-- Menggunakan warna biru solid yang lebih aman agar tidak ter-purge oleh Tailwind --}}
    <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
        Simpan Perubahan
    </button>
</div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>