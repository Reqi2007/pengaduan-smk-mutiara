<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

// Route khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
});

// Route khusus SuperAdmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    Route::post('/superadmin/users', [SuperAdminController::class, 'storeUser'])->name('superadmin.users.store');
    Route::patch('/superadmin/users/{id}/toggle', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.users.toggle');
    Route::get('/superadmin/laporan', [SuperAdminController::class, 'laporan'])->name('superadmin.laporan');
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

// Smart Redirect Dashboard (Sudah disempurnakan agar Admin tidak nyasar)
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    
    if ($role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    } elseif ($role === 'admin') {
        return redirect()->route('admin.dashboard'); // <-- LOGIKA BARU DITAMBAHKAN
    } elseif ($role === 'guru') {
        return redirect()->route('guru.dashboard');
    } else {
        // Jika bukan ketiga di atas (berarti murid), arahkan ke sini
        return redirect()->route('murid.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';