<?php

// Lokasi: app/Http/Controllers/GuruController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    // Menampilkan semua laporan ke Dasbor Guru
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'semua');
        $kategoriFilter = $request->query('kategori_id');
        $searchFilter = trim((string) $request->query('search', ''));
        $tanggalFilter = $request->query('tanggal');
        $sortFilter = $request->query('sort', 'terbaru');

        $allowedStatuses = ['semua', 'Menunggu', 'Proses', 'Selesai'];
        $allowedSorts = ['terbaru', 'terlama'];

        if (!in_array($statusFilter, $allowedStatuses, true)) {
            $statusFilter = 'semua';
        }

        if (!in_array($sortFilter, $allowedSorts, true)) {
            $sortFilter = 'terbaru';
        }

        if (!is_numeric($kategoriFilter)) {
            $kategoriFilter = null;
        }

        if ($tanggalFilter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalFilter)) {
            $tanggalFilter = null;
        }

        $pengaduanQuery = Pengaduan::with(['user', 'kategori', 'ulasans.user']);

        if ($statusFilter !== 'semua') {
            $pengaduanQuery->where('status', $statusFilter);
        }

        if ($kategoriFilter) {
            $pengaduanQuery->where('kategori_id', $kategoriFilter);
        }

        if ($searchFilter !== '') {
            $pengaduanQuery->where(function ($query) use ($searchFilter) {
                $query->where('lokasi', 'like', "%{$searchFilter}%")
                    ->orWhere('keterangan', 'like', "%{$searchFilter}%")
                    ->orWhere('feedback', 'like', "%{$searchFilter}%")
                    ->orWhereHas('user', function ($userQuery) use ($searchFilter) {
                        $userQuery->where('name', 'like', "%{$searchFilter}%")
                            ->orWhere('nis_nip', 'like', "%{$searchFilter}%");
                    });
            });
        }

        if ($tanggalFilter) {
            $pengaduanQuery->whereDate('created_at', $tanggalFilter);
        }

        $pengaduanQuery->orderBy('created_at', $sortFilter === 'terlama' ? 'asc' : 'desc');

        $pengaduans = $pengaduanQuery->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('guru.dashboard', compact(
            'pengaduans',
            'kategoris',
            'statusFilter',
            'kategoriFilter',
            'searchFilter',
            'tanggalFilter',
            'sortFilter'
        ));
    }

    // Memproses update status dan feedback dari Guru
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'feedback' => 'nullable|string',
            'feedback_foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        if (in_array($request->status, ['Proses', 'Selesai']) && empty($request->feedback)) {
            return back()->withErrors(['feedback' => 'Feedback wajib diisi ketika status diubah ke Proses atau Selesai.']);
        }

        $pengaduan = Pengaduan::findOrFail($id);

        $feedbackFotoPath = $pengaduan->feedback_foto;

        if ($request->hasFile('feedback_foto')) {
            if ($feedbackFotoPath && Storage::disk('public')->exists($feedbackFotoPath)) {
                Storage::disk('public')->delete($feedbackFotoPath);
            }

            $feedbackFotoPath = $request->file('feedback_foto')->store('pengaduan_feedback', 'public');
        }

        $pengaduan->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'feedback_foto' => $feedbackFotoPath
        ]);

        return redirect()->back()->with('success', 'Status dan Tanggapan berhasil diperbarui!');
    }
}
