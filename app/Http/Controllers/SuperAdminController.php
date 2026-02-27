<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Models\Pengaduan; 
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // 1. Menampilkan Halaman Dashboard Superadmin & Tabel User
    public function index()
    {
        $users = User::where('role', '!=', 'superadmin')->latest()->get();
        
        $resetRequests = PasswordResetRequest::with('user')
                            ->where('status', 'pending')
                            ->latest()
                            ->get();
        
        return view('superadmin.dashboard', compact('users', 'resetRequests'));
    }

    // 2. Menyimpan User Baru (Nama diubah kepada 'store' untuk mengelakkan ralat)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:guru,murid,admin',
            'nis_nip'  => 'nullable|string',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => true,
            'nis_nip'   => $request->nis_nip,
            'kelas'     => $request->kelas,
            'jurusan'   => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Akaun ' . $request->name . ' berjaya ditambah!');
    }

    // 3. Mengubah Status Aktif/Nyahaktif User (Nama diubah kepada 'toggle')
    public function toggle($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => !$user->is_active 
        ]);

        return redirect()->back()->with('success', 'Status akaun ' . $user->name . ' berjaya dikemas kini.');
    }

    // 4. Menghapus Akaun Pengguna (Fungsi baharu untuk butang Hapus)
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Langkah keselamatan: Elak Superadmin terpadam akaun sendiri
        if (auth()->id() == $user->id) {
            return redirect()->back()->withErrors(['Maaf, anda tidak boleh menghapus akaun milik anda sendiri.']);
        }

        // Padam pengguna dari pangkalan data
        $user->delete();

        return redirect()->back()->with('success', 'Akaun pengguna berjaya dihapuskan!');
    }

    // =========================================================================
    // PENTING: Biarkan kod fungsi laporan() asli anda di bawah ini!
    // Jangan padam fungsi ini supaya ciri cetak PDF anda kekal berfungsi.
    // =========================================================================
    public function laporan()
    {
        // (Kod asal laporan anda kekal di sini)
    }
}