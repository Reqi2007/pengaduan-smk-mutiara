<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-extrabold text-2xl text-slate-800 flex items-center gap-3 tracking-tight">
                <span class="bg-gradient-to-r from-slate-800 to-slate-700 text-white p-2.5 rounded-xl shadow-lg shadow-slate-500/30 text-base">🛡️</span> 
                Pusat Kendali Admin
            </h2>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white border border-red-200 hover:border-red-500 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md hover:-translate-y-0.5 group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </x-slot>

    <style>
        [x-cloak] { display: none !important; }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-slide-up { 
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
            opacity: 0; 
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        
        /* Custom Scrollbar untuk Tabel */
        .custom-scrollbar::-webkit-scrollbar { height: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <div class="py-8 bg-slate-50/50 min-h-screen relative overflow-hidden" x-data="{ openModal: false, roleMode: 'murid' }">
        
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-blue-50/50 to-transparent pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 7000)" class="animate-slide-up bg-white border-l-4 border-green-500 text-slate-700 px-6 py-4 rounded-2xl shadow-lg shadow-green-500/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-inner">✅</div>
                        <span class="font-bold text-slate-800">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-slate-600 font-bold">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="animate-slide-up bg-white border-l-4 border-red-500 text-slate-700 px-6 py-4 rounded-2xl shadow-lg shadow-red-500/10">
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

            <div class="animate-slide-up delay-100 bg-white rounded-3xl p-6 shadow-xl shadow-amber-900/5 border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-3xl -z-10"></div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3.5 bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 rounded-2xl shadow-inner border border-amber-300/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Permohonan Reset Sandi</h3>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">Persetujuan admin untuk akun yang lupa password.</p>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50/80 rounded-xl">
                            <tr>
                                <th class="px-6 py-3 font-bold rounded-l-xl">Identitas Akun (Username/Email)</th>
                                <th class="px-6 py-3 font-bold">Waktu Pengajuan</th>
                                <th class="px-6 py-3 font-bold text-right rounded-r-xl">Aksi Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($resetRequests as $request)
                            <tr class="hover:bg-amber-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-800 text-base">{{ $request->user->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $request->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                                        ⏱️ {{ $request->created_at->diffForHumans() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex justify-end gap-2">
                                    <form action="{{ route('superadmin.reset.reject', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak permohonan ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition duration-200" title="Tolak & Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.reset.approve', $request->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset password user ini?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-md shadow-amber-500/20 transition hover:-translate-y-0.5 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Setujui & Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400 font-semibold">
                                    <div class="text-4xl mb-2">✨</div>
                                    Tidak ada permohonan reset sandi saat ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="animate-slide-up delay-200">
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="p-3.5 bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 rounded-2xl shadow-inner border border-blue-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h3>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">Kelola seluruh akses akun guru dan murid.</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('superadmin.laporan') }}" target="_blank" class="px-5 py-3 bg-white border-2 border-slate-200 text-slate-700 rounded-xl font-bold shadow-sm hover:border-slate-300 hover:bg-slate-50 transition flex items-center gap-2">
                            🖨️ Cetak PDF
                        </a>
                        <button @click="openModal = true" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-xl hover:from-blue-700 hover:to-indigo-700 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            User Baru
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-extrabold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Nama & Username</th>
                                    <th class="px-6 py-4">Informasi Akun</th>
                                    <th class="px-6 py-4 text-center">Hak Akses</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($users as $user)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold uppercase shrink-0">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-extrabold text-slate-900 text-base group-hover:text-blue-600 transition-colors">{{ $user->name }}</div>
                                                <div class="text-xs text-slate-500 font-medium mt-0.5">ID: {{ $user->nis_nip ?? 'Belum Diatur' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($user->role == 'murid')
                                            <div class="space-y-1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700">
                                                    🎓 Kelas: {{ $user->kelas ?? '-' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-sky-50 text-sky-700 mt-1 sm:mt-0 sm:ml-1">
                                                    💻 {{ $user->jurusan ?? '-' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                                                🏫 Staff Sekolah
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="px-3 py-1.5 rounded-xl text-xs font-extrabold uppercase tracking-wide border {{ $user->role == 'guru' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-bold px-4 py-1.5 rounded-full border shadow-sm transition-all duration-300 {{ $user->is_active ? 'bg-green-500 text-white border-green-600 hover:bg-red-50 hover:text-red-600 hover:border-red-200' : 'bg-white text-slate-400 border-slate-200 hover:bg-green-500 hover:text-white hover:border-green-600' }}">
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
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] flex flex-col overflow-hidden">
                    
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white z-20">
                        <div>
                            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Registrasi Akun</h3>
                            <p class="text-sm text-slate-500 mt-1">Tambahkan pengguna baru ke sistem.</p>
                        </div>
                        <button @click="openModal = false" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-8 overflow-y-auto custom-scrollbar">
                        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap (Username Login)</label>
                                <input type="text" name="name" required placeholder="Masukkan nama..." class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                                <input type="email" name="email" required placeholder="contoh@sekolah.com" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Role Akun</label>
                                    <select name="role" x-model="roleMode" required class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow font-semibold text-slate-700">
                                        <option value="murid">🎓 Murid Siswa</option>
                                        <option value="guru">💼 Guru / Staff</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">NIS / NIP</label>
                                    <input type="text" name="nis_nip" required placeholder="Nomor Induk" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                                </div>
                            </div>

                            <div x-show="roleMode === 'murid'" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="grid grid-cols-2 gap-5 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                                <div>
                                    <label class="block text-xs font-extrabold text-blue-800 mb-1.5 uppercase tracking-wider">Kelas</label>
                                    <select name="kelas" class="w-full bg-white border-blue-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Pilih Kelas</option>
                                        <option value="X">X (Sepuluh)</option>
                                        <option value="XI">XI (Sebelas)</option>
                                        <option value="XII">XII (Dua Belas)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-extrabold text-blue-800 mb-1.5 uppercase tracking-wider">Jurusan</label>
                                    <input type="text" name="jurusan" placeholder="Contoh: RPL" class="w-full bg-white border-blue-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Password Awal (Min. 8 Karakter)</label>
                                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-shadow">
                            </div>

                            <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end gap-3">
                                <button type="button" @click="openModal = false" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">Batal</button>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>