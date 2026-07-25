# 🏘️ Sistem Pengelolaan Data Warga RW 04

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.0-38B2AC?style=flat-square&logo=tailwind-css)](#)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](https://opensource.org/licenses/MIT)

> **Sistem Pengelolaan Data Warga RW 04 adalah aplikasi berbasis web yang dirancang khusus untuk mendigitalisasi, mempermudah, dan merapikan administrasi kependudukan di tingkat Rukun Warga (RW) dan Rukun Tetangga (RT).**

Aplikasi ini mendesentralisasi manajemen warga, memungkinkan setiap RT mengelola warganya sendiri secara independen, sementara pihak RW tetap memiliki visibilitas penuh secara terpusat.

---

## 🛠️ Tech Stack

Aplikasi ini dibangun menggunakan arsitektur monolitik modern yang berfokus pada kecepatan pengembangan, keamanan, dan performa UI/UX yang responsif.

| Kategori | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 12** | Framework PHP modern untuk routing, ORM (Eloquent), dan backend logic. |
| **Frontend/Templating** | **Blade & Vite** | Server-side rendering (SSR) dengan bundling asset super cepat menggunakan Vite. |
| **Styling/UI** | **Tailwind CSS & Bootstrap** | Kombinasi framework CSS modern untuk layout yang indah dan responsif. |
| **Database** | **MySQL** | Relational database untuk menjaga integritas data relasi Warga dan KK. |
| **Package Manager** | **Composer & NPM** | Manajemen dependencies backend (PHP) dan frontend (JS/CSS). |

---

## ✨ Fitur Utama (Key Features)

Aplikasi ini tidak hanya mencatat data, tetapi menangani siklus hidup kependudukan secara menyeluruh:

1. 🔐 **Role-Based Access Control (RBAC)**
   Sistem membedakan akses antara **Admin RW** dan **Admin RT**. Admin RT dibatasi secara ketat hanya dapat melihat, mengedit, dan mengelola data Kartu Keluarga dan Warga yang berada di wilayah RT-nya sendiri.
2. 👨‍👩‍👧‍👦 **Manajemen Kartu Keluarga (KK)**
   Pencatatan NKK, Kepala Keluarga, dan penetapan wilayah RT/RW. Mendukung integrasi *One-to-Many* dengan entitas Warga.
3. 👤 **Pendataan Warga Komprehensif**
   Validasi NIK unik, pencatatan TTL, Pekerjaan, Agama, Status Perkawinan, hingga Status Kependudukan (Tetap/Kontrak).
4. 🔄 **Sistem Mutasi Kependudukan (Tanpa Hapus Data)**
   Aplikasi melacak rekam jejak kependudukan dengan fitur **Lapor Meninggal** dan **Lapor Pindah**. Data tidak dihapus permanen, melainkan statusnya diperbarui, menjaga validitas arsip historis. Status juga dapat **Dikembalikan (Rollback)** jika terjadi kesalahan input.
5. 🔀 **Migrasi Antar-KK (Pindah KK)**
   Fitur untuk memindahkan warga secara dinamis dari satu Kartu Keluarga (KK Lama) ke Kartu Keluarga lainnya (KK Baru).
6. 🖨️ **Cetak Surat Pengantar Otomatis**
   Otomatisasi pembuatan dan pencetakan (Print-Ready View) Surat Pengantar untuk urusan administrasi warga ke instansi kelurahan.
7. 📊 **Smart Filter & Dashboard**
   Fitur filter berdasarkan RT, Status Dasar (Hidup, Meninggal, Pindah), pencarian berbasis Nama/NIK, serta pelacakan "Warga yang Berulang Tahun Hari Ini".

---

## 🚀 Getting Started

Panduan untuk Software Engineer atau Kontributor yang ingin menjalankan project ini di komputer lokal.

### 📋 Prasyarat Sistem (Prerequisites)
- **PHP** >= 8.2
- **Composer** (PHP Package Manager)
- **Node.js** v18+ & **NPM** (Untuk kompilasi asset Vite/Tailwind)
- **MySQL / MariaDB** terinstal dan berjalan.

### ⚙️ Instalasi & Konfigurasi

**1. Clone Repositori**
```bash
git clone https://github.com/username/pengelolaan-data-warga-rw04.git
cd pengelolaan-data-warga-rw04
```

**2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies (Tailwind, Vite, dll)
npm install
```

**3. Konfigurasi Environment (Database)**
Salin file environment dan atur koneksi database Anda:
```bash
cp .env.example .env
```
Buka file `.env` dan pastikan konfigurasi berikut sesuai dengan database lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_warga_rw04
DB_USERNAME=root
DB_PASSWORD=
```

**4. Generate Key & Migrasi Database**
Aplikasi ini sudah dilengkapi dengan struktur tabel (Users, Warga, Kartu Keluarga). Jalankan perintah berikut untuk menginisialisasi skema database:
```bash
php artisan key:generate
php artisan migrate
```

*(Opsional: Jika tersedia, Anda bisa menjalankan seeder untuk data dummy)*
```bash
php artisan db:seed
```

**5. Jalankan Development Server**
Karena menggunakan Laravel Vite, Anda wajib menjalankan *dua proses server* secara bersamaan. Buka dua jendela terminal dan jalankan:

**Terminal 1 (PHP Backend Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Frontend Server):**
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser pada `http://localhost:8000`.

---

## 📖 Cara Penggunaan (Usage)

1. Buka `http://localhost:8000` dan lakukan **Login**.
2. **Dashboard**: Anda akan melihat ringkasan data warga. Gunakan filter "RT" atau "Status" (Hidup/Meninggal/Pindah) di tabel warga.
3. **Menu Kartu Keluarga**: 
   - Klik "Tambah KK" untuk mendaftarkan keluarga baru. 
   - Masuk ke Detail KK untuk menambahkan Anggota Keluarga (Warga).
4. **Menu Warga**: 
   - Gunakan tombol aksi pada tabel warga untuk "Lapor Pindah", "Lapor Meninggal", atau mencetak "Surat Pengantar".
   - Jika warga pindah keluarga (misal: menikah), gunakan fitur "Pilih & Pindahkan Warga Lama" ke KK tujuan.

---

## 🧪 Testing

Project ini mendukung environment *Automated Testing*. Untuk memastikan fungsionalitas CRUD dan Mutasi berjalan baik setelah melakukan perubahan kode, jalankan:

```bash
php artisan test
```

---

## 🤝 Contributing

Jika Anda adalah developer yang tertarik pada implementasi *e-Government* atau sistem pelayanan desa/RW, kontribusi Anda sangat dinantikan!

1. **Fork** repositori ini.
2. Buat branch baru (`git checkout -b feature/SistemIuranWarga`).
3. Lakukan **Commit** (`git commit -m 'feat: menambahkan modul sistem iuran bulanan warga'`).
4. **Push** ke branch (`git push origin feature/SistemIuranWarga`).
5. Buat **Pull Request (PR)**.

Pastikan kode Anda mengikuti standar *PSR-12* (PHP) dan menambahkan penyesuaian testing jika membuat fitur krusial.

---

## 📄 License

Sistem ini didistribusikan secara Open Source di bawah [MIT License](LICENSE). Anda bebas menggunakannya untuk kebutuhan RW Anda sendiri, memodifikasi, maupun menjadikannya sebagai bahan pembelajaran.

---

## 📬 Contact & Author

Project ini dikembangkan oleh **Muhammad Brata Hadinata**.

Jika Anda menemukan bug, memiliki pertanyaan teknis, atau membutuhkan implementasi kustom, Anda dapat menghubungi saya melalui:

- **LinkedIn**: https://www.linkedin.com/in/muhammad-brata-hadinata-05335b372/ 
- **Email**: muhammadbrata06@gmail.com
- **GitHub**: https://github.com/MuhammadBrataH 

---
<div align="center">
  <i>⭐ Jika aplikasi ini bermanfaat untuk manajemen warga di tempat Anda, jangan lupa untuk memberikan <b>Star</b> pada repositori ini! ⭐</i>
</div>
