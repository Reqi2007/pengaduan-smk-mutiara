<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Menampilkan halaman dashboard Admin & daftar user
    public function index()
    {
        $users = User::latest()->get(); // Ambil semua data pengguna
        return view('admin.dashboard', compact('users'));
    }

    // Menyimpan user baru (Guru / Murid)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,guru,murid',
            'nis_nip'  => 'nullable|string|max:50',
            'kelas'    => 'nullable|string|max:50',
            'jurusan'  => 'nullable|string|max:100',
        ]);

        // Simpan ke database dengan password yang DI-HASH (Dienkripsi)
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Wajib agar bisa login!
            'role'     => $request->role,
            'nis_nip'  => $request->nis_nip,
            'kelas'    => $request->kelas,
            'jurusan'  => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Akun pengguna berhasil dibuat dan siap digunakan untuk login!');
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus.');
    }
}