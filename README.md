# Zona Adventure POS

Aplikasi web untuk mengelola penyewaan alat camping Zona Adventure di Cianjur. Dibangun dengan Laravel 12, Blade, Alpine.js, Tailwind CSS 4, Vite, dan MySQL.

## Fitur

- Pengelolaan kategori, barang, stok, tarif sewa, deposit, dan foto barang.
- Pengelolaan pelanggan, riwayat sewa, dan daftar hitam pelanggan.
- Transaksi sewa dengan pencarian barang, keranjang, durasi, diskon, dan cetak nota.
- Pengembalian penuh atau sebagian, pencatatan kondisi barang, dan denda keterlambatan.
- Pencatatan pembayaran melalui tunai, transfer, QRIS, atau EDC.
- Dashboard serta laporan harian, bulanan, penggunaan barang, dan keterlambatan dengan ekspor CSV.

Pembayaran dicatat secara manual; aplikasi tidak terintegrasi dengan payment gateway. Denda keterlambatan dihitung sebesar 50% dari biaya sewa harian sebelum diskon untuk setiap hari terlambat.

## Hak Akses

| Peran | Akses |
| --- | --- |
| Admin | Dashboard, kategori, barang, pelanggan, transaksi sewa, pengembalian, pembayaran, dan laporan. |
| Kasir | Dashboard, pelanggan, transaksi sewa, pembatalan sewa, pengembalian, dan pencatatan pembayaran. |

Penghapusan pembayaran hanya tersedia untuk admin.

## Persyaratan

- PHP 8.2 atau lebih baru dengan ekstensi PDO MySQL.
- Composer 2.
- Node.js 20.19+ pada seri 20, atau 22.12+ beserta npm.
- MySQL.

## Instalasi

1. Clone repositori dan pasang dependensi:

   ```bash
   git clone https://github.com/wibisanabama/zona-app.git
   cd zona-app
   composer install
   npm ci
   ```

2. Salin konfigurasi dan buat kunci aplikasi:

   ```bash
   php -r "copy('.env.example', '.env');"
   php artisan key:generate
   ```

3. Buat database MySQL bernama `zona_app`. Sesuaikan konfigurasi berikut di `.env` dengan database lokal:

   ```dotenv
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=zona_app
   ```

   Isi `DB_USERNAME` dan `DB_PASSWORD` sesuai akun MySQL yang digunakan.

4. Buat tabel dan data awal pada database baru:

   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```

   Seeder membuat akun admin dan kasir, kategori, barang, serta pelanggan contoh. Konfigurasi akun awal tersedia di `database/seeders/DatabaseSeeder.php`; sesuaikan sebelum menjalankan seeder.

5. Build aset dan jalankan aplikasi:

   ```bash
   npm run build
   php artisan serve
   ```

   Buka [http://127.0.0.1:8000](http://127.0.0.1:8000), lalu masuk menggunakan akun dari seeder.

## Pengembangan

Jalankan server aplikasi, worker antrean, dan Vite secara bersamaan:

```bash
composer run dev
```

## Struktur Proyek

```text
app/Http/Controllers/  Alur aplikasi dan logika transaksi
app/Http/Requests/     Validasi input
app/Models/           Model dan relasi data
database/migrations/  Skema database
database/seeders/     Data awal
resources/views/      Halaman dan komponen Blade
resources/css/        Gaya tampilan
resources/js/         Inisialisasi JavaScript
routes/web.php        Route dan pembatasan akses
```
