<?php

// Lokasi: app/Http/Controllers/GuruController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;

class GuruController extends Controller
{
    // Menampilkan semua laporan ke Dasbor Guru
    public function index()
    {
        // Mengambil semua pengaduan beserta data user dan kategori yang berelasi
        $pengaduans = Pengaduan::with(['user', 'kategori'])->orderBy('created_at', 'desc')->get();
        return view('guru.dashboard', compact('pengaduans'));
    }

    // Memproses update status dan feedback dari Guru
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Proses,Selesai',
            'feedback' => 'nullable|string'
        ]);

        $pengaduan = Pengaduan::findOrFail($id);
        $pengaduan->update([
            'status' => $request->status,
            'feedback' => $request->feedback
        ]);

        return redirect()->back()->with('success', 'Status dan Tanggapan berhasil diperbarui!');
    }
}