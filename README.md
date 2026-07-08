
# 📚 Sistem Perpustakaan Laravel

Aplikasi manajemen perpustakaan berbasis **Laravel 13** untuk mengelola data buku, anggota, dan transaksi peminjaman/pengembalian secara digital — lengkap dengan dashboard analitik, laporan PDF, notifikasi keterlambatan, pencarian global, dan **QR Code** pada setiap transaksi.

**Mata Kuliah:** Pemrograman Website 2 (INF2419)
**NIM:** 60324059
**Nama:** Gathan Hilabi
**Dosen:** Mohammad Reza Maulana, M.Kom
**Universitas:** UIN K.H. Abdurrahman Wahid Pekalongan

> Repo ini merupakan kelanjutan dari tugas-tugas sebelumnya (Pertemuan 13–14), dengan penambahan **Pertemuan 15: Integrasi QR Code & Penyempurnaan UI Detail Transaksi**.

---

## 📸 Screenshots


---

## ✅ Features

### 🔐 Autentikasi
- [x] Login, Register, Logout (Laravel Breeze)
- [x] Reset password & verifikasi email
- [x] Update profil & ganti password

### 📊 Dashboard
- [x] Statistik ringkas — total buku, anggota aktif, total transaksi, sedang dipinjam, terlambat, transaksi hari ini
- [x] Filter periode statistik (`7 Hari`, `30 Hari`, `3 Bulan`, `6 Bulan`, `1 Tahun`, `Semua`) via AJAX tanpa reload halaman
- [x] Grafik tren peminjaman 7 hari terakhir
- [x] Widget **"Buku Terlambat"** — daftar transaksi yang telat dikembalikan
- [x] Daftar buku populer & anggota paling aktif (top 5)
- [x] Buku & anggota terbaru
- [x] Export laporan dashboard ke PDF

### 📖 Manajemen Buku
- [x] CRUD lengkap (tambah, lihat, edit, hapus)
- [x] Generate & preview kode buku otomatis, dengan validasi format kustom (`KodeBukuFormat` rule)
- [x] Pencarian buku (judul, pengarang, ISBN)
- [x] Filter buku berdasarkan kategori
- [x] Hapus massal (bulk delete)
- [x] Export data buku

### 👤 Manajemen Anggota
- [x] CRUD lengkap (tambah, lihat, edit, hapus)
- [x] Halaman detail anggota menampilkan riwayat transaksi peminjaman
- [x] Pencarian anggota (nama, email, kode anggota)
- [x] Export data anggota

### 🔄 Transaksi Peminjaman & Pengembalian
- [x] Form pinjam buku dengan generate kode transaksi otomatis
- [x] **Tandai Dikembalikan** dengan konfirmasi modal (SweetAlert2)
- [x] Perhitungan denda otomatis (Rp 5.000/hari keterlambatan), dibungkus `DB::transaction()` agar atomic
- [x] Stok buku otomatis bertambah saat buku dikembalikan
- [x] **QR Code** pada halaman detail transaksi — di-generate langsung dari kode transaksi (fitur baru Pertemuan 15)
- [x] Timeline visual peminjaman (Dipinjam → Jatuh Tempo → Dikembalikan)
- [x] Kartu info buku & anggota yang terintegrasi di halaman detail

### 📄 Laporan & Notifikasi
- [x] Filter laporan transaksi (rentang tanggal, status, anggota)
- [x] Kartu statistik total transaksi & total denda
- [x] Export laporan transaksi ke PDF (`barryvdh/laravel-dompdf`) sesuai filter aktif
- [x] Badge **"Terlambat"** otomatis di daftar transaksi & baris tabel disorot merah
- [x] Alert peringatan keterlambatan di halaman detail transaksi
- [x] Widget notifikasi "Buku Terlambat" di dashboard

### 🔍 Pencarian Global
- [x] Satu kotak pencarian untuk mencari di tiga entitas sekaligus: Buku, Anggota, dan Transaksi

---

## ⚙️ Installation

### 1. Clone repository

```bash
git clone https://github.com/G-than12/Tugas-15.git
cd Tugas-15
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi database di `.env`

```env
DB_CONNECTION=mysql
DB_DATABASE=perpustakaan_laravel
DB_USERNAME=root
DB_PASSWORD=
```

Buat database baru bernama `perpustakaan_laravel` di phpMyAdmin/MySQL sebelum lanjut ke langkah berikutnya.

### 5. Jalankan migration & seeder

```bash
php artisan migrate:fresh --seed
```

> Seeder yang tersedia (`KategoriSeeder`, `BukuSeeder`, `AnggotaSeeder`) hanya mengisi data Buku, Kategori, dan Anggota. Data transaksi peminjaman perlu dibuat manual lewat menu **"+ Pinjam Buku"** setelah login, agar ada contoh kasus (termasuk kasus terlambat) untuk pengujian.

### 6. Build asset & jalankan server

```bash
npm run build
php artisan serve
```

Aplikasi bisa diakses di `http://127.0.0.1:8000`.

> Alternatif: jalankan `composer run dev` untuk menjalankan server, queue listener, log viewer, dan Vite dev server sekaligus secara bersamaan.

---

## 🧭 Usage

1. **Login** menggunakan akun yang didaftarkan lewat halaman Register, atau buat user lewat `php artisan tinker` / seeder.
2. **Dashboard** menampilkan ringkasan kondisi perpustakaan — gunakan filter periode di bagian atas untuk melihat tren pada rentang waktu tertentu.
3. **Kelola Buku** — tambah buku baru lewat menu Buku, kode buku akan digenerate otomatis (bisa dilihat pratinjaunya sebelum disimpan).
4. **Kelola Anggota** — tambah anggota baru lewat menu Anggota; setiap kartu anggota menampilkan riwayat peminjamannya.
5. **Pinjam Buku** — buka menu Transaksi → "+ Pinjam Buku", pilih anggota dan buku, tentukan tanggal jatuh tempo.
6. **Detail Transaksi** — setiap transaksi punya halaman detail berisi info buku, info anggota, **QR Code** kode transaksi, timeline peminjaman, dan tombol "Tandai Dikembalikan".
7. **Pengembalian** — klik "Tandai Dikembalikan" pada transaksi yang masih `Dipinjam`; sistem otomatis menghitung denda (jika telat) dan menambah kembali stok buku.
8. **Laporan** — buka menu Transaksi, gunakan filter (tanggal/status/anggota), lalu klik "Export PDF" untuk mengunduh laporan sesuai filter yang aktif.
9. **Pencarian** — gunakan kotak pencarian global untuk mencari buku, anggota, atau transaksi sekaligus dalam satu tempat.

### URL Penting

| URL | Method | Keterangan |
| --- | ------ | ---------- |
| `/dashboard` | GET | Dashboard + widget statistik & buku terlambat |
| `/dashboard/data?period=` | GET | Endpoint AJAX data chart sesuai periode |
| `/dashboard/export` | GET | Export laporan dashboard ke PDF |
| `/buku` | GET | Daftar buku |
| `/buku/search?q=` | GET | Pencarian buku |
| `/anggota` | GET | Daftar anggota |
| `/transaksi` | GET | Daftar transaksi + filter laporan + export PDF |
| `/transaksi/{id}` | GET | Detail transaksi + QR Code + tombol kembalikan |
| `/transaksi/{id}/kembalikan` | POST | Proses pengembalian buku + hitung denda |
| `/transaksi/laporan/export` | GET | Download laporan transaksi sebagai PDF |
| `/search?q=` | GET | Pencarian global (buku, anggota, transaksi) |

---

## 🆕 Sorotan Pertemuan 15 — QR Code pada Transaksi

Fitur baru di pertemuan ini adalah integrasi **QR Code** ke halaman detail transaksi, sekaligus perombakan tampilan halaman tersebut menjadi lebih terstruktur (kartu info buku, kartu QR + status + aksi, kartu info anggota, kartu detail peminjaman, dan timeline).

Karena package `simplesoftwareio/simple-qrcode` sudah tidak kompatibel dengan `bacon/bacon-qr-code ^3` (versi yang dipakai Laravel terbaru), QR Code digenerate langsung lewat helper tipis di atas `bacon/bacon-qr-code`:

**`app/Helpers/QrCodeHelper.php`**

```php
class QrCodeHelper
{
    public static function generateSvg(string $text, int $size = 200, int $margin = 1): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, $margin),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($text);
    }

    public static function generateDataUri(string $text, int $size = 200, int $margin = 1): string
    {
        return 'data:image/svg+xml;base64,' . base64_encode(self::generateSvg($text, $size, $margin));
    }
}
```

Dipanggil di `resources/views/transaksi/show.blade.php` untuk menampilkan QR dari `kode_transaksi`:

```blade
<div class="bg-white p-2 rounded-lg border border-gray-100 dark:border-gray-700">
    {!! \App\Helpers\QrCodeHelper::generateSvg($transaksi->kode_transaksi, 120) !!}
</div>
```

QR Code ini merepresentasikan kode transaksi sehingga bisa dipindai (mis. lewat aplikasi scanner umum) untuk memverifikasi/mencatat kode transaksi secara cepat tanpa harus mengetik manual.

---

## 🗂️ Struktur Folder Penting

```
Tugas-15/
├── app/
│   ├── Helpers/
│   │   └── QrCodeHelper.php           ← generate QR Code (SVG) — baru Pertemuan 15
│   ├── Http/Controllers/
│   │   ├── BukuController.php
│   │   ├── AnggotaController.php
│   │   ├── TransaksiController.php
│   │   ├── DashboardController.php
│   │   └── SearchController.php
│   ├── Models/
│   │   ├── Buku.php / Anggota.php / Kategori.php / Transaksi.php
│   └── Rules/
│       └── KodeBukuFormat.php         ← validasi format kode buku
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── resources/views/
│   ├── dashboard/
│   ├── buku/ · anggota/ · kategori/
│   ├── transaksi/
│   │   ├── index.blade.php
│   │   ├── show.blade.php             ← + QR Code & UI baru
│   │   └── pdf.blade.php
│   └── search/
│
└── routes/
    └── web.php
```

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
| --- | --- |
| Backend | PHP 8.3, Laravel 13 |
| Frontend | Blade Templates, Tailwind CSS 3, Alpine.js |
| Build Tool | Vite |
| Database | MySQL |
| PDF Export | `barryvdh/laravel-dompdf ^3.1` |
| QR Code | `bacon/bacon-qr-code ^3.1` (via helper kustom) |
| UI Interaktif | SweetAlert2 |
| Testing | PHPUnit |
| Auth Scaffolding | Laravel Breeze |

---

## 👤 Author

**Gathan Hilabi**
NIM: 60324059
Program Studi Informatika — UIN K.H. Abdurrahman Wahid Pekalongan
Mata Kuliah: Pemrograman Website 2 (Dosen: Mohammad Reza Maulana, M.Kom)

GitHub: [@G-than12](https://github.com/G-than12)
