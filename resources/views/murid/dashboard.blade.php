<x-app-layout>
    <div x-data="{ activeTab: 'laporanku', openModal: false, showSuccess: true, rateModal: false, rateData: {id: '', rating: 0, hover: 0, ulasan: ''} }">
        
        <x-slot name="header">
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                    <span class="bg-blue-600 text-white p-2 rounded-lg text-sm shadow-md">🎒</span> Ruang Aspirasi Siswa
                </h2>
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition duration-300 flex items-center gap-2 shadow-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </x-slot>

        <div class="py-10 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
                
                @if(session('success'))
                    <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition class="bg-green-100 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 relative z-50">
                        <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-lg border border-slate-100 p-8 flex flex-col md:flex-row items-center gap-8">
                    <div class="w-28 h-28 rounded-full border-4 border-blue-50 shadow-lg overflow-hidden bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white text-4xl font-extrabold flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h3 class="text-3xl font-extrabold text-slate-800">{{ Auth::user()->name }}</h3>
                        <div class="mt-3 inline-flex flex-wrap justify-center md:justify-start gap-2">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-bold border border-slate-200">NIS: {{ Auth::user()->nis_nip ?? '-' }}</span>
                            <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-sm font-bold border border-indigo-200">Kelas: {{ Auth::user()->kelas ?? '-' }}</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold border border-blue-200">Jurusan: {{ Auth::user()->jurusan ?? 'Umum' }}</span>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button @click="openModal = true" class="px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-extrabold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1">
                            + Buat Laporan Baru
                        </button>
                    </div>
                </div>

                <div class="flex space-x-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 w-max">
                    <button @click="activeTab = 'laporanku'" :class="activeTab === 'laporanku' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2 rounded-xl font-bold transition">Riwayat Laporanku</button>
                    <button @click="activeTab = 'kinerja'" :class="activeTab === 'kinerja' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">🌟 Kinerja Sekolah</button>
                </div>

                <div x-show="activeTab === 'laporanku'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($pengaduans as $item)
                            <div class="bg-white rounded-3xl shadow-sm hover:shadow-lg border border-slate-100 overflow-hidden transition duration-300 flex flex-col">
                                <div class="relative h-48 bg-slate-100">
                                    @if($item->foto)
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $item->foto) }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold">Tanpa Foto</div>
                                    @endif
                                    <div class="absolute top-3 right-3">
                                        @if($item->status == 'Selesai') <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Selesai</span>
                                        @elseif($item->status == 'Proses') <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Proses</span>
                                        @else <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Menunggu</span> @endif
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <h4 class="font-bold text-lg text-slate-800 mb-2">{{ $item->lokasi }}</h4>
                                    <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $item->keterangan }}</p>
                                    
                                    @if($item->status == 'Selesai')
                                        @if(!$item->rating)
                                            <div class="mt-auto">
                                                <button @click="rateModal = true; rateData.id = '{{ $item->id }}'; rateData.rating = 0; rateData.ulasan = ''" class="w-full py-2 bg-yellow-50 text-yellow-700 hover:bg-yellow-400 hover:text-white border border-yellow-200 rounded-xl font-bold transition text-sm flex justify-center items-center gap-2">
                                                    ⭐ Beri Penilaian Kinerja
                                                </button>
                                            </div>
                                        @else
                                            <div class="mt-auto bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                                <div class="flex text-yellow-400 text-lg mb-2">
                                                    @for($i=0; $i<$item->rating; $i++) ★ @endfor
                                                </div>
                                                <p class="text-sm text-slate-700 font-medium italic">"{{ $item->ulasan_murid }}"</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-slate-400 font-bold">Belum ada laporan dibuat.</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="activeTab === 'kinerja'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="mb-6">
                        <h3 class="text-2xl font-extrabold text-slate-800">Papan Ulasan Siswa</h3>
                        <p class="text-slate-500 text-sm">Transparansi kinerja sekolah berdasarkan ulasan jujur dari teman-temanmu.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($laporanSelesai as $selesai)
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm">{{ substr($selesai->user->name, 0, 1) }}</div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $selesai->user->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $selesai->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if($selesai->rating)
                                        <div class="flex text-yellow-400 text-lg">
                                            @for($i=0; $i<$selesai->rating; $i++) ★ @endfor
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="bg-slate-50 p-4 rounded-xl mb-4 border border-slate-100">
                                    <p class="text-xs font-extrabold text-indigo-600 uppercase mb-1">Masalah: {{ $selesai->lokasi }}</p>
                                    <p class="text-sm text-slate-600 line-clamp-2">{{ $selesai->keterangan }}</p>
                                </div>
                                
                                @if($selesai->rating)
                                    <div>
                                        <p class="text-sm text-slate-700 italic font-medium">"{{ $selesai->ulasan_murid }}"</p>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-400 italic">Siswa belum memberikan penilaian.</div>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-slate-400 font-bold">Belum ada laporan yang diselesaikan.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <div x-show="rateModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="rateModal = false" x-show="rateModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative">
                
                <button @click="rateModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                
                <div class="text-5xl mb-4 text-center">🌟</div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-2 text-center">Seberapa Puas Kamu?</h3>
                <p class="text-sm text-slate-500 mb-8 text-center">Bantu sekolah menilai kinerja tim teknisi/guru secara jujur.</p>
                
                <form :action="'/murid/pengaduan/' + rateData.id + '/rate'" method="POST">
                    @csrf
                    <div class="flex justify-center gap-3 mb-8" @mouseleave="rateData.hover = 0">
                        <template x-for="star in 5">
                            <button type="button" @click="rateData.rating = star" @mouseover="rateData.hover = star" 
                                class="text-5xl transition transform hover:scale-110 focus:outline-none drop-shadow-sm"
                                :class="(rateData.hover >= star || rateData.rating >= star) ? 'text-yellow-400' : 'text-slate-200'">
                                ★
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="rateData.rating" required>
                    
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tuliskan Ulasanmu (Dapat dilihat teman-teman lain)</label>
                    <textarea name="ulasan_murid" rows="3" placeholder="Contoh: AC sudah dingin, teknisinya cepat banget! Terima kasih..." class="w-full bg-slate-50 border-slate-200 rounded-xl mb-6 text-sm focus:ring-yellow-400 focus:border-yellow-400 p-4" required></textarea>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="rateModal = false" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-slate-900 rounded-xl font-bold shadow-lg shadow-yellow-400/40 transition">Kirim Ulasan Publik</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="openModal = false" x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-extrabold text-slate-800">Buat Laporan Baru</h3>
                    <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                </div>
                
                <form action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Kategori Masalah</label>
                        <select name="kategori_id" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Lokasi Detail</label>
                        <input type="text" name="lokasi" required placeholder="Contoh: Kelas XII RPL 1, Meja Barisan Depan" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi Kerusakan</label>
                        <textarea name="keterangan" required rows="3" placeholder="Jelaskan secara detail kerusakannya..." class="w-full bg-slate-50 border-slate-200 rounded-xl"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Lampirkan Foto Bukti</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div class="pt-4 text-right">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>