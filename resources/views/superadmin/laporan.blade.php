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
            background-color: #f8fafc; /* Warna latar luar kertas */
        }
        
        /* Area Kertas Laporan */
        .paper-area {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            max-width: 1000px;
            margin: 0 auto;
        }

        /* CSS Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 5px double #000; 
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
            width: 100px; 
            height: auto;
        }
        .teks-kop {
            text-align: center;
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

        /* Styling Tombol Modern */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            font-size: 14px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }
        .btn svg { width: 18px; height: 18px; }
        
        .btn-primary {
            background-color: #2563eb;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }
        .btn-primary:hover { background-color: #1d4ed8; transform: translateY(-1px); }

        .btn-secondary {
            background-color: #fff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover { background-color: #f1f5f9; color: #0f172a; }

        .btn-dark {
            background-color: #0f172a;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2);
        }
        .btn-dark:hover { background-color: #000; transform: translateY(-1px); }

        .filter-label {
            display:block; font-family: Arial, sans-serif; font-size: 12px; font-weight: 700; color: #334155; margin-bottom: 8px; text-transform: uppercase; letter-spacing: .05em;
        }
        .filter-input {
            width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 10px; font-family: Arial, sans-serif; font-size: 14px; outline: none; transition: border-color 0.2s;
        }
        .filter-input:focus { border-color: #2563eb; }

        /* Print Media Query */
        @media print { 
            .no-print { display: none !important; } 
            body { margin: 0; background-color: #fff; }
            .paper-area { padding: 0; box-shadow: none; max-width: 100%; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body>
    @php
        // Menggunakan request() fallback agar jika controller belum siap, view tetap aman
        $selectedKategoriId = request('kategori_id', $filters['kategori_id'] ?? '');
        $tanggalAwal = request('tanggal_awal', $filters['tanggal_awal'] ?? '');
        $tanggalAkhir = request('tanggal_akhir', $filters['tanggal_akhir'] ?? '');
        $selectedStatus = request('status', $filters['status'] ?? '');
        $searchQuery = request('search', $filters['search'] ?? '');
    @endphp

    <div class="no-print" style="max-width: 1000px; margin: 0 auto 20px;">
        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; box-shadow: 0 10px 25px rgba(15,23,42,0.05);">
            
            <div style="margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px;">
                <h2 style="margin: 0; font-family: Arial, sans-serif; font-size: 18px; color: #0f172a;">🎛️ Panel Laporan & Ekspor</h2>
                <p style="margin: 5px 0 0; font-family: Arial, sans-serif; font-size: 13px; color: #64748b;">Gunakan filter di bawah ini untuk menyortir data sebelum dicetak menjadi PDF.</p>
            </div>

            <form method="GET" action="{{ route('superadmin.laporan') }}" id="filterForm">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px;">
                    
                    <div>
                        <label class="filter-label">Cari Nama / Keterangan</label>
                        <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Ketik kata kunci..." class="filter-input">
                    </div>

                    <div>
                        <label class="filter-label">Kategori Kerusakan</label>
                        <select name="kategori_id" class="filter-input">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ (string) $selectedKategoriId === (string) $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="filter-label">Status Laporan</label>
                        <select name="status" class="filter-input">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ $selectedStatus == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diproses" {{ $selectedStatus == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="Selesai" {{ $selectedStatus == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Ditolak" {{ $selectedStatus == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="filter-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ $tanggalAwal }}" class="filter-input" onchange="validateDates()">
                    </div>

                    <div>
                        <label class="filter-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ $tanggalAkhir }}" class="filter-input" onchange="validateDates()">
                    </div>
                </div>

                <div style="display:flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Terapkan Filter
                        </button>
                        <a href="{{ route('superadmin.laporan') }}" class="btn btn-secondary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset
                        </a>
                    </div>
                    
                    <button type="button" onclick="prepareAndPrint()" class="btn btn-dark">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Laporan (PDF)
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="paper-area">
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
            @if($tanggalAwal && $tanggalAkhir)
                <p style="margin: 5px 0 0; font-size: 13px;">Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') }} s.d {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}</p>
            @endif
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
                            <strong style="color: #16a34a;">Selesai</strong>
                        @elseif($item->status == 'Menunggu')
                            <strong style="color: #d97706;">Menunggu</strong>
                        @elseif($item->status == 'Ditolak')
                            <strong style="color: #dc2626;">Ditolak</strong>
                        @else
                            <strong style="color: #2563eb;">{{ $item->status }}</strong>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 25px; color: #64748b; font-style: italic;">
                        -- Tidak ada data laporan yang sesuai dengan filter yang dipilih --
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Bandung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Mengetahui,</p>
            <p style="margin-top: 80px;"><strong>Kepala Sekolah / Admin</strong></p>
        </div>
    </div>

    <script>
        // Memastikan tanggal akhir tidak lebih kecil dari tanggal awal
        function validateDates() {
            const start = document.getElementById('tanggal_awal').value;
            const end = document.getElementById('tanggal_akhir').value;
            
            if (start && end && start > end) {
                alert('Tanggal Akhir tidak boleh lebih awal dari Tanggal Awal!');
                document.getElementById('tanggal_akhir').value = start;
            }
        }

        // Skrip untuk merapikan judul dokumen saat di save menjadi PDF
        function prepareAndPrint() {
            // Ubah nama file sementara saat print PDF
            const originalTitle = document.title;
            document.title = 'Laporan-Pengaduan-SMK-Mutiara-' + new Date().toISOString().split('T')[0];
            
            // Eksekusi print
            window.print();
            
            // Kembalikan judul dokumen
            setTimeout(() => {
                document.title = originalTitle;
            }, 1000);
        }
    </script>
</body>
</html>