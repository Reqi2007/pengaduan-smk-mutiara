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

    <div class="relative min-h-screen bg-slate-50 overflow-hidden font-sans selection:bg-slate-200 selection:text-slate-900 pb-20" 
         x-data="{ openModal: false, roleMode: 'murid' }">
        
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

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 7000)" class="animate-slide-up bg-white border-l-4 border-green-500 px-6 py-4 rounded-2xl shadow-xl shadow-green-500/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-inner">✅</div>
                            <span class="font-bold text-slate-800">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 font-bold text-2xl">&times;</button>
                    </div>
                @endif

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
                                            ⏱️ {{ $request->created_at->diffForHumans() }}
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
                                            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-extrabold rounded-xl shadow-lg shadow-amber-500/20 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                Setujui & Reset
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 font-semibold bg-white/50">
                                        <div class="text-4xl mb-3 opacity-50">✨</div>
                                        Tidak ada permohonan reset sandi saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="animate-slide-up" style="animation-delay: 200ms;">
                    
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

                            <button @click="openModal = true" class="flex-1 md:flex-none justify-center px-6 py-3 bg-gradient-to-r from-slate-800 to-slate-900 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/20 hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                User Baru
                            </button>
                        </div>
                    </div>

                    <div class="glass-panel rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left text-slate-600">
                                <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider">
                                    <tr>
                                        <th class="px-6 py-5">Identitas Pengguna</th>
                                        <th class="px-6 py-5">Informasi Data</th>
                                        <th class="px-6 py-5 text-center">Hak Akses</th>
                                        <th class="px-6 py-5 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white/40">
                                    @foreach($users as $user)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 font-black shadow-inner shrink-0 border border-slate-300">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-extrabold text-slate-900 text-base group-hover:text-blue-600 transition-colors">{{ $user->name }}</div>
                                                    <div class="text-xs font-semibold text-slate-500 mt-0.5">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="text-xs font-bold text-slate-700 mb-1.5">ID/NIS: {{ $user->nis_nip ?? 'Belum Diatur' }}</div>
                                            @if($user->role == 'murid')
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded border border-indigo-100 text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700">
                                                        Kelas: {{ $user->kelas ?? '-' }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded border border-sky-100 text-[10px] font-black uppercase tracking-wider bg-sky-50 text-sky-700">
                                                        {{ $user->jurusan ?? '-' }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded border border-slate-200 text-xs font-black bg-slate-100 text-slate-600">
                                                    🏫 Staff Sekolah
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-5 text-center align-middle">
                                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $user->role == 'guru' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                                {{ $user->role }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-center align-middle">
                                            <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST" class="m-0">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs font-extrabold px-4 py-2 rounded-xl border shadow-sm transition-all duration-300 {{ $user->is_active ? 'bg-green-500 text-white border-green-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-white text-slate-400 border-slate-200 hover:bg-green-500 hover:text-white hover:border-green-600' }}">
                                                    {{ $user->is_active ? '✅ Aktif' : '❌ Nonaktif' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div x-cloak x-show="openModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                    <div x-show="openModal" x-transition.opacity.duration.300ms class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
                    
                    <div x-show="openModal" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] flex flex-col overflow-hidden border border-slate-100">
                        
                        <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 z-20">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Registrasi Akun</h3>
                                <p class="text-sm text-slate-500 font-medium mt-1">Tambahkan pengguna baru ke sistem.</p>
                            </div>
                            <button @click="openModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-8 overflow-y-auto custom-scrollbar bg-white">
                            <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Nama Lengkap (Username Login)</label>
                                    <input type="text" name="name" required placeholder="Masukkan nama..." class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
                                </div>

                                <div>
                                    <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Email Akses</label>
                                    <input type="email" name="email" required placeholder="contoh@sekolah.com" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
                                </div>

                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Role Akun</label>
                                        <select name="role" x-model="roleMode" required class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition-shadow font-bold text-slate-700">
                                            <option value="murid">🎓 Murid Siswa</option>
                                            <option value="guru">💼 Guru / Staff</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-700 mb-1.5">NIS / NIP</label>
                                        <input type="text" name="nis_nip" required placeholder="Nomor Induk" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
                                    </div>
                                </div>

                                <div x-show="roleMode === 'murid'" 
                                     x-transition
                                     class="grid grid-cols-2 gap-5 p-5 bg-slate-50/80 rounded-2xl border border-slate-200">
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Tingkat Kelas</label>
                                        <select name="kelas" class="w-full bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-slate-800">
                                            <option value="">Pilih Kelas</option>
                                            <option value="X">X (Sepuluh)</option>
                                            <option value="XI">XI (Sebelas)</option>
                                            <option value="XII">XII (Dua Belas)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Jurusan</label>
                                        <input type="text" name="jurusan" placeholder="Contoh: RPL" class="w-full bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-slate-800">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-extrabold text-slate-700 mb-1.5">Password Awal (Min. 8 Karakter)</label>
                                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-slate-800 focus:border-slate-800 font-medium transition-shadow">
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

            </div>
        </div>
    </div>
</x-app-layout>