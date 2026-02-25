<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest;
use Illuminate\Support\Facades\Hash;

class PasswordResetRequestController extends Controller
{
    // 1. Fungsi untuk Murid/Guru mengirim permohonan dari halaman Login
    public function store(Request $request)
    {
        $request->validate([
            'account_identifier' => 'required|string',
        ]);

        // Cari user berdasarkan username (name) atau email
        $user = User::where('name', $request->account_identifier)
                    ->orWhere('email', $request->account_identifier)
                    ->first();

        if (!$user) {
            return back()->withErrors(['name' => 'Akun dengan Username/Email tersebut tidak ditemukan.']);
        }

        // Cek apakah user ini sudah mengajukan request yang masih pending
        $existingRequest = PasswordResetRequest::where('user_id', $user->id)
                                               ->where('status', 'pending')
                                               ->first();

        if ($existingRequest) {
            return back()->with('status', 'Permohonan Anda sudah ada dan sedang menunggu persetujuan Superadmin.');
        }

        // Buat permohonan baru
        PasswordResetRequest::create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        return back()->with('status', 'Permohonan reset sandi berhasil dikirim! Silakan tunggu konfirmasi Superadmin.');
    }

    // 2. Fungsi untuk Superadmin Menyetujui & Mereset Sandi
    public function approve($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $user = $resetRequest->user;

        // Ubah password user menjadi default (Misal: Sekolah123!)
        $defaultPassword = 'Sekolah123!';
        $user->update([
            'password' => Hash::make($defaultPassword)
        ]);

        // Hapus request dari antrean (atau bisa juga diubah statusnya jadi 'approved')
        $resetRequest->delete();

        return back()->with('success', "Sandi untuk {$user->name} berhasil direset menjadi: {$defaultPassword}");
    }

    // 3. Fungsi untuk Superadmin Menolak Permohonan
    public function destroy($id)
    {
        $resetRequest = PasswordResetRequest::findOrFail($id);
        $resetRequest->delete();

        return back()->with('success', 'Permohonan reset sandi berhasil ditolak & dihapus.');
    }
}