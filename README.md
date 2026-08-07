# Plastani

Plastani adalah platform digital yang dirancang untuk membantu UMKM, khususnya petani dan produsen lokal, dalam menyalurkan hasil produknya melalui jalur online. Platform ini menjadi jembatan antara penjual dan pembeli dengan fitur katalog produk, pencarian, detail produk, serta integrasi komunikasi melalui WhatsApp.

## Tujuan Proyek

Plastani bertujuan untuk:
- membantu UMKM dan petani memasarkan produk mereka secara lebih luas,
- mempermudah pembeli menemukan produk lokal berkualitas,
- menyediakan sistem administrasi untuk mengelola produk, konten, dan transaksi,
- mendukung pertumbuhan ekonomi digital bagi komunitas usaha lokal.

## Fitur Utama

- Halaman depan yang menampilkan produk, artikel, dan jurnal terbaru
- Katalog produk dengan kategori dan pencarian
- Detail produk lengkap beserta informasi penting
- Integrasi tautan WhatsApp untuk memudahkan komunikasi pembeli
- Manajemen admin untuk produk, kategori, artikel, jurnal, dan transaksi
- Fitur laporan dan analitik transaksi
- Sistem autentikasi pengguna dan admin

## Tech Stack

- Backend: PHP dengan Laravel
- Database: MySQL
- ORM: Eloquent
- Template Engine: Blade
- Frontend: HTML, CSS, JavaScript
- Storage: Laravel Filesystem
- Authentication: Laravel Auth

## Struktur Aplikasi

- app/Http/Controllers: mengatur alur halaman dan logika bisnis
- app/Models: model data untuk produk, transaksi, artikel, jurnal, dan lainnya
- database/migrations: skema database
- database/seeders: data awal untuk pengujian dan demo
- resources/views: tampilan halaman web

## Persyaratan

Sebelum menjalankan aplikasi, pastikan sistem Anda sudah memiliki:
- PHP
- Composer
- MySQL
- Web server lokal (opsional, bisa gunakan Laravel built-in server)

## Cara Menjalankan

1. Clone repository:
   ```bash
   git clone <repository-url>
   cd plastani
   ```

2. Install dependency:
   ```bash
   composer install
   ```

3. Salin file environment:
   ```bash
   copy .env.example .env
   ```

4. Konfigurasi database di file .env sesuai server MySQL Anda.

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

8. Buka browser ke alamat:
   ```bash
   http://127.0.0.1:8000
   ```

## Akun Admin Default

Jika Anda menjalankan seeder admin, akun default yang tersedia adalah:
- Email: admin@plastani.com
- Password: admin123

## Catatan

Proyek ini masih dapat dikembangkan lebih lanjut, misalnya dengan fitur checkout langsung, pembayaran online, dashboard analytics yang lebih lengkap, dan integrasi marketplace lainnya.

## Lisensi

Proyek ini disusun untuk kebutuhan pengembangan aplikasi web berbasis Laravel.
