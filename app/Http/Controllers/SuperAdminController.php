<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest; // WAJIB ditambahkan untuk memanggil tabel permohonan reset
use Illuminate\Support\Facades\Hash; // WAJIB untuk enkripsi password

class SuperAdminController extends Controller
{
    // 1. Menampilkan Halaman Dashboard Superadmin & Tabel User
    public function index()
    {
        // Mengambil semua user selain superadmin, diurutkan dari yang terbaru
        $users = User::where('role', '!=', 'superadmin')->latest()->get();
        
        // MENGAMBIL DATA REQUEST RESET PASSWORD YANG MASIH PENDING
        $resetRequests = PasswordResetRequest::with('user')
                            ->where('status', 'pending')
                            ->latest()
                            ->get();
        
        // Melempar variabel $users dan $resetRequests ke halaman view
        return view('superadmin.dashboard', compact('users', 'resetRequests'));
    }

    // 2. Menyimpan User Baru (Agar masuk ke tabel & bisa Login)
    public function storeUser(Request $request)
    {
        // 1. Validasi super ketat agar kita tahu jika ada yang salah
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users', // Email tetap wajib & harus unik
            'password' => 'required|string|min:8', // Ingat, password WAJIB minimal 8 karakter!
            'role'     => 'required|in:guru,murid,admin',
            'nis_nip'  => 'nullable|string',
        ]);

        // 2. Simpan ke database
        User::create([
            'name'      => $request->name,
            'email'     => $request->email, // Jangan lupakan email
            'password'  => Hash::make($request->password), // Password aman
            'role'      => $request->role,
            'is_active' => true, // Status aktif
            'nis_nip'   => $request->nis_nip,
            'kelas'     => $request->kelas,
            'jurusan'   => $request->jurusan,
        ]);

        return redirect()->back()->with('success', 'Akun ' . $request->name . ' berhasil ditambahkan!');
    }

    // 3. Mengubah Status Aktif/Nonaktif User
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Membalikkan nilai boolean is_active (true jadi false, false jadi true)
        $user->update([
            'is_active' => !$user->is_active 
        ]);

        return redirect()->back()->with('success', 'Status akun ' . $user->name . ' berhasil diperbarui.');
    }

    // 4. (Opsional) Cetak Laporan PDF
    public function laporan()
    {
        // Taruh logika cetak PDF kamu di sini nanti
        return "Halaman Cetak Laporan PDF";
    }
}