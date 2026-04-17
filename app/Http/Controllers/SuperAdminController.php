<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetRequest;
use App\Models\Kategori;
use App\Models\Pengaduan; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    // 1. Menampilkan Halaman Dashboard Superadmin & Tabel User
    public function index(Request $request)
    {
        $view = $request->query('view', 'semua');
        $sort = $request->query('sort', 'terbaru');
        $kelas = $request->query('kelas', 'semua');
        $jurusan = $request->query('jurusan', 'semua');

        $query = User::whereIn('role', ['guru', 'murid']);

        if ($view === 'murid') {
            $query->where('role', 'murid');

            if ($kelas !== 'semua') {
                $query->where('kelas', $kelas);
            }

            if ($jurusan !== 'semua') {
                $query->where('jurusan', $jurusan);
            }
        } elseif ($view === 'guru') {
            $query->where('role', 'guru');
        }

        $query = match ($sort) {
            'nama_asc' => $query->orderBy('name', 'asc'),
            'nama_desc' => $query->orderBy('name', 'desc'),
            'identitas_asc' => $query->orderBy('nis_nip', 'asc'),
            'identitas_desc' => $query->orderBy('nis_nip', 'desc'),
            'kelas_asc' => $query->orderBy('kelas', 'asc')->orderBy('jurusan', 'asc')->orderBy('name', 'asc'),
            'kelas_desc' => $query->orderBy('kelas', 'desc')->orderBy('jurusan', 'desc')->orderBy('name', 'desc'),
            'jurusan_asc' => $query->orderBy('jurusan', 'asc')->orderBy('kelas', 'asc')->orderBy('name', 'asc'),
            'jurusan_desc' => $query->orderBy('jurusan', 'desc')->orderBy('kelas', 'desc')->orderBy('name', 'desc'),
            'terlama' => $query->oldest(),
            default => $query->latest(),
        };

        $users = $query->get();
        
        $resetRequests = PasswordResetRequest::with('user')
                            ->where('status', 'pending')
                            ->latest()
                            ->get();

        $counts = [
            'semua' => User::whereIn('role', ['guru', 'murid'])->count(),
            'murid' => User::where('role', 'murid')->count(),
            'guru' => User::where('role', 'guru')->count(),
        ];

        $kelasOptions = ['10', '11', '12'];
        $jurusanOptions = ['RPL', 'MPLB', 'AKL', 'TKR'];

        return view('superadmin.dashboard', compact(
            'users',
            'resetRequests',
            'view',
            'sort',
            'kelas',
            'jurusan',
            'counts',
            'kelasOptions',
            'jurusanOptions'
        ));
    }

    // 2. Menyimpan User Baru (Beserta Kelas & Jurusan)
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:guru,murid'],
        ];

        if ($request->role === 'murid') {
            $rules['nis_nip'] = ['required', 'digits:8', 'unique:users,nis_nip'];
            $rules['kelas'] = ['required', 'in:10,11,12'];
            $rules['jurusan'] = $request->kelas === '12'
                ? ['required', 'in:RPL,MPLB,AKL,TKR']
                : ['nullable', 'in:RPL,MPLB,AKL,TKR'];
        } else {
            $rules['nis_nip'] = ['required', 'digits:10', 'unique:users,nis_nip'];
            $rules['kelas'] = ['nullable', 'in:10,11,12'];
            $rules['jurusan'] = ['nullable', 'in:RPL,MPLB,AKL,TKR'];
        }

        $validated = $request->validate($rules);

        $kelasValue = $validated['role'] === 'murid' ? $validated['kelas'] : null;
        $jurusanValue = $validated['role'] === 'murid' && $validated['kelas'] === '12'
            ? ($validated['jurusan'] ?? null)
            : null;

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'is_active' => true,
            'nis_nip'   => $validated['nis_nip'],
            'kelas'     => $kelasValue,
            'jurusan'   => $jurusanValue,
        ]);

        return redirect()->back()->with('success', 'Akun ' . $validated['name'] . ' berhasil ditambahkan!');
    }

    // 3. Mengubah Info Akun Pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:guru,murid'],
        ];

        if ($request->role === 'murid') {
            $rules['nis_nip'] = ['required', 'digits:8', Rule::unique('users', 'nis_nip')->ignore($user->id)];
            $rules['kelas'] = ['required', 'in:10,11,12'];
            $rules['jurusan'] = $request->kelas === '12'
                ? ['required', 'in:RPL,MPLB,AKL,TKR']
                : ['nullable', 'in:RPL,MPLB,AKL,TKR'];
        } else {
            $rules['nis_nip'] = ['required', 'digits:10', Rule::unique('users', 'nis_nip')->ignore($user->id)];
            $rules['kelas'] = ['nullable', 'in:10,11,12'];
            $rules['jurusan'] = ['nullable', 'in:RPL,MPLB,AKL,TKR'];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $validated = $request->validate($rules);

        $kelasValue = $validated['role'] === 'murid' ? $validated['kelas'] : null;
        $jurusanValue = $validated['role'] === 'murid' && $validated['kelas'] === '12'
            ? ($validated['jurusan'] ?? null)
            : null;

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'nis_nip' => $validated['nis_nip'],
            'kelas' => $kelasValue,
            'jurusan' => $jurusanValue,
        ];

        if ($request->filled('password')) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->back()->with('success', 'Informasi akun ' . $user->name . ' berhasil diperbarui.');
    }

    // 4. Mengubah Status Aktif/Nonaktif User
    public function toggle($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => !$user->is_active 
        ]);

        return redirect()->back()->with('success', 'Status akun ' . $user->name . ' berhasil diperbarui.');
    }

    // 5. Menghapus Akun Pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() == $user->id) {
            return redirect()->back()->withErrors(['Maaf, Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun pengguna berhasil dihapus!');
    }

    // 6. Cetak Laporan PDF
    public function laporan(Request $request)
    {
        // 1. Validasi input filter termasuk search dan status
        $filters = $request->validate([
            'search'        => ['nullable', 'string', 'max:255'],
            'kategori_id'   => ['nullable', 'exists:kategoris,id'],
            'status'        => ['nullable', 'string'],
            'tanggal_awal'  => ['nullable', 'date'],
            'tanggal_akhir' => ['nullable', 'date', 'after_or_equal:tanggal_awal'], // Mencegah tanggal akhir lebih kecil
        ]);

        $search       = $filters['search'] ?? null;
        $kategoriId   = $filters['kategori_id'] ?? null;
        $status       = $filters['status'] ?? null;
        $tanggalAwal  = $filters['tanggal_awal'] ?? null;
        $tanggalAkhir = $filters['tanggal_akhir'] ?? null;

        // 2. Query dasar (Eager Loading untuk performa)
        $pengaduanQuery = Pengaduan::with(['user', 'kategori'])->latest();

        // 3. Terapkan Filter Pencarian (Nama Pelapor atau Keterangan Laporan)
        if ($search) {
            $pengaduanQuery->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('nis_nip', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Terapkan Filter Kategori
        if ($kategoriId) {
            $pengaduanQuery->where('kategori_id', $kategoriId);
        }

        // 5. Terapkan Filter Status
        if ($status) {
            $pengaduanQuery->where('status', $status);
        }

        // 6. Terapkan Filter Tanggal Awal
        if ($tanggalAwal) {
            $pengaduanQuery->whereDate('created_at', '>=', $tanggalAwal);
        }

        // 7. Terapkan Filter Tanggal Akhir
        if ($tanggalAkhir) {
            $pengaduanQuery->whereDate('created_at', '<=', $tanggalAkhir);
        }

        // 8. Eksekusi Query
        $pengaduans = $pengaduanQuery->get();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        // 9. Kirim data ke View
        return view('superadmin.laporan', [
            'pengaduans' => $pengaduans,
            'kategoris'  => $kategoris,
            // Kita pass kembali $request->all() agar filter tetap bertahan di input UI
            'filters'    => $request->all() 
        ]);
    }
}
