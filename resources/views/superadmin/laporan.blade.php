<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan Sarana - SMK Mutiara</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            color: #333; 
        }
        
        /* CSS Kop Surat menggunakan Tabel agar rapi saat dicetak */
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat td {
            border: none; /* Hilangkan border untuk kop surat */
            padding: 0;
        }
        .logo-sekolah {
            width: 90px; /* Atur ukuran logo di sini */
            height: auto;
        }
        .teks-kop {
            text-align: center;
        }
        .teks-kop h1 { 
            margin: 0; 
            font-size: 24px; 
            text-transform: uppercase; 
        }
        .teks-kop h2 { 
            margin: 5px 0; 
            font-size: 20px; 
        }
        .teks-kop p { 
            margin: 0; 
            font-size: 14px; 
        }

        /* CSS Tabel Data */
        .table-data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        .table-data th, .table-data td { 
            border: 1px solid #000; 
            padding: 10px; 
            text-align: left; 
            font-size: 14px; 
        }
        .table-data th { 
            background-color: #f2f2f2; 
            text-align: center;
        }
        
        .footer { 
            margin-top: 50px; 
            text-align: right; 
        }
        .footer p { 
            margin-bottom: 80px; 
        }

        /* Tombol dan utilitas */
        .btn-print {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        /* Hilangkan elemen yang tidak perlu saat di-print PDF */
        @media print { 
            .no-print { display: none; } 
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <!-- <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button> -->
    </div>

    <table class="kop-surat">
        <tr>
            <td style="width: 15%; text-align: center;">
                <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" class="logo-sekolah" 
                     onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_Tut_Wuri_Handayani.png/120px-Logo_Tut_Wuri_Handayani.png';">
            </td>
            
            <td style="width: 85%;" class="teks-kop">
                <h1>Laporan Rekapitulasi Pengaduan Sarana</h1>
                <h2>SMK MUTIARA BANDUNG</h2>
                <p>Jl.Maleber Utara No 37, Bandung, Jawa Barat 401813</p>
                <p>Email: reqi2007@gmail.com| Telp: (022) 1234567</p>
            </td>
        </tr>
    </table>

    <div style="text-align: right; font-size: 12px; margin-bottom: 10px;">
        Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB
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
                    <small>NIS/NIP: {{ $item->user->nis_nip ?? '-' }}</small>
                </td>
                <td>
                    <strong>{{ $item->kategori->nama_kategori ?? 'Umum' }}</strong><br>
                    {{ $item->lokasi }}
                </td>
                <td>{{ $item->keterangan }}</td>
                <td style="text-align: center;">
                    @if($item->status == 'Selesai')
                        <strong>Selesai</strong>
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

</body>
</html>