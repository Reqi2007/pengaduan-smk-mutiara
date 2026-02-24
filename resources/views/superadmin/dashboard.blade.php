<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            🛡️ Pusat Kendali Admin
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen" x-data="{ openModal: false, showSuccess: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 4000)" x-transition.opacity 
                     class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-md">
                    <p class="font-bold">Notifikasi Sistem</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800">Manajemen Pengguna</h3>
                        <p class="text-sm text-slate-500">Kelola akses Guru dan Murid dalam sistem.</p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('superadmin.laporan') }}" target="_blank" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl font-semibold shadow-md hover:bg-slate-900 transition flex items-center gap-2 hover:-translate-y-1 duration-300">
                            🖨️ Cetak Rekap
                        </a>
                        <button @click="openModal = true" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition flex items-center gap-2 hover:-translate-y-1 duration-300">
                            + User Baru
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-bold">Profil Pengguna</th>
                                <th scope="col" class="px-6 py-4 font-bold">NIS/NIP</th>
                                <th scope="col" class="px-6 py-4 font-bold">Role Hak Akses</th>
                                <th scope="col" class="px-6 py-4 font-bold text-center">Status Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="bg-white border-b hover:bg-slate-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-100 to-indigo-100 flex items-center justify-center text-blue-700 font-bold text-lg">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-base">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $user->nis_nip }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $user->role == 'guru' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center align-middle">
                                    <form action="{{ route('superadmin.users.toggle', $user->id) }}" method="POST" class="inline-flex items-center justify-center">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors duration-300 ease-in-out {{ $user->is_active ? 'bg-green-500' : 'bg-slate-300' }}" title="{{ $user->is_active ? 'Klik untuk Nonaktifkan' : 'Klik untuk Aktifkan' }}">
                                            <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform duration-300 ease-in-out {{ $user->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                        <span class="ml-3 text-xs font-bold {{ $user->is_active ? 'text-green-600' : 'text-slate-400' }} w-12 text-left">
                                            {{ $user->is_active ? 'Aktif' : 'Off' }}
                                        </span>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
                    
                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-4">
                            <h3 class="text-lg font-bold text-white">Registrasi Pengguna Baru</h3>
                        </div>
                        <form action="{{ route('superadmin.users.store') }}" method="POST" class="p-6">
                            @csrf
                            <div class="space-y-4">
                                <div><label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label><input type="text" name="name" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm font-bold text-slate-700 mb-1">NIS / NIP</label><input type="text" name="nis_nip" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50"></div>
                                    <div><label class="block text-sm font-bold text-slate-700 mb-1">Role</label>
                                        <select name="role" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50">
                                            <option value="murid">Murid</option>
                                            <option value="guru">Guru</option>
                                        </select>
                                    </div>
                                </div>
                                <div><label class="block text-sm font-bold text-slate-700 mb-1">Email</label><input type="email" name="email" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50"></div>
                                <div><label class="block text-sm font-bold text-slate-700 mb-1">Password</label><input type="password" name="password" required class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50"></div>
                            </div>
                            <div class="mt-6 flex flex-row-reverse gap-2">
                                <button type="submit" class="px-6 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold transition">Simpan Akun</button>
                                <button type="button" @click="openModal = false" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>