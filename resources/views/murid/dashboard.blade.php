<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                <span class="bg-blue-600 text-white p-2 rounded-lg text-sm shadow-md">🎒</span> Ruang Aspirasi Siswa
            </h2>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition duration-300 flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ openModal: false, showSuccess: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition 
                     class="bg-green-100 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3">
                    <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse"></div>
                
                <div class="w-28 h-28 rounded-full border-4 border-blue-100 shadow-lg overflow-hidden bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white text-4xl font-extrabold z-10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left z-10">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ Auth::user()->name }}</h3>
                    <div class="mt-2 inline-flex flex-wrap justify-center md:justify-start gap-2">
                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-bold border border-slate-200">NIS: {{ Auth::user()->nis_nip ?? '-' }}</span>
                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-200">Kelas: {{ Auth::user()->kelas ?? '-' }}</span>
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold border border-blue-200">Jurusan: {{ Auth::user()->jurusan ?? 'Umum' }}</span>
                    </div>
                </div>
                <div class="md:ml-auto z-10">
                    <button @click="openModal = true" class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-2xl font-extrabold shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-1 hover:scale-105 flex items-center gap-2">
                        + Buat Laporan Kerusakan
                    </button>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold text-slate-800 mb-4 px-2">Riwayat Laporanmu</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($pengaduans as $item)
                        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl border border-slate-100 overflow-hidden transition duration-300">
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                @if($item->foto)
                                    <img class="w-full h-full object-cover group-hover:scale-110 transition duration-500" src="{{ asset('storage/' . $item->foto) }}" alt="Foto">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">Tidak ada foto</div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    @if($item->status == 'Menunggu') <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Menunggu</span>
                                    @elseif($item->status == 'Proses') <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Diproses</span>
                                    @else <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Selesai</span> @endif
                                </div>
                            </div>
                            <div class="p-5">
                                <span class="text-xs font-bold text-indigo-600 uppercase">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                <h4 class="font-bold text-lg text-slate-800 mt-1 mb-2">{{ $item->lokasi }}</h4>
                                <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $item->keterangan }}</p>
                                @if($item->feedback)
                                    <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100 text-sm text-indigo-800">
                                        <strong>Guru:</strong> "{{ $item->feedback }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                            <h3 class="text-lg font-bold text-slate-500">Belum ada riwayat laporan.</h3>
                        </div>
                    @endforelse
                </div>
            </div>

            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
                <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
                    <div class="bg-blue-600 px-6 py-4 text-white font-bold flex justify-between">
                        Form Pengaduan Sarana <button @click="openModal = false">&times;</button>
                    </div>
                    <form action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
                            <select name="kategori_id" required class="w-full bg-slate-50 border-slate-200 rounded-xl focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($kategoris as $kategori) <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Lokasi Detail</label>
                            <input type="text" name="lokasi" required placeholder="Contoh: AC Kelas XII RPL 1 Mati" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Penjelasan Kerusakan</label>
                            <textarea name="keterangan" rows="3" required class="w-full bg-slate-50 border-slate-200 rounded-xl"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Foto Bukti (Opsional)</label>
                            <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-blue-50 file:text-blue-700">
                        </div>
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="openModal = false" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-bold shadow-lg">Kirim Laporan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>