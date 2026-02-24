<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                <span class="bg-slate-800 text-white p-2 rounded-lg text-sm shadow-md">🛡️</span> Pusat Kendali Admin
            </h2>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition duration-300 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ openModal: false, roleMode: 'murid' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border border-green-500 text-green-800 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
                    <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-500 text-red-800 px-6 py-4 rounded-xl shadow-sm">
                    <p class="font-black mb-2 flex items-center gap-2"><span>⚠️</span> Gagal Menambahkan Akun:</p>
                    <ul class="list-disc pl-8 font-bold text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800">Manajemen Pengguna</h3>
                    <p class="text-sm text-slate-500">Buat dan kelola akun Guru serta Murid.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('superadmin.laporan') }}" target="_blank" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-bold shadow hover:bg-slate-900 transition flex items-center gap-2">
                        🖨️ Cetak PDF
                    </a>
                    <button @click="openModal = true" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition">
                        + User Baru
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Nama & Username</th>
                                <th class="px-6 py-4">Informasi Akun</th>
                                <th class="px-6 py-4 text-center">Hak Akses</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ $user->nis_nip ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role == 'murid')
                                        <span class="text-xs font-bold text-indigo-600 block">Kelas: {{ $user->kelas ?? '-' }}</span>
                                        <span class="text-xs font-bold text-blue-600 block">Jurusan: {{ $user->jurusan ?? '-' }}</span>
                                    @else
                                        <span class="text-xs text-slate-400">Staff Sekolah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $user->role == 'guru' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-xs font-bold px-3 py-1 rounded border transition {{ $user->is_active ? 'bg-green-50 text-green-600 border-green-200 hover:bg-red-50 hover:text-red-600' : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-green-50 hover:text-green-600' }}">
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

            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
                <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden relative max-h-[90vh] overflow-y-auto">
                    <div class="bg-slate-800 px-6 py-4 text-white font-bold flex justify-between sticky top-0 z-10">
                        Registrasi Pengguna <button @click="openModal = false" class="hover:text-red-400">&times;</button>
                    </div>
                    <form action="{{ route('superadmin.users.store') }}" method="POST" class="p-6 space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap (Username Login)</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                            <input type="email" name="email" required class="w-full bg-slate-50 border-slate-200 rounded-xl" placeholder="contoh@sekolah.com">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Role Akun</label>
                                <select name="role" x-model="roleMode" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                                    <option value="murid">Murid Siswa</option>
                                    <option value="guru">Guru / Teknisi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">NIS / NIP</label>
                                <input type="text" name="nis_nip" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                            </div>
                        </div>

                        <div x-show="roleMode === 'murid'" x-transition class="grid grid-cols-2 gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div>
                                <label class="block text-xs font-bold text-blue-800 mb-1">Kelas</label>
                                <select name="kelas" class="w-full bg-white border-blue-200 rounded-lg text-sm">
                                    <option value="">Pilih</option>
                                    <option value="X">X (Sepuluh)</option>
                                    <option value="XI">XI (Sebelas)</option>
                                    <option value="XII">XII (Dua Belas)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-blue-800 mb-1">Jurusan</label>
                                <input type="text" name="jurusan" placeholder="Contoh: RPL" class="w-full bg-white border-blue-200 rounded-lg text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Password Baru (Min. 8 Karakter)</label>
                            <input type="password" name="password" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        </div>

                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="openModal = false" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-slate-800 text-white rounded-xl font-bold shadow-lg">Simpan Akun</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>