<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                <span class="bg-indigo-600 text-white p-2 rounded-lg text-sm shadow-md">👨‍🏫</span> Meja Kerja Guru & Teknisi
            </h2>

            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition duration-300 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ 
        openModal: false, 
        profileModal: false, 
        selectedData: { id: '', status: '', feedback: '' }, 
        studentProfile: { name: '', nis: '', kelas: '', jurusan: '', hp: '' } 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="bg-green-100 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3">
                    <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-1">Selamat Bertugas, {{ Auth::user()->name }}!</h3>
                    <p class="text-slate-400">NIP/NIK: {{ Auth::user()->nis_nip ?? '-' }} | Berikut adalah laporan kerusakan fasilitas dari siswa yang perlu ditindaklanjuti.</p>
                </div>
                <div class="hidden md:block text-5xl opacity-50 transform hover:scale-110 transition duration-300">🛠️</div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Data Pelapor</th>
                                <th class="px-6 py-4">Masalah & Lokasi</th>
                                <th class="px-6 py-4 text-center">Bukti Foto</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pengaduans as $item)
                            <tr class="hover:bg-slate-50 transition duration-200">
                                <td class="px-6 py-4">
                                    <button type="button" 
                                            @click="profileModal = true; studentProfile = { name: '{{ addslashes($item->user->name) }}', nis: '{{ addslashes($item->user->nis_nip ?? '-') }}', kelas: '{{ addslashes($item->user->kelas ?? '-') }}', jurusan: '{{ addslashes($item->user->jurusan ?? 'Umum') }}', hp: '{{ addslashes($item->user->no_telp ?? 'Tidak ada') }}' }" 
                                            class="font-extrabold text-indigo-600 hover:text-indigo-800 text-left hover:underline flex items-center gap-1">
                                        👤 {{ $item->user->name }}
                                    </button>
                                    <div class="text-xs font-semibold text-slate-500 mt-1">{{ $item->user->kelas ?? '-' }} {{ $item->user->jurusan ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400 mt-1">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-800 px-2 py-1 rounded text-xs font-bold border border-slate-200">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                    <div class="font-bold text-slate-800 mt-2">{{ $item->lokasi }}</div>
                                    <div class="text-xs text-slate-500 line-clamp-2 mt-1">{{ $item->keterangan }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->foto) 
                                        <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg font-bold text-xs transition">
                                            📷 Lihat Foto
                                        </a>
                                    @else 
                                        <span class="text-slate-400 text-xs italic">Tidak ada foto</span> 
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'Menunggu') 
                                        <span class="bg-yellow-100 border border-yellow-200 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Menunggu</span>
                                    @elseif($item->status == 'Proses') 
                                        <span class="bg-blue-100 border border-blue-200 text-blue-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Proses</span>
                                    @else 
                                        <span class="bg-green-100 border border-green-200 text-green-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Selesai</span> 
                                    @endif
                                    
                                    @if($item->status == 'Selesai' && $item->rating)
                                        <div class="text-yellow-400 text-xs mt-2 flex justify-center" title="Siswa memberi {{ $item->rating }} Bintang">
                                            @for($i=0; $i<$item->rating; $i++) ★ @endfor
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="openModal = true; selectedData = { id: '{{ $item->id }}', status: '{{ $item->status }}', feedback: '{{ addslashes($item->feedback) }}' }" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-md transition transform hover:-translate-y-0.5">
                                        Tanggapi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 font-bold bg-slate-50 border-t border-dashed border-slate-200">
                                    <span class="text-3xl block mb-2">🎉</span>
                                    Bagus! Semua fasilitas sekolah dalam keadaan baik. Belum ada laporan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
                <div @click.away="openModal = false" x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
                    <div class="bg-indigo-600 px-6 py-4 text-white font-bold flex justify-between items-center">
                        <span>Proses Laporan Kerusakan</span> 
                        <button @click="openModal = false" class="text-indigo-200 hover:text-white transition text-xl">&times;</button>
                    </div>
                    <form :action="'/guru/pengaduan/' + selectedData.id" method="POST" class="p-6 space-y-5">
                        @csrf 
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Ubah Status Perbaikan</label>
                            <select name="status" x-model="selectedData.status" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 font-semibold text-slate-700">
                                <option value="Menunggu">🟠 Menunggu Antrean</option>
                                <option value="Proses">🔵 Sedang Dikerjakan / Diperbaiki</option>
                                <option value="Selesai">🟢 Telah Selesai Diperbaiki</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catatan / Pesan untuk Siswa</label>
                            <textarea name="feedback" x-model="selectedData.feedback" rows="4" placeholder="Contoh: AC sudah diservis dan freon sudah ditambah..." class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm p-3"></textarea>
                        </div>
                        
                        <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                            <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-0.5">Simpan Pembaruan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="profileModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
                <div @click.away="profileModal = false" x-show="profileModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden relative">
                    <div class="h-28 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600"></div>
                    
                    <div class="px-6 pb-8 relative">
                        <div class="w-24 h-24 bg-white rounded-full border-4 border-white shadow-xl absolute -top-12 left-6 flex items-center justify-center text-4xl font-black text-indigo-600 bg-indigo-50">
                            <span x-text="studentProfile.name.charAt(0)"></span>
                        </div>
                        
                        <button @click="profileModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 w-8 h-8 rounded-full flex items-center justify-center font-bold transition">&times;</button>
                        
                        <div class="mt-14">
                            <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight" x-text="studentProfile.name"></h3>
                            <p class="text-sm font-bold text-indigo-600 mb-6" x-text="'NIS: ' + studentProfile.nis"></p>
                            
                            <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-100 shadow-inner">
                                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                                    <span class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Tingkat Kelas</span>
                                    <span class="text-sm font-bold text-slate-800 bg-white px-3 py-1 rounded-lg border border-slate-200 shadow-sm" x-text="studentProfile.kelas"></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                                    <span class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Program / Jurusan</span>
                                    <span class="text-sm font-bold text-slate-800 text-right max-w-[150px] leading-tight" x-text="studentProfile.jurusan"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">Kontak / WA</span>
                                    <a :href="'https://wa.me/' + studentProfile.hp.replace(/[^0-9]/g, '')" target="_blank" class="text-sm font-bold text-green-600 hover:text-green-700 flex items-center gap-1 bg-green-50 px-3 py-1 rounded-lg border border-green-200 transition" x-show="studentProfile.hp !== 'Tidak ada'">
                                        📞 <span x-text="studentProfile.hp"></span>
                                    </a>
                                    <span class="text-sm font-bold text-slate-400" x-show="studentProfile.hp === 'Tidak ada'">Tidak ada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>