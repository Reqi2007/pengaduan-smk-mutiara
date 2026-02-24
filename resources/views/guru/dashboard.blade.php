<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-bold text-2xl text-slate-800 flex items-center gap-2">
                <span class="bg-indigo-600 text-white p-2 rounded-lg text-sm shadow-md">👨‍🏫</span> Meja Kerja Guru & Teknisi
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

    <div class="py-10 bg-slate-50 min-h-screen" x-data="{ openModal: false, selectedData: { id: '', status: '', feedback: '' } }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-8 text-white shadow-xl flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold mb-1">Selamat Bertugas, {{ Auth::user()->name }}!</h3>
                    <p class="text-slate-400">NIP/NIK: {{ Auth::user()->nis_nip ?? '-' }} | Berikut adalah laporan kerusakan fasilitas dari siswa.</p>
                </div>
                <div class="hidden md:block text-5xl opacity-50">🛠️</div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4">Data Pelapor</th>
                                <th class="px-6 py-4">Masalah & Lokasi</th>
                                <th class="px-6 py-4 text-center">Bukti</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pengaduans as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-slate-900">{{ $item->user->name }}</div>
                                    <div class="text-xs font-semibold text-indigo-600">{{ $item->user->kelas ?? '-' }} {{ $item->user->jurusan ?? '-' }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $item->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-slate-100 text-slate-800 px-2 py-1 rounded text-xs font-bold">{{ $item->kategori->nama_kategori ?? 'Umum' }}</span>
                                    <div class="font-bold text-slate-800 mt-1">{{ $item->lokasi }}</div>
                                    <div class="text-xs line-clamp-2 mt-1">{{ $item->keterangan }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->foto) <a href="{{ asset('storage/' . $item->foto) }}" target="_blank" class="text-blue-500 hover:underline font-bold text-xs">Lihat Foto</a>
                                    @else <span class="text-slate-400 text-xs">Tidak ada</span> @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'Menunggu') <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">Menunggu</span>
                                    @elseif($item->status == 'Proses') <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Proses</span>
                                    @else <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Selesai</span> @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="openModal = true; selectedData = { id: '{{ $item->id }}', status: '{{ $item->status }}', feedback: '{{ addslashes($item->feedback) }}' }" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold shadow-md transition">Tanggapi</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-10 text-center text-slate-400 font-bold">Bagus! Belum ada laporan kerusakan saat ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" style="display: none;">
                <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden">
                    <div class="bg-indigo-600 px-6 py-4 text-white font-bold flex justify-between">
                        Proses Laporan <button @click="openModal = false">&times;</button>
                    </div>
                    <form :action="'/guru/pengaduan/' + selectedData.id" method="POST" class="p-6 space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Ubah Status</label>
                            <select name="status" x-model="selectedData.status" required class="w-full bg-slate-50 border-slate-200 rounded-xl">
                                <option value="Menunggu">🟠 Menunggu</option>
                                <option value="Proses">🔵 Sedang Diproses/Diperbaiki</option>
                                <option value="Selesai">🟢 Telah Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Catatan/Balasan untuk Siswa</label>
                            <textarea name="feedback" x-model="selectedData.feedback" rows="4" class="w-full bg-slate-50 border-slate-200 rounded-xl"></textarea>
                        </div>
                        <div class="pt-4 flex justify-end gap-2">
                            <button type="button" @click="openModal = false" class="px-5 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl font-bold shadow-lg">Simpan Tanggapan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>