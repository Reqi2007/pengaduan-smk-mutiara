<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan Sarana - SMK Mutiara</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 50px; text-align: right; }
        .footer p { margin-bottom: 80px; }
        /* Hilangkan tombol cetak saat di-print */
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">🖨️ Cetak Laporan Sekarang</button>
        <a href="{{ route('superadmin.dashboard') }}" style="margin-left: 10px; color: #2563eb; text-decoration: none;">&larr; Kembali</a>
    </div>

    <div class="header">
        <h1>Laporan Rekapitulasi Pengaduan Sarana</h1>
        <p><strong>SMK MUTIARA BANDUNG</strong></p>
        <p>Jl. Contoh Alamat No. 123, Bandung | Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Lapor</th>
                <th>Pelapor (NIS/NIP)</th>
                <th>Kategori & Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengaduans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->user->name }} <br> <small>({{ $item->user->nis_nip }})</small></td>
                <td><strong>{{ $item->kategori->nama_kategori ?? 'Umum' }}</strong><br>{{ $item->lokasi }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Bandung, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p><strong>Super Admin (Refan Al-Kholqi)</strong></p>
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>