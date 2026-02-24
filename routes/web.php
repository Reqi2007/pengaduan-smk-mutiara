<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MuridController;

Route::get('/', function () {
    return view('welcome');
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
    // GANTI baris Route::post('/murid/pengaduan/{id}/rate', ...) yang lama menjadi ini:
    Route::post('/murid/pengaduan/{id}/ulasan', [MuridController::class, 'storeUlasan'])->name('murid.ulasan.store');
    Route::put('/murid/ulasan/{id}', [MuridController::class, 'updateUlasan'])->name('murid.ulasan.update');
    Route::delete('/murid/ulasan/{id}', [MuridController::class, 'destroyUlasan'])->name('murid.ulasan.destroy');
});

// INI BAGIAN YANG HILANG: Route bawaan Breeze untuk Profile
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
    } elseif ($role === 'guru') {
        return redirect()->route('guru.dashboard');
    } else {
        return redirect()->route('murid.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';