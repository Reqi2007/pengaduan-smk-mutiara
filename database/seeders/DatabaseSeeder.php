<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        User::updateOrCreate(
            ['name' => 'Refan Al-Kholqi'],
            [
                'email' => 'superadmin@smk-mutiara.sch.id',
                'name' => 'Refan Al-Kholqi',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        $teachers = [
            ['name' => 'Budi Sarwono', 'email' => 'guru01@smk-mutiara.sch.id', 'nis_nip' => '1978010001'],
            ['name' => 'Siti Rahmawati', 'email' => 'guru02@smk-mutiara.sch.id', 'nis_nip' => '1978010002'],
            ['name' => 'Andi Kusuma', 'email' => 'guru03@smk-mutiara.sch.id', 'nis_nip' => '1978010003'],
            ['name' => 'Dewi Lestari', 'email' => 'guru04@smk-mutiara.sch.id', 'nis_nip' => '1978010004'],
            ['name' => 'Eko Prasetyo', 'email' => 'guru05@smk-mutiara.sch.id', 'nis_nip' => '1978010005'],
            ['name' => 'Fitria Ningsih', 'email' => 'guru06@smk-mutiara.sch.id', 'nis_nip' => '1978010006'],
            ['name' => 'Hendra Wijaya', 'email' => 'guru07@smk-mutiara.sch.id', 'nis_nip' => '1978010007'],
            ['name' => 'Maya Sari', 'email' => 'guru08@smk-mutiara.sch.id', 'nis_nip' => '1978010008'],
            ['name' => 'Rizky Maulana', 'email' => 'guru09@smk-mutiara.sch.id', 'nis_nip' => '1978010009'],
            ['name' => 'Tika Amelia', 'email' => 'guru10@smk-mutiara.sch.id', 'nis_nip' => '1978010010'],
        ];

        foreach ($teachers as $teacher) {
            User::updateOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'password' => $defaultPassword,
                    'role' => 'guru',
                    'is_active' => true,
                    'nis_nip' => $teacher['nis_nip'],
                    'kelas' => null,
                    'jurusan' => null,
                ]
            );
        }

        $students = [
            ['name' => 'Aisyah Putri', 'email' => 'siswa01@smk-mutiara.sch.id', 'nis_nip' => '10230001', 'kelas' => '12', 'jurusan' => 'RPL'],
            ['name' => 'Bagas Pratama', 'email' => 'siswa02@smk-mutiara.sch.id', 'nis_nip' => '10230002', 'kelas' => '12', 'jurusan' => 'MPLB'],
            ['name' => 'Cinta Lestari', 'email' => 'siswa03@smk-mutiara.sch.id', 'nis_nip' => '10230003', 'kelas' => '12', 'jurusan' => 'AKL'],
            ['name' => 'Dimas Saputra', 'email' => 'siswa04@smk-mutiara.sch.id', 'nis_nip' => '10230004', 'kelas' => '12', 'jurusan' => 'TKR'],
            ['name' => 'Eka Nurhayati', 'email' => 'siswa05@smk-mutiara.sch.id', 'nis_nip' => '10230005', 'kelas' => '11', 'jurusan' => null],
            ['name' => 'Farhan Maulana', 'email' => 'siswa06@smk-mutiara.sch.id', 'nis_nip' => '10230006', 'kelas' => '11', 'jurusan' => null],
            ['name' => 'Gita Amalia', 'email' => 'siswa07@smk-mutiara.sch.id', 'nis_nip' => '10230007', 'kelas' => '10', 'jurusan' => null],
            ['name' => 'Hafiz Ramadhan', 'email' => 'siswa08@smk-mutiara.sch.id', 'nis_nip' => '10230008', 'kelas' => '10', 'jurusan' => null],
            ['name' => 'Intan Sari', 'email' => 'siswa09@smk-mutiara.sch.id', 'nis_nip' => '10230009', 'kelas' => '12', 'jurusan' => 'RPL'],
            ['name' => 'Joko Wicaksono', 'email' => 'siswa10@smk-mutiara.sch.id', 'nis_nip' => '10230010', 'kelas' => '12', 'jurusan' => 'AKL'],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => $defaultPassword,
                    'role' => 'murid',
                    'is_active' => true,
                    'nis_nip' => $student['nis_nip'],
                    'kelas' => $student['kelas'],
                    'jurusan' => $student['jurusan'],
                ]
            );
        }

        $categories = [
            ['nama_kategori' => 'Fasilitas Kelas', 'deskripsi' => 'Kerusakan meja, kursi, proyektor.'],
            ['nama_kategori' => 'Fasilitas Umum', 'deskripsi' => 'Toilet, lapangan, kantin.'],
            ['nama_kategori' => 'Fasilitas Lab', 'deskripsi' => 'Komputer, AC lab, kabel jaringan.'],
        ];

        foreach ($categories as $category) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $category['nama_kategori']],
                ['deskripsi' => $category['deskripsi']]
            );
        }
    }
}
