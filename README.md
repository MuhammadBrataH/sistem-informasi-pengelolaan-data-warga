# Sistem Informasi Pengelolaan Data Warga RW 04

Web application berbasis Laravel untuk mengelola data Kartu Keluarga dan data Warga pada lingkungan RW 04. Aplikasi ini dirancang untuk membantu admin RW dan admin RT melakukan pendataan, pencarian, mutasi status warga, serta pencetakan surat pengantar secara terpusat.

## Deskripsi

Aplikasi ini menyediakan dashboard ringkas yang menampilkan statistik kependudukan, data warga yang berulang tahun, serta rekap data per RT. Pengguna login berdasarkan peran, sehingga admin RW dapat mengakses seluruh data, sementara admin RT dibatasi hanya pada data RT masing-masing.

## Fitur Utama

- Autentikasi pengguna dengan login, register, reset password, dan logout.
- Role-based access untuk `admin_rw` dan `admin_rt`.
- Dashboard statistik total KK, warga hidup, warga meninggal, warga pindah, dan ulang tahun hari ini.
- Manajemen data Kartu Keluarga: tambah, lihat, ubah, hapus, dan pencarian.
- Manajemen data Warga: tambah warga baru, lihat detail, pindahkan warga ke KK lain, dan pencarian.
- Mutasi status warga tanpa menghapus data, seperti laporan meninggal, pindah, dan kembalikan status hidup.
- Cetak surat pengantar untuk warga.
- Filter data berdasarkan RT, status dasar, dan ulang tahun hari ini.
- Seed data contoh untuk akun login dan data kependudukan awal.

## Tech Stack

- Backend: PHP 8.2
- Framework: Laravel 12
- Auth scaffolding: Laravel UI
- Database: Relasional, dengan migrasi Laravel
- Frontend: Bootstrap 5, Bootstrap Icons, Sass, Vite
- Package tooling: npm, Composer
- Testing: PHPUnit
- Data dummy: Faker

## Struktur Data Inti

- `users`: menyimpan akun login, role, dan nomor RT untuk admin RT.
- `kartu_keluarga`: menyimpan nomor KK, kepala keluarga, alamat, RT, dan RW.
- `warga`: menyimpan identitas penduduk, relasi ke KK, dan status dasar seperti hidup, meninggal, atau pindah.

## Role dan Akses

- `admin_rw`: dapat melihat seluruh data RW 04.
- `admin_rt`: hanya dapat mengakses data RT miliknya sendiri.

## Requirement

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- Database server yang didukung Laravel, seperti MySQL atau MariaDB

## Instalasi

1. Clone repository ini.
2. Jalankan `composer install`.
3. Jalankan `npm install`.
4. Salin file `.env.example` menjadi `.env`.
5. Atur konfigurasi database di `.env`.
6. Jalankan `php artisan key:generate`.
7. Jalankan migrasi dan seeder dengan `php artisan migrate --seed`.
8. Jalankan build frontend dengan `npm run build` atau mode development dengan `npm run dev`.
9. Jalankan aplikasi dengan `php artisan serve`.

## Akun Demo

Seeder menyediakan akun contoh berikut:

- Admin RW: `rw@test.com` / `password`
- Admin RT 01: `rt01@test.com` / `password`
- Admin RT 02: `rt02@test.com` / `password`
- Admin RT 03: `rt03@test.com` / `password`
- Admin RT 04: `rt04@test.com` / `password`
- Admin RT 05: `rt05@test.com` / `password`

## Catatan Penggunaan

- Route utama aplikasi mengarahkan ke halaman login.
- Data warga dan KK pada seeder sudah disiapkan untuk kebutuhan demo dan pengujian fitur.
- Fitur mutasi status warga menyimpan histori data dengan mengubah status dasar, bukan menghapus data.

## Rekomendasi Nama Repository

Nama yang paling aman dan deskriptif untuk GitHub adalah `sistem-informasi-pengelolaan-data-warga-rw04`.

Jika ingin lebih singkat, opsi yang bagus adalah `si-warga-rw04` atau `pengelolaan-data-warga-rw04`.
