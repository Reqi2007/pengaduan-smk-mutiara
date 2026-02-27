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

    // 2. Menyimpan User Baru (Beserta Kelas & Jurusan)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:guru,murid,admin',
            'nis_nip'  => 'nullable|string',
            'kelas'    => 'nullable|string',
            'jurusan'  => 'nullable|string',
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

        return redirect()->back()->with('success', 'Akun ' . $request->name . ' berhasil ditambahkan!');
    }

    // 3. Mengubah Status Aktif/Nonaktif User
    public function toggle($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => !$user->is_active 
        ]);

        return redirect()->back()->with('success', 'Status akun ' . $user->name . ' berhasil diperbarui.');
    }

    // 4. Menghapus Akun Pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return redirect()->back()->withErrors(['Maaf, Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    // 5. Cetak Laporan PDF
    public function laporan()
    {
        $pengaduans = Pengaduan::with(['user', 'kategori'])->latest()->get();
        return view('superadmin.laporan', compact('pengaduans'));
    }
}