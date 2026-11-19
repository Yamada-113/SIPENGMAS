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

## Login LDAP

Koneksi LDAP diwajibkan terenkripsi dan verifikasi sertifikat TLS aktif. Default
proyek adalah LDAPS pada port 636; administrator kampus harus mengonfirmasi mode,
host, base DN, dan sertifikat CA yang benar sebelum deployment. Jika kampus
mewajibkan StartTLS, atur `LDAP_SSL=false`, `LDAP_TLS=true`, dan port yang
ditentukan kampus di `.env`. Konfigurasi menolak jika kedua mode enkripsi mati
atau sama-sama aktif.

Lima kegagalan login berturut-turut pada kombinasi NPM/username dan IP dalam
jendela 10 menit akan memicu HTTP 429 selama 10 menit. Login yang berhasil
menghapus hitungan gagal. Kegagalan kredensial maupun backend memakai pesan
generik yang sama.

Atur host, base DN, sertifikat CA, dan bila diperlukan kredensial bind layanan
hanya di `.env` (contoh variabel ada di `.env.example`). Jangan commit nilai
kredensial. LDAP query logging nonaktif secara default.

## Migration
Jalankan semua migration: `php artisan migrate`

Migration yang ditambahkan di Sprint 1:
| Fitur | Perubahan skema |
| --- | --- |
| A-08 Pengisian profil pengusul | Menambah kolom profil (nama, NIP, jabatan, Google Scholar ID) pada tabel user
| B-01 Pengamanan login | Kolom penghitung gagal dan penguncian tetap ada pada skema; pembatasan aktif memakai cache per NPM/username dan IP. |

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