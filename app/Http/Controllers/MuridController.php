<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kategori;
use App\Models\Ulasan;
use Illuminate\Support\Facades\Auth;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'kinerja_status'  => ['nullable', 'in:semua,Proses,Selesai'],
            'kinerja_sort'    => ['nullable', 'in:terbaru,terlama'],
            'kinerja_tanggal' => ['nullable', 'date'],
        ]);

        $pengaduans = Pengaduan::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        $kategoris = Kategori::all();

        $kinerjaStatus = $filters['kinerja_status'] ?? 'semua';
        $kinerjaSort = $filters['kinerja_sort'] ?? 'terbaru';
        $kinerjaTanggal = $filters['kinerja_tanggal'] ?? null;

        $laporanKinerjaQuery = Pengaduan::with(['user', 'kategori', 'ulasans.user'])
            ->whereIn('status', ['Proses', 'Selesai']);

        if ($kinerjaStatus !== 'semua') {
            $laporanKinerjaQuery->where('status', $kinerjaStatus);
        }

        if ($kinerjaTanggal) {
            $laporanKinerjaQuery->whereDate('created_at', $kinerjaTanggal);
        }

        $laporanKinerjaQuery->orderBy('created_at', $kinerjaSort === 'terlama' ? 'asc' : 'desc');

        $laporanKinerja = $laporanKinerjaQuery
            ->paginate(10)
            ->withQueryString();

        return view('murid.dashboard', compact('pengaduans', 'kategoris', 'laporanKinerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi'      => 'required|string|max:255',
            'keterangan'  => 'required|string',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('pengaduan_fotos', 'public') : null;

        Pengaduan::create([
            'user_id' => Auth::id(), 'kategori_id' => $request->kategori_id,
            'lokasi' => $request->lokasi, 'keterangan' => $request->keterangan,
            'foto' => $fotoPath, 'status' => 'Menunggu',
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
    }

    // SIMPAN ULASAN BARU
    public function storeUlasan(Request $request, $pengaduan_id)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'komentar' => 'required|string']);

        $pengaduan = Pengaduan::findOrFail($pengaduan_id);

        if ($pengaduan->status !== 'Selesai') {
            return redirect()->back()->with('error', 'Ulasan hanya bisa diberikan setelah laporan selesai diproses.');
        }
        
        // Cek jika sudah pernah review
        if(Ulasan::where('pengaduan_id', $pengaduan_id)->where('user_id', Auth::id())->exists()){
            return redirect()->back()->with('error', 'Kamu sudah memberikan penilaian.');
        }

        Ulasan::create([
            'pengaduan_id' => $pengaduan_id, 'user_id' => Auth::id(),
            'rating' => $request->rating, 'komentar' => $request->komentar
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil dipublikasikan!');
    }

    // UPDATE ULASAN
    public function updateUlasan(Request $request, $id)
    {
        $ulasan = Ulasan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $request->validate(['rating' => 'required|integer|min:1|max:5', 'komentar' => 'required|string']);
        
        $ulasan->update(['rating' => $request->rating, 'komentar' => $request->komentar]);
        return redirect()->back()->with('success', 'Ulasan berhasil diperbarui!');
    }

    // HAPUS ULASAN
    public function destroyUlasan($id)
    {
        $ulasan = Ulasan::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $ulasan->delete();
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
