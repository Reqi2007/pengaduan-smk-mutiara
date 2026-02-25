<x-app-layout>
    <style>
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
    </style>

    <div class="relative min-h-screen bg-slate-50 overflow-hidden font-sans selection:bg-blue-200 selection:text-blue-900 pb-20">
        
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-blue-300/20 rounded-full blur-[100px] animate-float"></div>
            <div class="absolute bottom-[10%] left-[-5%] w-[300px] h-[300px] bg-indigo-300/20 rounded-full blur-[100px] animate-float-delayed"></div>
        </div>

        <x-slot name="header">
            <div class="flex justify-between items-center w-full relative z-10">
                <h2 class="font-extrabold text-2xl text-slate-800 flex items-center gap-3 tracking-tight">
                    <span class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white p-2.5 rounded-xl shadow-lg shadow-blue-500/30 text-base transform hover:scale-110 transition duration-300">👤</span> 
                    Pengaturan Profil
                </h2>
            </div>
        </x-slot>

        <div class="py-8 relative z-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

                <div class="glass-panel rounded-[2rem] shadow-xl shadow-blue-900/5 p-8 border border-white relative overflow-hidden animate-slide-up">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-bl-full -z-10 opacity-50"></div>
                    
                    <header class="mb-6">
                        <h2 class="text-xl font-black text-slate-900">Informasi Profil</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Perbarui foto, informasi profil, dan alamat email akun kamu.</p>
                    </header>

                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="flex items-center gap-6 pb-6 border-b border-slate-100">
                            <div class="relative group">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg shadow-blue-500/20">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-3xl font-black shadow-lg shadow-blue-500/20 border-4 border-white">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <label for="avatar" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity duration-300">
                                    <span class="text-white text-xs font-bold text-center">Ubah<br>Foto</span>
                                </label>
                            </div>
                            
                            <div class="flex-1">
                                <label class="block text-sm font-extrabold text-slate-700 mb-2">Foto Profil</label>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 px-0 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                <p class="text-xs text-slate-400 mt-2 font-medium">Format: JPG, PNG (Maks. 2MB). Disarankan rasio 1:1.</p>
                                @error('avatar') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                            <div>
                                <label for="name" class="block text-sm font-extrabold text-slate-700 mb-1.5">Nama Lengkap</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">📝</div>
                                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-slate-700 transition-shadow">
                                </div>
                                @error('name') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-extrabold text-slate-700 mb-1.5">Alamat Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">📧</div>
                                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-slate-700 transition-shadow">
                                </div>
                                @error('email') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-200">
                                        <p class="text-sm text-amber-800 font-medium">
                                            Alamat email kamu belum diverifikasi.
                                            <button form="send-verification" class="underline font-bold text-amber-600 hover:text-amber-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                                Klik di sini untuk mengirim ulang email verifikasi.
                                            </button>
                                        </p>
                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 text-sm font-bold text-green-600">
                                                Tautan verifikasi baru telah dikirim ke alamat email kamu.
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                                Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-green-600 flex items-center gap-1">
                                    ✅ Berhasil disimpan.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="glass-panel rounded-[2rem] shadow-sm hover:shadow-xl hover:shadow-indigo-900/5 transition-all p-8 border border-slate-200 relative overflow-hidden animate-slide-up" style="animation-delay: 100ms;">
                    <header class="mb-6">
                        <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">🔐 Perbarui Kata Sandi</h2>
                        <p class="mt-1 text-sm font-medium text-slate-500">Pastikan akun kamu menggunakan kata sandi acak yang panjang agar tetap aman.</p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="space-y-5 max-w-xl">
                            <div>
                                <label for="update_password_current_password" class="block text-sm font-extrabold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
                                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium text-slate-700 transition-shadow">
                                @error('current_password', 'updatePassword') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="update_password_password" class="block text-sm font-extrabold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                                <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium text-slate-700 transition-shadow">
                                @error('password', 'updatePassword') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="update_password_password_confirmation" class="block text-sm font-extrabold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium text-slate-700 transition-shadow">
                                @error('password_confirmation', 'updatePassword') <p class="text-sm text-red-500 font-semibold mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-2">
                            <button type="submit" class="px-8 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-extrabold shadow-lg shadow-slate-900/20 transition transform hover:-translate-y-0.5">
                                Perbarui Sandi
                            </button>

                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-green-600 flex items-center gap-1">
                                    ✅ Sandi diperbarui.
                                </p>
                            @endif
                        </div>
                    </form>
                </div>

                <div x-data="{ confirmUserDeletion: false }" class="bg-red-50/50 rounded-[2rem] p-8 border border-red-100 relative overflow-hidden animate-slide-up" style="animation-delay: 200ms;">
                    <header class="mb-6">
                        <h2 class="text-xl font-black text-red-600 flex items-center gap-2">⚠️ Hapus Akun</h2>
                        <p class="mt-1 text-sm font-medium text-red-500/80">Setelah akun kamu dihapus, semua sumber daya dan data di dalamnya akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                    </header>

                    <button @click="confirmUserDeletion = true" class="px-6 py-3 bg-red-100 hover:bg-red-500 text-red-600 hover:text-white border border-red-200 hover:border-red-500 rounded-xl font-bold transition-all duration-300 shadow-sm">
                        Hapus Akun Saya
                    </button>

                    <div x-cloak x-show="confirmUserDeletion" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                        <div x-show="confirmUserDeletion" x-transition.opacity class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm" @click="confirmUserDeletion = false"></div>
                        
                        <div x-show="confirmUserDeletion" x-transition class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 relative z-10 border border-slate-100">
                            <h3 class="text-xl font-black text-slate-900 mb-2">Apakah kamu yakin?</h3>
                            <p class="text-sm text-slate-500 font-medium mb-6">Sekali lagi, semua data akan hilang selamanya. Silakan masukkan kata sandi kamu untuk mengonfirmasi penghapusan akun.</p>
                            
                            <form method="post" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('delete')

                                <div class="mb-6">
                                    <label for="password" class="sr-only">Kata Sandi</label>
                                    <input id="password" name="password" type="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 font-medium" placeholder="Masukkan kata sandi kamu">
                                    @error('password', 'userDeletion') <p class="text-sm text-red-500 font-semibold mt-2">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="confirmUserDeletion = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-extrabold shadow-lg shadow-red-500/30 transition transform hover:-translate-y-0.5">
                                        Hapus Akun Permanen
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