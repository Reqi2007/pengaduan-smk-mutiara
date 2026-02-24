<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <span class="bg-indigo-600 text-white p-2 rounded-lg text-sm">👨‍🏫</span> Ruang Tindak Lanjut Guru
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen" x-data="{ openModal: false, selectedData: { id: '', status: '', feedback: '' }, showSuccess: true }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-show="showSuccess" x-init="setTimeout(() => showSuccess = false, 5000)" x-transition.duration.500ms 
                     class="mb-6 bg-green-500/10 border border-green-500 text-green-700 px-6 py-4 rounded-xl shadow-lg flex justify-between items-center backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xl font-extrabold text-slate-800">Daftar Laporan Masuk</h3>
                    <p class="text-sm text-slate-500">Periksa dan tanggapi keluhan fasilitas dari para siswa.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-100">
                            <tr>
                                <th class="px-6 py-4 font-bold">Pelapor</th>
                                <th class="px-6 py-4 font-bold">Kategori & Lokasi</th>
                                <th class="px-6 py-4 font-bold">Foto</th>
                                <th class="px-6 py-4 font-bold text-center">Status</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengaduans as $item)
                            <tr class="bg-white border-b hover:bg-slate-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-base">{{ $item->user->name }}</div>
                                    <div class="text-xs text-slate-500">NIS: {{ $item->user->nis_nip }}</div>
                                    <div class="text-[10px] text-slate-400 mt-1">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded text-xs font-bold uppercase block w-max mb-1">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                    <span class="font-medium text-slate-700">{{ $item->lokasi }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->foto)
                                        <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="inline-block overflow-hidden rounded-lg shadow-sm hover:shadow-md transition transform hover:scale-105">
                                            <img src="{{ asset('storage/' . $item->foto) }}" class="w-16 h-16 object-cover" alt="Foto Bukti">
                                        </a>
                                    @else
                                        <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 text-xs">No Pic</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center align-middle">
                                    @if($item->status == 'Menunggu')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200 animate-pulse">Menunggu</span>
                                    @elseif($item->status == 'Proses')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">Proses</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">Selesai</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="openModal = true; selectedData = { 
                                        id: '{{ $item->id }}', 
                                        status: '{{ $item->status }}', 
                                        feedback: '{{ addslashes($item->feedback) }}' 
                                    }" class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-blue-500 hover:from-indigo-600 hover:to-blue-600 text-white rounded-lg font-bold shadow-lg shadow-indigo-500/30 transition transform hover:-translate-y-1 text-xs">
                                        Tanggapi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-50">
                                        <span class="text-4xl mb-3">📭</span>
                                        <p class="text-slate-500 font-bold">Belum ada laporan masuk</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="openModal" class="fixed inset-0 z-[99] overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                    <div x-show="openModal" x-transition.opacity.duration.300ms class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
                    
                    <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative inline-block bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-slate-100">
                        
                        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Proses Laporan</h3>
                            <button @click="openModal = false" class="text-white/70 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>

                        <form :action="'/guru/pengaduan/' + selectedData.id" method="POST" class="p-6">
                            @csrf
                            @method('PUT')
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Update Status</label>
                                    <select name="status" x-model="selectedData.status" required class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3">
                                        <option value="Menunggu">🟠 Menunggu (Pending)</option>
                                        <option value="Proses">🔵 Proses (Sedang Dikerjakan)</option>
                                        <option value="Selesai">🟢 Selesai (Telah Diperbaiki)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-slate-700 text-sm font-bold mb-2">Pesan Balasan / Feedback</label>
                                    <textarea name="feedback" x-model="selectedData.feedback" rows="4" placeholder="Tuliskan pesan untuk siswa pelapor..." class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3"></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex flex-row-reverse gap-3">
                                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg transition transform hover:-translate-y-1">Simpan Perubahan</button>
                                <button type="button" @click="openModal = false" class="px-6 py-3 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 rounded-xl font-bold transition">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>