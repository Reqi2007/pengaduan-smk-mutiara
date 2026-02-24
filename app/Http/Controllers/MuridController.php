<?php

// Lokasi: app/Http/Controllers/MuridController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class MuridController extends Controller
{
    // Menampilkan Dashboard Murid (Form dan Riwayat)
    public function index()
    {
        $kategoris = Kategori::all();
        // Mengambil riwayat pengaduan milik murid yang sedang login saja
        $pengaduans = Pengaduan::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        
        return view('murid.dashboard', compact('kategoris', 'pengaduans'));
    }

    // Menyimpan Pengaduan Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'lokasi' => 'required|string|max:100',
            'keterangan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        $fotoPath = null;
        // Jika murid mengupload foto, simpan ke folder public/pengaduan-foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengaduan-foto', 'public');
        }

        Pengaduan::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'foto' => $fotoPath,
            'status' => 'Menunggu', // Status default dari database
        ]);

        return redirect()->back()->with('success', 'Aspirasi / Pengaduan berhasil dikirim! Menunggu tanggapan dari Guru/Admin.');
    }
}