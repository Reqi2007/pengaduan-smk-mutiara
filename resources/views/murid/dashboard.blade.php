<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <span class="bg-blue-600 text-white p-2 rounded-lg text-sm">🎒</span> Ruang Aspirasi Siswa
        </h2>
    </x-slot>

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ openModal: false, showSuccess: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition.duration.500ms 
                     class="mb-6 bg-green-500/10 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg shadow-green-500/20 flex justify-between items-center backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="mb-8 flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-800">Riwayat Laporanmu</h3>
                    <p class="text-slate-500 text-sm">Pantau status laporan yang telah kamu ajukan di sini.</p>
                </div>
                <button @click="openModal = true" class="relative inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-sm font-medium text-slate-900 rounded-xl group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 transition duration-300 hover:scale-105 shadow-lg shadow-blue-500/30">
                    <span class="relative px-6 py-3 transition-all ease-in duration-75 bg-white dark:bg-slate-900 rounded-xl group-hover:bg-opacity-0 font-bold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Buat Pengaduan
                    </span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($pengaduans as $item)
                    <div class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl border border-slate-100 overflow-hidden transform transition duration-500 hover:-translate-y-2 flex flex-col">
                        
                        <div class="relative h-56 overflow-hidden">
                            @if($item->foto)
                                <img class="w-full h-full object-cover transform transition duration-700 group-hover:scale-110" src="{{ asset('storage/' . $item->foto) }}" alt="Foto">
                            @else
                                <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-80"></div>
                            
                            <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                                <span class="bg-white/20 backdrop-blur-md text-white px-3 py-1 rounded-lg text-xs font-bold border border-white/30 uppercase tracking-wider">
                                    {{ $item->kategori->nama_kategori ?? 'Umum' }}
                                </span>
                                
                                @if($item->status == 'Menunggu')
                                    <span class="flex h-3 w-3 relative" title="Menunggu">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                                    </span>
                                @elseif($item->status == 'Proses')
                                    <span class="flex h-3 w-3 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span></span>
                                @else
                                    <span class="h-3 w-3 rounded-full bg-green-500 shadow-lg shadow-green-500"></span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex-grow flex flex-col">
                            <h4 class="font-extrabold text-xl text-slate-800 mb-2 group-hover:text-blue-600 transition">{{ $item->lokasi }}</h4>
                            <p class="text-slate-500 text-sm mb-4 line-clamp-3 leading-relaxed">{{ $item->keterangan }}</p>
                            
                            @if($item->feedback)
                            <div class="mt-auto bg-indigo-50/50 p-4 rounded-xl border border-indigo-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">G</div>
                                    <span class="font-bold text-indigo-900 text-sm">Tanggapan Guru:</span>
                                </div>
                                <p class="text-indigo-700 text-sm italic">"{{ $item->feedback }}"</p>
                            </div>
                            @else
                                <div class="mt-auto pt-4 border-t border-slate-100 text-xs text-slate-400 flex justify-between items-center">
                                    <span>Dilaporkan: {{ $item->created_at->diffForHumans() }}</span>
                                    <span class="text-[10px] bg-slate-100 px-2 py-1 rounded-md">{{ $item->status }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white rounded-3xl border border-dashed border-slate-300">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" class="w-32 h-32 opacity-50 mb-4 animate-bounce" alt="Empty">
                        <h3 class="text-xl font-bold text-slate-700">Belum ada Laporan</h3>
                        <p class="text-slate-400 mt-2">Fasilitas di sekitarmu aman? Bagus! Kalau ada yang rusak, lapor di sini ya.</p>
                    </div>
                @endforelse
            </div>

            <div x-show="openModal" class="fixed inset-0 z-[99] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div x-show="openModal" x-transition.opacity.duration.300ms class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="openModal = false"></div>
                    
                    <div x-show="openModal" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95" 
                         class="relative inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-slate-100">
                        
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Buat Laporan Baru</h3>
                            <button @click="openModal = false" class="text-white/70 hover:text-white transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>

                        <form action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Kategori Kerusakan</label>
                                    <select name="kategori_id" required class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition duration-300 hover:bg-white">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Lokasi Detail</label>
                                    <input type="text" name="lokasi" required placeholder="Contoh: Lab Komputer RPL 1" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition hover:bg-white">
                                </div>
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Deskripsi Kerusakan</label>
                                    <textarea name="keterangan" rows="4" required placeholder="Jelaskan secara rinci..." class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition hover:bg-white"></textarea>
                                </div>
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Upload Foto (Opsional, Max 2MB)</label>
                                    <input type="file" name="foto" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                                </div>
                            </div>
                            
                            <div class="mt-8 flex flex-row-reverse gap-3">
                                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 text-white rounded-xl font-bold transition duration-300 transform hover:-translate-y-1 shadow-lg">Kirim Laporan</button>
                                <button type="button" @click="openModal = false" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-xl font-bold transition duration-300">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>