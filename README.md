```
Link Drive
https://drive.google.com/drive/folders/1P69bO7fX8QA_cKf4iioPK9O7hhWWAuOe?usp=drive_link
```

```markdown
# 🎒 Panduan Instalasi Project: Sistem Pengaduan SMK Mutiara

Selamat datang! Dokumen ini berisi panduan langkah demi langkah untuk melakukan *clone* dan menjalankan project **Sistem Pengaduan SMK Mutiara** di komputer lokal (Localhost) tanpa error.

## ⚙️ Persyaratan Sistem (Prerequisites)
Sebelum menjalankan project ini, pastikan komputer/laptop sudah terinstal:
1. **XAMPP** (dengan PHP versi 8.1 atau lebih baru)
2. **Composer** (Package manager untuk PHP)
3. **Node.js & NPM** (Untuk kompilasi UI/UX Tailwind CSS)
4. **Git** (Untuk melakukan clone repository)

---

## 🚀 Langkah-Langkah Instalasi (Step-by-Step)

### 1. Clone Repository
Buka Terminal atau Command Prompt (CMD), lalu arahkan ke folder `htdocs` (jika menggunakan XAMPP):
```bash
cd C:\xampp\htdocs

```

Lakukan *clone* repository GitHub ini:

```bash
git clone https://github.com/Reqi2007/pengaduan-smk-mutiara.git

```

### 2. Masuk ke Folder Project

```bash
cd pengaduan-smk-mutiara

```

### 3. Install Dependencies PHP (Composer)

Jalankan perintah ini untuk mengunduh semua *library* PHP/Laravel yang dibutuhkan:

```bash
composer install

```

### 4. Setup File Environment (.env)

1. Salin file `.env.example` dan ubah namanya menjadi `.env`.
*(Bisa dilakukan manual via File Explorer, atau gunakan perintah CMD di bawah ini)*:
```bash
copy .env.example .env

```


2. Buka file `.env` tersebut di *Code Editor* (VS Code).
3. Cari bagian konfigurasi database, dan sesuaikan nama databasenya (misal: `pengaduan_smk`):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pengaduan_smk
DB_USERNAME=root
DB_PASSWORD=

```



### 5. Generate Application Key

Jalankan perintah ini untuk membuat kunci enkripsi keamanan aplikasi:

```bash
php artisan key:generate

```

### 6. Buat Database & Jalankan Migrasi

1. Nyalakan modul **Apache** dan **MySQL** pada aplikasi XAMPP.
2. Buka browser dan akses `http://localhost/phpmyadmin`.
3. Buat database baru dengan nama yang sama seperti di file `.env` tadi (contoh: **`pengaduan_smk`**).
4. Kembali ke Terminal/CMD, jalankan perintah migrasi beserta *Seeder* untuk mengisi data awal (data dummy):
```bash
php artisan migrate:fresh --seed

```



### 7. Hubungkan Storage (Untuk Fitur Upload Foto)

Agar foto bukti pelaporan dan foto profil bisa ditampilkan di web, jalankan perintah ini:

```bash
php artisan storage:link

```

### 8. Install & Build UI/UX Dependencies (NPM)

Aplikasi ini menggunakan Tailwind CSS dan Alpine.js untuk animasi. Jalankan perintah ini agar tampilan web tidak berantakan:

```bash
npm install
npm run build

```

### 9. Jalankan Server Lokal

Langkah terakhir, nyalakan server Laravel:

```bash
php artisan serve

```

Buka browser dan akses alamat: **`http://localhost:8000`**

---

## 🔑 Akun Uji Coba (Berdasarkan Seeder)

Untuk memudahkan proses pengujian *role* (hak akses), Anda dapat masuk (*login*) menggunakan akun-akun yang sudah otomatis terbuat berikut ini.
*(Catatan: Login menggunakan **Nama Lengkap**, bukan Email)*

**1. Akun Superadmin**

* **Nama Login:** `Refan Al-Kholqi`
* **Password:** `password123`

**2. Akun Guru / Teknisi**

* **Login dulu ke akun SuperAdmin setelah itu cari akun dummy Guru yang ingin di login atau buat baru**
* **Password:** `password123`

**3. Akun Murid**

* **Login dulu ke akun SuperAdmin setelah itu cari akun dummy siswa yang ingin di login atau buat baru**
* **Password:** `password123`

*(Semua password *default* untuk akun dummy adalah `password123`)*

---

**Dibuat oleh:** Refan
*Semoga proses pengujian berjalan lancar! Jika ada kendala, mohon pastikan versi PHP dan Node.js sudah sesuai.*

```


```
