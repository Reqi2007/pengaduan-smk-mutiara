<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class MuridController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Murid
     */
    public function index()
    {
        // 1. Mengambil riwayat laporan milik murid yang sedang login
        $pengaduans = Pengaduan::where('user_id', Auth::id())->latest()->get();
        
        // 2. Mengambil semua kategori untuk pilihan di form laporan
        $kategoris = Kategori::all();
        
        // 3. Mengambil SEMUA laporan di sekolah yang statusnya sudah 'Selesai' (Untuk Galeri Kinerja)
        $laporanSelesai = Pengaduan::with(['user', 'kategori'])
                            ->where('status', 'Selesai')
                            ->latest()
                            ->get();

        return view('murid.dashboard', compact('pengaduans', 'kategoris', 'laporanSelesai'));
    }

    /**
     * Menyimpan laporan pengaduan baru dari murid
     */
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari form
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi'      => 'required|string|max:255',
            'keterangan'  => 'required|string',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // Proses upload foto jika murid melampirkan foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            // Simpan foto ke folder storage/app/public/pengaduan_fotos
            $fotoPath = $request->file('foto')->store('pengaduan_fotos', 'public');
        }

        // Simpan data ke database
        Pengaduan::create([
            'user_id'     => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'lokasi'      => $request->lokasi,
            'keterangan'  => $request->keterangan,
            'foto'        => $fotoPath,
            'status'      => 'Menunggu', // Status awal (default)
        ]);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Laporan kerusakan berhasil dikirim! Silakan tunggu tindak lanjut dari pihak sekolah.');
    }

    /**
     * Menyimpan rating (Bintang) dan ulasan tingkat kebahagiaan siswa
     */
    public function rate(Request $request, $id)
    {
        // Validasi rating dan ulasan
        $request->validate([
            'rating'       => 'required|integer|min:1|max:5',
            'ulasan_murid' => 'nullable|string|max:500'
        ]);

        // Cari laporan berdasarkan ID, dan pastikan laporan tersebut milik murid yang login
        $pengaduan = Pengaduan::where('id', $id)
                              ->where('user_id', Auth::id())
                              ->firstOrFail();

        // Update data laporan dengan rating dan ulasan baru
        $pengaduan->update([
            'rating'       => $request->rating,
            'ulasan_murid' => $request->ulasan_murid
        ]);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Terima kasih! Penilaian dan ulasanmu sangat berarti untuk evaluasi kinerja sekolah.');
    }
}