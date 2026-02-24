<?php

// Lokasi: database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat Akun SuperAdmin untuk Refan
        User::create([
            'name' => 'Refan Al-Kholqi',
            'email' => 'admin@smkmutiara.sch.id',
            'password' => Hash::make('password123'), // Password default
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // Membuat beberapa Kategori default
        Kategori::create([
            'nama_kategori' => 'Fasilitas Kelas',
            'deskripsi' => 'Kerusakan pada meja, kursi, papan tulis, proyektor, dll.'
        ]);
        
        Kategori::create([
            'nama_kategori' => 'Fasilitas Umum',
            'deskripsi' => 'Kerusakan pada toilet, tempat parkir, kantin, dll.'
        ]);
    }
}