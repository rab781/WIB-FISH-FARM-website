<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

# Project Pribadi - Sistem Manajemen Keuangan dan Pesanan

## Deskripsi Proyek

Proyek ini adalah sistem manajemen keuangan dan pesanan berbasis web yang dirancang untuk membantu pengguna dalam mengelola pengeluaran, pendapatan, dan pesanan pelanggan. Sistem ini mencakup fitur-fitur seperti laporan keuangan, manajemen pengeluaran, notifikasi otomatis, dan pengelolaan stok produk.

## Fitur Utama

1. **Manajemen Keuangan**
   - Laporan keuangan dengan filter tanggal, kategori, dan pencarian.
   - Ringkasan keuangan seperti total pendapatan, pengeluaran, dan laba bersih.
   - Ekspor laporan keuangan ke format Excel.

2. **Manajemen Pesanan**
   - Pemrosesan pesanan pelanggan.
   - Pembatalan otomatis pesanan yang melewati batas waktu pembayaran.
   - Notifikasi otomatis kepada pelanggan terkait status pesanan.

3. **Manajemen Pengeluaran**
   - Tambah, edit, dan hapus pengeluaran.
   - Kategori pengeluaran yang dapat disesuaikan.
   - Integrasi dengan laporan keuangan.

4. **Pengelolaan Stok Produk**
   - Penyesuaian stok produk secara otomatis saat pesanan dibatalkan.
   - Dukungan untuk produk dengan ukuran yang berbeda.

5. **Notifikasi**
   - Notifikasi real-time untuk pelanggan terkait status pesanan.
   - Dukungan untuk berbagai jenis notifikasi (pesanan, pembayaran, dll).

## Struktur Direktori

Berikut adalah struktur direktori utama proyek ini:

```
app/
  Console/
    Commands/          # Perintah Artisan, seperti pemeriksaan pesanan kadaluarsa
  Http/
    Controllers/       # Controller untuk menangani logika aplikasi
    Middleware/        # Middleware untuk pengelolaan request
  Models/              # Model Eloquent untuk database
resources/
  views/               # File Blade untuk tampilan
routes/
  web.php              # Definisi rute web
  api.php              # Definisi rute API
config/                # Konfigurasi aplikasi
public/                # Aset publik seperti CSS, JS, dan gambar
```

## Instalasi

1. Clone repositori ini:
   ```bash
   git clone <repository-url>
   ```

2. Masuk ke direktori proyek:
   ```bash
   cd test1
   ```

3. Instal dependensi menggunakan Composer:
   ```bash
   composer install
   ```

4. Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Migrasi database:
   ```bash
   php artisan migrate
   ```

7. Jalankan server pengembangan:
   ```bash
   php artisan serve
   ```

## Cara Penggunaan

### Menjalankan Pemeriksaan Pesanan Kadaluarsa
Gunakan perintah berikut untuk memeriksa dan membatalkan pesanan yang melewati batas waktu pembayaran:
```bash
php artisan app:check-expired-orders
```

### Mengakses Laporan Keuangan
1. Masuk ke halaman admin.
2. Navigasikan ke menu "Laporan Keuangan".
3. Gunakan filter untuk menyesuaikan laporan.
4. Ekspor laporan ke Excel jika diperlukan.

### Menambahkan Pengeluaran Baru
1. Masuk ke halaman "Laporan Keuangan".
2. Klik tombol "Tambah Pengeluaran".
3. Isi formulir dan simpan.

## Teknologi yang Digunakan

- **Framework**: Laravel
- **Database**: MySQL
- **Frontend**: Blade, Tailwind CSS
- **Notifikasi**: Custom Notification Controller
- **Manajemen Stok**: Eloquent ORM

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan fork repositori ini dan kirimkan pull request Anda.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
