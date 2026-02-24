<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Guru - Kelola Pengaduan Sarana') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, selectedData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition.duration.500ms class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Daftar Laporan Masuk</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="py-3 px-4 text-left">Pelapor (NIS)</th>
                                <th class="py-3 px-4 text-left">Kategori & Lokasi</th>
                                <th class="py-3 px-4 text-left">Detail Kerusakan</th>
                                <th class="py-3 px-4 text-center">Foto</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse($pengaduans as $item)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->user->nis_nip }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-semibold text-indigo-600 block">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                    <span class="text-sm">{{ $item->lokasi }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm max-w-xs truncate" title="{{ $item->keterangan }}">
                                    {{ $item->keterangan }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->foto)
                                        <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="text-blue-500 underline text-sm hover:text-blue-700">Lihat Foto</a>
                                    @else
                                        <span class="text-gray-400 text-sm">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->status == 'Menunggu')
                                        <span class="bg-yellow-100 text-yellow-800 py-1 px-3 rounded-full text-xs font-bold border border-yellow-200">Menunggu</span>
                                    @elseif($item->status == 'Proses')
                                        <span class="bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-xs font-bold border border-blue-200">Proses</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-bold border border-green-200">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button @click="openModal = true; selectedData = { 
                                        id: '{{ $item->id }}', 
                                        status: '{{ $item->status }}', 
                                        feedback: '{{ addslashes($item->feedback) }}' 
                                    }" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-1 px-3 rounded text-sm transition duration-300">
                                        Beri Tanggapan
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-6 px-4 text-center text-gray-500">Belum ada laporan pengaduan yang masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openModal = false" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div x-show="openModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        
                        <form :action="'/guru/pengaduan/' + selectedData.id" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4 border-b pb-2">Proses & Tanggapi Laporan</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Ubah Status</label>
                                    <select name="status" x-model="selectedData.status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Proses">Proses (Sedang dikerjakan)</option>
                                        <option value="Selesai">Selesai (Sudah diperbaiki)</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Pesan Tanggapan / Feedback</label>
                                    <textarea name="feedback" x-model="selectedData.feedback" rows="4" placeholder="Tuliskan tanggapan untuk siswa..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan Tanggapan
                                </button>
                                <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>