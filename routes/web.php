<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PasswordResetRequestController;

Route::get('/', function () {
    return view('welcome');
});

// Rute Publik (Untuk permohonan reset dari halaman login)
Route::post('/request-reset', [PasswordResetRequestController::class, 'store'])->name('password.admin.request');

// =======================================================
// ROUTE KHUSUS SUPERADMIN (Sudah Digabung & Dirapikan)
// =======================================================
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    // Dashboard & Cetak Laporan PDF
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::get('/superadmin/laporan', [SuperAdminController::class, 'laporan'])->name('superadmin.laporan');
    
    // Manajemen Pengguna (Simpan, Status Aktif, Hapus)
    Route::post('/superadmin/users', [SuperAdminController::class, 'store'])->name('superadmin.users.store');
    Route::patch('/superadmin/users/{id}/toggle', [SuperAdminController::class, 'toggle'])->name('superadmin.users.toggle');
    Route::delete('/superadmin/users/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.users.destroy');

    // Persetujuan Lupa Password
    Route::patch('/superadmin/reset-requests/{id}/approve', [PasswordResetRequestController::class, 'approve'])->name('superadmin.reset.approve');
    Route::delete('/superadmin/reset-requests/{id}', [PasswordResetRequestController::class, 'destroy'])->name('superadmin.reset.reject');
});

// Route khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Route khusus Guru
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    Route::put('/guru/pengaduan/{id}', [GuruController::class, 'update'])->name('guru.pengaduan.update');
});

// Route khusus Murid
Route::middleware(['auth', 'role:murid'])->group(function () {
    Route::get('/murid/dashboard', [MuridController::class, 'index'])->name('murid.dashboard');
    Route::post('/murid/pengaduan', [MuridController::class, 'store'])->name('murid.pengaduan.store');
    
    // Fitur Ulasan Murid
    Route::post('/murid/pengaduan/{id}/ulasan', [MuridController::class, 'storeUlasan'])->name('murid.ulasan.store');
    Route::put('/murid/ulasan/{id}', [MuridController::class, 'updateUlasan'])->name('murid.ulasan.update');
    Route::delete('/murid/ulasan/{id}', [MuridController::class, 'destroyUlasan'])->name('murid.ulasan.destroy');
});

// Route bawaan Breeze untuk Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Smart Redirect Dashboard
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    } elseif ($role === 'admin') {
        return redirect()->route('admin.dashboard'); 
    } elseif ($role === 'guru') {
        return redirect()->route('guru.dashboard');
    } else {
        return redirect()->route('murid.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';