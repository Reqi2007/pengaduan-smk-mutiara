<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                <span class="bg-indigo-800 text-white p-2 rounded-lg text-sm shadow-md">👑</span> Ruang Superadmin
            </h2>
            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition duration-300 flex items-center gap-2 shadow-sm">
                    Keluar
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ openModal: false, role: 'murid' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3">
                    <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-indigo-800 to-purple-700 flex items-center justify-center text-white text-3xl font-extrabold shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">Manajemen Pengguna</h3>
                        <p class="text-slate-500 font-medium mt-1">Kelola akun Murid, Guru/Teknisi, dan Admin lainnya.</p>
                    </div>
                </div>
                <button @click="openModal = true" class="px-6 py-3 bg-indigo-800 hover:bg-indigo-900 text-white rounded-xl font-bold shadow-lg shadow-indigo-900/30 transition transform hover:-translate-y-0.5">
                    + Tambah Pengguna Baru
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Nama & Kontak</th>
                                <th class="px-6 py-4">Role / Peran</th>
                                <th class="px-6 py-4">Detail Identitas</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($users as $user)
                            <tr class="hover:bg-slate-50 transition duration-200">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-800 text-base">{{ $user->name }}</div>
                                    <div class="text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->role == 'admin') <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold border border-purple-200">👑 Admin</span>
                                    @elseif($user->role == 'guru') <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200">👨‍🏫 Guru / Teknisi</span>
                                    @else <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200">🎒 Murid</span> @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-500">
                                        <span class="font-bold text-slate-700">NIS/NIP:</span> {{ $user->nis_nip ?? '-' }} <br>
                                        <span class="font-bold text-slate-700">Kelas:</span> {{ $user->kelas ?? '-' }} <br>
                                        <span class="font-bold text-slate-700">Jurusan:</span> {{ $user->jurusan ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition border border-red-200">Hapus</button>
                                    </form>
                                    @else
                                    <span class="text-xs font-bold text-slate-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="openModal = false" x-show="openModal" x-transition class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative max-h-[90vh] overflow-y-auto">
                <button @click="openModal = false" class="absolute top-4 right-4 text-slate-400 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-6">Tambah Pengguna Baru</h3>
                
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Peran / Role</label>
                        <select name="role" x-model="role" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                            <option value="murid">Murid</option>
                            <option value="guru">Guru / Teknisi</option>
                            <option value="admin">Superadmin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Password (Minimal 8 Karakter)</label>
                        <input type="password" name="password" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                    </div>

                    <div x-show="role === 'murid' || role === 'guru'" class="space-y-4 border-t border-slate-100 pt-4 mt-4">
                        <p class="text-xs text-slate-400 font-bold uppercase">Data Tambahan (Opsional)</p>
                        <input type="text" name="nis_nip" placeholder="NIS / NIP" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        
                        <div x-show="role === 'murid'" class="grid grid-cols-2 gap-4">
                            <input type="text" name="kelas" placeholder="Kelas (Contoh: XII)" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                            <input type="text" name="jurusan" placeholder="Jurusan (Contoh: RPL)" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 bg-indigo-800 hover:bg-indigo-900 text-white rounded-xl font-bold transition">Simpan & Buat Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>