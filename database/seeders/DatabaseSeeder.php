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
        $defaultPassword = Hash::make('password123'); // Semua passwordnya: password123

        // 1. Akun SuperAdmin (Refan)
        User::create([
            'name' => 'Refan Al-Kholqi',
            'password' => $defaultPassword,
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // 2. Akun Guru (Admin/Teknisi Sarpras)
        User::create([
            'name' => 'Bapak Budi Sarpras',
            'nis_nip' => '19801234567890',
            'password' => $defaultPassword,
            'role' => 'guru',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ibu Siti IT',
            'nis_nip' => '19851234567890',
            'password' => $defaultPassword,
            'role' => 'guru',
            'is_active' => true,
        ]);

        // 3. Akun Murid (Gimmick Berbagai Jurusan)
        User::create([
            'name' => 'Andi Pratama',
            'nis_nip' => '10203040',
            'kelas' => 'XII',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'password' => $defaultPassword,
            'role' => 'murid',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'nis_nip' => '10203041',
            'kelas' => 'XI',
            'jurusan' => 'Teknik Komputer Jaringan',
            'password' => $defaultPassword,
            'role' => 'murid',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Citra Kirana',
            'nis_nip' => '10203042',
            'kelas' => 'X',
            'jurusan' => 'Multimedia / DKV',
            'password' => $defaultPassword,
            'role' => 'murid',
            'is_active' => true,
        ]);

        // Membuat Kategori default untuk laporan
        Kategori::create(['nama_kategori' => 'Fasilitas Kelas', 'deskripsi' => 'Kerusakan meja, kursi, proyektor.']);
        Kategori::create(['nama_kategori' => 'Fasilitas Umum', 'deskripsi' => 'Toilet, lapangan, kantin.']);
        Kategori::create(['nama_kategori' => 'Fasilitas Lab', 'deskripsi' => 'Komputer, AC Lab, Kabel Jaringan.']);
    }
}