<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# SIPENGMAS – Sistem Penelitian dan Pengabdian FTI

Tim INOVIX. Aplikasi web Laravel untuk pengelolaan proposal penelitian dan pengabdian.

## Kebutuhan
- PHP [versi, lihat composer.json] dan Composer
- Laravel Herd (atau server PHP lain)
- Basis data [MySQL/PostgreSQL/SQLite, sesuaikan]

## Cara menjalankan
1. Clone repositori dan masuk ke foldernya:
   `https://github.com/Yamada-113/SIPENGMAS.git`
2. Pasang dependensi: `composer install`
3. Salin konfigurasi: `cp .env.example .env`
4. Buat kunci aplikasi: `php artisan key:generate`
5. Atur koneksi basis data di `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
6. Jalankan migration (lihat bagian di bawah).
7. Buka aplikasi di Herd: `http://sipengmas-main.test`

## Migration
Jalankan semua migration: `php artisan migrate`

Migration yang ditambahkan di Sprint 1:
| Fitur | Perubahan skema |
| --- | --- |
| A-08 Pengisian profil pengusul | Menambah kolom profil (nama, NIP, jabatan, Google Scholar ID) pada tabel user
| B-01 Pengamanan login | Menambah kolom penghitung gagal login dan penguncian akun pada tabel user 

Untuk mengulang dari awal (data lokal akan terhapus): `php artisan migrate:fresh`

## Akun uji
| Role | Email | Password |
| --- | --- | --- |
| Admin | admin | admin |
| Pengusul | - | - |
| Penanggung jawab | - | - |

Akun ini hanya untuk lingkungan uji.

## Menjalankan uji
`php artisan test` (jika sudah ada tes)