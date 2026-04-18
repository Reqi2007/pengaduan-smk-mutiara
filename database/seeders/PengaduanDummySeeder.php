<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Ulasan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengaduanDummySeeder extends Seeder
{
    public function run(): void
    {
        $murids = User::query()
            ->where('role', 'murid')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        $kategoris = Kategori::query()
            ->orderBy('id')
            ->get();

        if ($murids->isEmpty() || $kategoris->isEmpty()) {
            return;
        }

        $lokasiList = [
            'Ruang Kelas 10 RPL 1', 'Ruang Kelas 10 RPL 2', 'Ruang Kelas 11 RPL 1', 'Ruang Kelas 11 RPL 2',
            'Ruang Kelas 12 RPL 1', 'Ruang Kelas 12 RPL 2', 'Laboratorium Komputer A', 'Laboratorium Komputer B',
            'Laboratorium Bahasa', 'Perpustakaan', 'Koridor Gedung A', 'Koridor Gedung B',
            'Toilet Siswa Lantai 1', 'Toilet Siswa Lantai 2', 'Toilet Guru', 'Aula Sekolah',
            'Mushola', 'Ruang BK', 'Ruang UKS', 'Ruang Tata Usaha',
            'Lapangan Upacara', 'Lapangan Olahraga', 'Kantin Utama', 'Area Parkir Siswa',
            'Area Parkir Guru', 'Pos Satpam', 'Ruang Multimedia', 'Ruang Server',
            'Tangga Gedung Timur', 'Tangga Gedung Barat',
        ];

        $kerusakanList = [
            'Lampu kelas mati sejak beberapa hari lalu dan ruangan menjadi redup saat jam pelajaran sore.',
            'Kipas angin tidak berfungsi sehingga sirkulasi udara di ruangan terasa sangat panas.',
            'Proyektor tidak menampilkan gambar dengan normal dan sering berkedip saat digunakan.',
            'Kursi siswa patah pada bagian sandaran sehingga tidak aman dipakai.',
            'Meja belajar goyang dan permukaannya retak cukup besar.',
            'Pintu toilet tidak bisa dikunci dan mengganggu kenyamanan siswa.',
            'Kran air bocor terus menerus dan membuat lantai licin.',
            'Jaringan internet di laboratorium sering putus ketika praktikum berlangsung.',
            'Beberapa komputer tidak bisa menyala saat dipakai untuk pembelajaran.',
            'AC ruangan tidak dingin dan mengeluarkan suara bising.',
            'Whiteboard sudah kusam dan sulit dibersihkan setelah dipakai menulis.',
            'Stop kontak di dinding longgar dan berisiko saat dipakai.',
            'Jendela kelas sulit ditutup rapat ketika hujan turun.',
            'Speaker ruangan pecah sehingga suara presentasi tidak jelas.',
            'Atap lorong mengalami rembes saat hujan deras.',
        ];

        $feedbackProses = [
            'Tim sarpras sudah melakukan pengecekan awal dan kebutuhan perbaikan sedang disiapkan.',
            'Laporan sudah diterima. Saat ini sedang menunggu jadwal teknisi untuk penanganan lebih lanjut.',
            'Bagian terkait sudah dihubungi dan proses tindak lanjut sedang berjalan.',
            'Kerusakan telah diverifikasi. Pengadaan komponen pengganti sedang diproses.',
            'Petugas sudah datang ke lokasi dan pekerjaan perbaikan dilakukan secara bertahap.',
        ];

        $feedbackSelesai = [
            'Perbaikan sudah selesai dilakukan dan fasilitas kembali dapat digunakan dengan normal.',
            'Komponen rusak telah diganti. Mohon bantu pantau jika ada kendala lanjutan.',
            'Area terkait sudah dibersihkan dan diuji coba, hasilnya berfungsi dengan baik.',
            'Tim sekolah telah menyelesaikan tindak lanjut dan kondisi sarpras sudah aman dipakai.',
            'Pekerjaan perbaikan rampung hari ini dan laporan ditutup sebagai selesai.',
        ];

        $komentarUlasan = [
            'Perbaikannya cepat dan hasilnya bagus.',
            'Sekarang fasilitasnya sudah nyaman dipakai lagi.',
            'Respon sekolah sangat membantu, terima kasih.',
            'Sudah berfungsi normal, semoga tetap terawat.',
            'Penanganannya rapi dan informasinya jelas.',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $murid = $murids[($i - 1) % $murids->count()];
            $kategori = $kategoris[($i - 1) % $kategoris->count()];

            $status = match (true) {
                $i <= 15 => 'Menunggu',
                $i <= 35 => 'Proses',
                default => 'Selesai',
            };

            $feedback = null;
            if ($status === 'Proses') {
                $feedback = $feedbackProses[($i - 1) % count($feedbackProses)];
            }

            if ($status === 'Selesai') {
                $feedback = $feedbackSelesai[($i - 1) % count($feedbackSelesai)];
            }

            $createdAt = Carbon::now()->subDays(51 - $i)->setTime(7 + ($i % 8), 10 + ($i % 40));
            $updatedAt = $status === 'Menunggu'
                ? $createdAt
                : (clone $createdAt)->addDays(($i % 5) + 1);

            $pengaduan = Pengaduan::updateOrCreate(
                [
                    'lokasi' => $lokasiList[($i - 1) % count($lokasiList)] . ' - Dummy #' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'keterangan' => '[Dummy Seeder ' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . '] ' . $kerusakanList[($i - 1) % count($kerusakanList)],
                ],
                [
                    'user_id' => $murid->id,
                    'kategori_id' => $kategori->id,
                    'foto' => null,
                    'status' => $status,
                    'feedback' => $feedback,
                    'feedback_foto' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]
            );

            if ($status === 'Selesai' && $i % 2 === 0) {
                Ulasan::updateOrCreate(
                    [
                        'pengaduan_id' => $pengaduan->id,
                        'user_id' => $murid->id,
                    ],
                    [
                        'rating' => (($i % 5) + 1),
                        'komentar' => $komentarUlasan[($i - 1) % count($komentarUlasan)],
                    ]
                );
            } else {
                Ulasan::query()
                    ->where('pengaduan_id', $pengaduan->id)
                    ->where('user_id', $murid->id)
                    ->delete();
            }
        }
    }
}
