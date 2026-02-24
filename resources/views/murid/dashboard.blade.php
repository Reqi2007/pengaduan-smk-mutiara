<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Siswa - Ruang Aspirasi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                     x-transition.duration.500ms class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md" role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-6 flex justify-end">
                <button @click="openModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-300 transform hover:-translate-y-1">
                    + Buat Pengaduan Baru
                </button>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-4">Riwayat Pengaduan Kamu</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($pengaduans as $item)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 hover:shadow-xl transition duration-300">
                        @if($item->foto)
                            <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $item->foto) }}" alt="Foto Laporan">
                        @else
                            <div class="h-48 w-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <span>Tidak ada foto</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-semibold text-indigo-600 uppercase">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                @if($item->status == 'Menunggu')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif($item->status == 'Proses')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">Proses</span>
                                @else
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">Selesai</span>
                                @endif
                            </div>
                            <h4 class="font-bold text-lg text-gray-900 mb-1">Lokasi: {{ $item->lokasi }}</h4>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $item->keterangan }}</p>
                            
                            @if($item->feedback)
                            <div class="mt-4 bg-gray-50 p-3 rounded-lg border-l-4 border-indigo-500 text-sm">
                                <span class="font-bold text-gray-700 block mb-1">Tanggapan Guru/Admin:</span>
                                <span class="text-gray-600">{{ $item->feedback }}</span>
                            </div>
                            @endif
                            <div class="mt-4 text-xs text-gray-400">Dilaporkan: {{ $item->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-lg shadow text-center text-gray-500">
                        Kamu belum membuat pengaduan atau aspirasi apa pun.
                    </div>
                @endforelse
            </div>

            <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="openModal = false" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    
                    <div x-show="openModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="{{ route('murid.pengaduan.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4 border-b pb-2">Formulir Pengaduan Sarana</h3>
                                
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori Kerusakan</label>
                                    <select name="kategori_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Misal: Ruang Kelas 12 RPL 1)</label>
                                    <input type="text" name="lokasi" required placeholder="Tuliskan lokasi rinci" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Detail Kerusakan</label>
                                    <textarea name="keterangan" rows="4" required placeholder="Jelaskan kerusakannya seperti apa..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Foto Bukti (Opsional, Max 2MB)</label>
                                    <input type="file" name="foto" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Kirim Laporan
                                </button>
                                <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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