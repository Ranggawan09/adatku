# Adatku - Proyek Penyewaan Pakaian Adat

Ini adalah proyek penyewaan pakaian adat yang dikembangkan menggunakan framework Laravel. Aplikasi web ini menyediakan platform untuk mengelola operasi penyewaan pakaian adat.

## Features

-   Otentikasi Admin
-   Daftar dan Manajemen Pakaian Adat
-   Pemesanan dan Manajemen Reservasi
-   Panel Admin untuk mengelola pakaian adat, reservasi, dan pengguna

## Installation

1. Clone repositori: `git clone [URL_REPOSITORI_ANDA]`

2. Arahkan ke direktori proyek: `cd adatku`

3. Instal dependensi menggunakan Composer: `composer install`

4. Buat salinan file `.env.example` dan ganti namanya menjadi `.env`. Konfigurasikan pengaturan database di dalam file `.env`.

5. Buat kunci aplikasi: `php artisan key:generate`

6. Jalankan migrasi database: `php artisan migrate`

7. Isi database dengan data pakaian adat: `php artisan db:seed`

8. Instal dependensi NPM dan jalankan: `npm install && npm run dev`

9. Buat tautan simbolis untuk penyimpanan: `php artisan storage:link`

10. Jalankan server pengembangan: `php artisan serve`

11. Kunjungi `http://localhost:8000` di browser Anda untuk mengakses aplikasi.

## Usage

-   Jelajahi pakaian adat yang tersedia beserta detailnya.
-   Lakukan reservasi dengan memilih pakaian adat dan mengisi informasi yang diperlukan.
-   Pengguna admin dapat mengakses panel admin dengan mengunjungi `http://localhost:8000/admin/login` dan login menggunakan akun admin yang telah ditentukan.
-   Admin dapat mengelola pakaian adat, reservasi, dan pengguna melalui panel admin.
