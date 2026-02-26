<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan Sarana - SMK Mutiara</title>
    <style>
        /* Menggunakan font standar surat resmi */
        body { 
            font-family: 'Times New Roman', Times, serif; 
            margin: 40px; 
            color: #000; 
        }
        
        /* CSS Kop Surat Diperbaiki agar rapih seperti surat dinas */
        .kop-surat {
            width: 100%;
            border-bottom: 5px double #000; /* Menggunakan garis ganda (double) */
            padding-bottom: 15px;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .kop-surat td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-sekolah {
            width: 100px; /* Ukuran logo disesuaikan */
            height: auto;
        }
        .teks-kop {
            text-align: center;
            /* Padding kanan untuk menyeimbangkan letak logo di kiri agar teks benar-benar di tengah */
            padding-right: 15%; 
        }
        .teks-kop h3 {
            margin: 0;
            font-size: 18px;
            font-family: Arial, sans-serif;
            font-weight: normal;
        }
        .teks-kop h1 { 
            margin: 2px 0; 
            font-size: 26px; 
            text-transform: uppercase; 
            font-family: Arial, sans-serif;
            font-weight: bold;
        }
        .teks-kop p { 
            margin: 2px 0; 
            font-size: 14px; 
            font-family: Arial, sans-serif;
        }

        /* CSS Tabel Data */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-family: Arial, sans-serif;
        }
        .table-data th, .table-data td { 
            border: 1px solid #000; 
            padding: 10px; 
            text-align: left; 
            font-size: 13px; 
        }
        .table-data th { 
            background-color: #f2f2f2; 
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* CSS Footer Tanda Tangan */
        .footer { 
            margin-top: 50px; 
            text-align: right; 
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .footer p { 
            margin-bottom: 80px; 
        }

        /* Tombol dan utilitas */
        .btn-print {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s;
        }
        .btn-print:hover {
            background-color: #1d4ed8;
        }
        
        /* Menyembunyikan elemen UI (seperti tombol cetak) saat disimpan menjadi PDF / Print */
        @media print { 
            .no-print { display: none !important; } 
            body { margin: 0; }
            @page { margin: 1.5cm; } /* Margin untuk PDF */
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-bottom: 30px;">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan sebagai PDF</button>
        <p style="font-family: Arial, sans-serif; font-size: 13px; color: #666; margin-top: -10px;">
            *Tip: Pada jendela print, ubah Tujuan (Destination) menjadi "Save as PDF" untuk mengunduh.
        </p>
    </div>

    <table class="kop-surat">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="logo-sekolah" 
                     onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_Tut_Wuri_Handayani.png/120px-Logo_Tut_Wuri_Handayani.png';">
            </td>
            
            <td style="width: 85%;" class="teks-kop">
                <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
                <h1>SMK MUTIARA BANDUNG</h1>
                <p>Jl. Maleber Utara No 37, Kota Bandung, Jawa Barat 40181</p>
                <p>Email: reqi2007@gmail.com | Telp: (022) 1234567</p>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-bottom: 20px; font-family: Arial, sans-serif;">
        <h2 style="margin: 0; font-size: 18px; text-decoration: underline;">REKAPITULASI PENGADUAN FASILITAS SEKOLAH</h2>
    </div>

    <div style="text-align: right; font-size: 12px; margin-bottom: 10px; font-family: Arial, sans-serif;">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal Lapor</th>
                <th style="width: 25%;">Pelapor (NIS/NIP)</th>
                <th style="width: 20%;">Kategori & Lokasi</th>
                <th style="width: 25%;">Keterangan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengaduans as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $item->user->name }}</strong><br>
                    <span style="font-size: 11px; color: #555;">NIS/NIP: {{ $item->user->nis_nip ?? '-' }}</span>
                </td>
                <td>
                    <strong>{{ $item->kategori->nama_kategori ?? 'Umum' }}</strong><br>
                    <span style="font-size: 11px; color: #555;">Lokasi: {{ $item->lokasi }}</span>
                </td>
                <td>{{ $item->keterangan }}</td>
                <td style="text-align: center;">
                    @if($item->status == 'Selesai')
                        <strong style="color: green;">Selesai</strong>
                    @else
                        {{ $item->status }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Belum ada data laporan kerusakan fasilitas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Mengetahui,</p>
        <p style="margin-top: 80px;"><strong>Kepala Sekolah / Admin</strong></p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>
</html>