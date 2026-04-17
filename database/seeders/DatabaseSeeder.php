<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');
        
        // Inisialisasi Faker dengan lokalisasi Indonesia
        $faker = Faker::create('id_ID'); 

        // ==========================================
        // 1. AKUN SUPERADMIN
        // ==========================================
        User::updateOrCreate(
            ['email' => 'superadmin@smk-mutiara.sch.id'],
            [
                'name' => 'Refan Al-Kholqi',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        // ==========================================
        // 2. GENERATE 20 AKUN GURU DUMMY
        // ==========================================
        for ($i = 1; $i <= 20; $i++) {
            User::updateOrCreate(
                ['email' => "guru{$i}@smk-mutiara.sch.id"],
                [
                    // Mengenerate nama acak ditambah gelar S.Pd
                    'name' => $faker->firstName . ' ' . $faker->lastName . ', S.Pd.',
                    'password' => $defaultPassword,
                    'role' => 'guru',
                    'is_active' => true,
                    // Format NIP guru acak
                    'nis_nip' => $faker->unique()->numerify('198#######00##'),
                    'kelas' => null,
                    'jurusan' => null,
                ]
            );
        }

        // ==========================================
        // 3. GENERATE 80 AKUN MURID DUMMY
        // ==========================================
        $kelasOptions = ['10', '11', '12'];
        $jurusanOptions = ['RPL', 'MPLB', 'AKL', 'TKR'];

        for ($i = 1; $i <= 80; $i++) {
            User::updateOrCreate(
                ['email' => "siswa{$i}@smk-mutiara.sch.id"],
                [
                    // Mengenerate nama murid acak
                    'name' => $faker->name,
                    'password' => $defaultPassword,
                    'role' => 'murid',
                    'is_active' => true,
                    // Format NIS murid acak
                    'nis_nip' => $faker->unique()->numerify('1023####'),
                    // Kelas dan Jurusan diambil secara acak dari array opsi
                    'kelas' => $faker->randomElement($kelasOptions),
                    'jurusan' => $faker->randomElement($jurusanOptions),
                ]
            );
        }

        // ==========================================
        // 4. GENERATE DATA KATEGORI
        // ==========================================
        $categories = [
            ['nama_kategori' => 'Fasilitas Kelas', 'deskripsi' => 'Kerusakan meja, kursi, proyektor.'],
            ['nama_kategori' => 'Fasilitas Umum', 'deskripsi' => 'Toilet, lapangan, kantin.'],
            ['nama_kategori' => 'Fasilitas Lab', 'deskripsi' => 'Komputer lab, AC lab, jaringan internet.'],
            ['nama_kategori' => 'Lainnya', 'deskripsi' => 'Kerusakan lainnya di luar kategori.'],
        ];

        foreach ($categories as $category) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $category['nama_kategori']],
                ['deskripsi' => $category['deskripsi']]
            );
        }
    }
}