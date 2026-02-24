<x-app-layout>
    <div x-data="{ 
        activeTab: 'kinerja', 
        openModal: false, 
        showSuccess: true, 
        rateModal: false, 
        rateData: { pengaduan_id: '', rating: 0, hover: 0, komentar: '' },
        editRateModal: false,
        editRateData: { id: '', rating: 0, hover: 0, komentar: '' }
    }">
        
        <x-slot name="header">
            <div class="flex justify-between items-center w-full">
                <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                    <span class="bg-blue-600 text-white p-2 rounded-lg text-sm shadow-md">🎒</span> Ruang Aspirasi
                </h2>
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 rounded-xl font-bold transition flex items-center gap-2 shadow-sm">
                        Keluar
                    </button>
                </form>
            </div>
        </x-slot>

        <div class="py-8 bg-slate-100 min-h-screen">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                @if(session('success'))
                    <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition class="bg-slate-800 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 relative z-50 mb-6">
                        <span class="text-xl">✅</span> <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition class="bg-red-100 text-red-700 border border-red-300 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 relative z-50 mb-6">
                        <span class="text-xl">⚠️</span> <span class="font-bold">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 flex flex-col md:flex-row items-center gap-6">
                    <div class="w-24 h-24 rounded-full border-4 border-blue-50 bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white text-3xl font-extrabold flex-shrink-0 shadow-md">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="text-center md:text-left flex-1">
                        <h3 class="text-2xl font-black text-slate-800">{{ Auth::user()->name }}</h3>
                        <p class="text-slate-500 font-medium mt-1">NIS: {{ Auth::user()->nis_nip ?? '-' }} • {{ Auth::user()->kelas ?? 'Kelas -' }} {{ Auth::user()->jurusan ?? '' }}</p>
                    </div>
                    <button @click="openModal = true" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                        + Buat Laporan
                    </button>
                </div>

                <div class="flex space-x-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-200 w-max mx-auto md:mx-0">
                    <button @click="activeTab = 'kinerja'" :class="activeTab === 'kinerja' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2 rounded-xl font-bold transition flex items-center gap-2">🌐 Feed Kinerja</button>
                    <button @click="activeTab = 'laporanku'" :class="activeTab === 'laporanku' ? 'bg-slate-800 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'" class="px-6 py-2 rounded-xl font-bold transition">Riwayat Pribadi</button>
                </div>

                <div x-show="activeTab === 'kinerja'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="space-y-8 max-w-3xl mx-auto">
                        @forelse($laporanSelesai as $post)
                            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                                <div class="p-5 flex items-center justify-between border-b border-slate-100">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-black text-lg">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $post->user->name }}</p>
                                            <p class="text-xs font-semibold text-slate-400">{{ $post->created_at->diffForHumans() }} • Kategori: {{ $post->kategori->nama_kategori ?? 'Umum' }}</p>
                                        </div>
                                    </div>
                                    <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-green-200">✅ Selesai Diperbaiki</span>
                                </div>
                                
                                <div class="p-5">
                                    <p class="text-sm font-bold text-slate-800 mb-1">📍 {{ $post->lokasi }}</p>
                                    <p class="text-slate-600 mb-4">{{ $post->keterangan }}</p>
                                    @if($post->foto)
                                        <img src="{{ asset('storage/' . $post->foto) }}" class="w-full h-72 object-cover rounded-2xl border border-slate-100">
                                    @endif
                                </div>

                                <div class="bg-slate-50 p-5 border-t border-slate-100">
                                    <h4 class="font-extrabold text-slate-800 mb-4 flex items-center gap-2">
                                        💬 Ulasan Murid <span class="bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full text-xs">{{ $post->ulasans->count() }}</span>
                                    </h4>

                                    @php $myReview = $post->ulasans->where('user_id', Auth::id())->first(); @endphp

                                    @if(!$myReview)
                                        <button @click="rateModal = true; rateData.pengaduan_id = '{{ $post->id }}'; rateData.rating = 0; rateData.komentar = ''" class="w-full py-3 mb-4 bg-white border-2 border-dashed border-slate-300 text-slate-500 hover:border-yellow-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-2xl font-bold transition flex justify-center items-center gap-2">
                                            ⭐ Berikan Penilaianmu untuk Laporan Ini
                                        </button>
                                    @endif

                                    <div class="space-y-4">
                                        @forelse($post->ulasans as $ulasan)
                                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex gap-3 relative">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs flex-shrink-0">
                                                    {{ substr($ulasan->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="text-sm font-bold text-slate-800">{{ $ulasan->user->name }} <span class="text-xs font-normal text-slate-400 ml-1">{{ $ulasan->created_at->diffForHumans() }}</span></p>
                                                            <div class="flex text-yellow-400 text-sm mt-0.5 mb-1">
                                                                @for($i=0; $i<$ulasan->rating; $i++) ★ @endfor
                                                            </div>
                                                        </div>
                                                        @if($ulasan->user_id == Auth::id())
                                                            <div class="flex gap-2">
                                                                <button @click="editRateModal = true; editRateData = { id: '{{ $ulasan->id }}', rating: {{ $ulasan->rating }}, komentar: '{{ addslashes($ulasan->komentar) }}' }" class="text-xs text-blue-600 hover:underline font-bold">Edit</button>
                                                                <form action="{{ route('murid.ulasan.destroy', $ulasan->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?');" class="inline">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="text-xs text-red-600 hover:underline font-bold">Hapus</button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm text-slate-600 mt-1">"{{ $ulasan->komentar }}"</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-center text-sm text-slate-400 italic py-2">Belum ada ulasan. Jadilah yang pertama!</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-3xl border border-slate-200">
                                <span class="text-5xl block mb-4">📭</span>
                                <p class="text-slate-500 font-bold">Belum ada laporan yang selesai diperbaiki.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div x-show="activeTab === 'laporanku'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($pengaduans as $item)
                            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                                <div class="relative h-48 bg-slate-100">
                                    @if($item->foto) <img class="w-full h-full object-cover" src="{{ asset('storage/' . $item->foto) }}">
                                    @else <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold">Tanpa Foto</div> @endif
                                    <div class="absolute top-3 right-3">
                                        @if($item->status == 'Selesai') <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Selesai</span>
                                        @elseif($item->status == 'Proses') <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Proses</span>
                                        @else <span class="bg-yellow-400 text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">Menunggu</span> @endif
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <h4 class="font-bold text-lg text-slate-800 mb-2">{{ $item->lokasi }}</h4>
                                    <p class="text-slate-500 text-sm mb-4 line-clamp-2">{{ $item->keterangan }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-slate-400 font-bold">Belum ada laporan dibuat.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        <div x-show="rateModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="rateModal = false" x-show="rateModal" x-transition class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative">
                <button @click="rateModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                <div class="text-5xl mb-4 text-center">🌟</div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-2 text-center">Berikan Penilaianmu</h3>
                <form :action="'/murid/pengaduan/' + rateData.pengaduan_id + '/ulasan'" method="POST">
                    @csrf
                    <div class="flex justify-center gap-3 mb-6" @mouseleave="rateData.hover = 0">
                        <template x-for="star in 5">
                            <button type="button" @click="rateData.rating = star" @mouseover="rateData.hover = star" class="text-5xl transition focus:outline-none" :class="(rateData.hover >= star || rateData.rating >= star) ? 'text-yellow-400 transform scale-110' : 'text-slate-200'">★</button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="rateData.rating" required>
                    <textarea name="komentar" rows="3" placeholder="Tuliskan ulasanmu..." class="w-full bg-slate-50 border-slate-200 rounded-xl mb-6 p-4" required></textarea>
                    <button type="submit" class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold transition">Kirim Ulasan</button>
                </form>
            </div>
        </div>

        <div x-show="editRateModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="editRateModal = false" x-show="editRateModal" x-transition class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative">
                <button @click="editRateModal = false" class="absolute top-4 right-4 text-slate-400 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-6 text-center">Edit Ulasan</h3>
                <form :action="'/murid/ulasan/' + editRateData.id" method="POST">
                    @csrf @method('PUT')
                    <div class="flex justify-center gap-3 mb-6" @mouseleave="editRateData.hover = 0">
                        <template x-for="star in 5">
                            <button type="button" @click="editRateData.rating = star" @mouseover="editRateData.hover = star" class="text-5xl transition focus:outline-none" :class="(editRateData.hover >= star || editRateData.rating >= star) ? 'text-yellow-400' : 'text-slate-200'">★</button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" x-model="editRateData.rating" required>
                    <textarea name="komentar" x-model="editRateData.komentar" rows="3" class="w-full bg-slate-50 border-slate-200 rounded-xl mb-6 p-4" required></textarea>
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold transition">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" style="display: none;" x-cloak>
            <div @click.away="openModal = false" x-show="openModal" x-transition class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-8 relative">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-extrabold text-slate-800">Buat Laporan Baru</h3>
                    <button @click="openModal = false" class="text-slate-400 font-bold bg-slate-100 w-8 h-8 rounded-full">&times;</button>
                </div>
                <form action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <select name="kategori_id" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                        @foreach($kategoris as $kategori) <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option> @endforeach
                    </select>
                    <input type="text" name="lokasi" required placeholder="Lokasi Detail" class="w-full bg-slate-50 border-slate-200 rounded-xl">
                    <textarea name="keterangan" required rows="3" placeholder="Deskripsi..." class="w-full bg-slate-50 border-slate-200 rounded-xl"></textarea>
                    <input type="file" name="foto" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold">Kirim Laporan</button>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>