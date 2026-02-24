<?php

// Lokasi: app/Http/Controllers/SuperAdminController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // Menampilkan halaman dashboard & daftar user
    public function index()
    {
        // Mengambil semua user kecuali superadmin sendiri
        $users = User::where('role', '!=', 'superadmin')->orderBy('created_at', 'desc')->get();
        return view('superadmin.dashboard', compact('users'));
    }

    // Menyimpan user baru (Guru/Murid)
    // Lokasi: app/Http/Controllers/SuperAdminController.php

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:guru,murid',
            'nis_nip' => 'nullable|string|unique:users',
            'kelas' => 'nullable|string',
            'jurusan' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'nis_nip' => $request->nis_nip,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Akun '.$request->name.' berhasil ditambahkan!');
    }

    // Mengaktifkan atau Menonaktifkan Akun
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active; // Kebalikan dari status saat ini
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', 'Akun '.$user->name.' berhasil '.$status.'!');
    }

    // Lokasi: app/Http/Controllers/SuperAdminController.php

    // Fungsi untuk mencetak laporan pengaduan
    public function laporan()
    {
        // Ambil semua data pengaduan untuk dicetak
        $pengaduans = \App\Models\Pengaduan::with(['user', 'kategori'])->orderBy('created_at', 'desc')->get();
        return view('superadmin.laporan', compact('pengaduans'));
    }
}
