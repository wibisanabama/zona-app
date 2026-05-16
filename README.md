# 🏕️ Zona Adventure POS

Sistem Point of Sale (POS) dan manajemen penyewaan alat camping khusus untuk Zona Adventure - Cianjur. Dibangun menggunakan Laravel 12, Alpine.js, Tailwind CSS (v4), dan MySQL.

## Fitur Utama
- **Manajemen Master Data:** Kategori, Barang (Inventory & Stok), dan Pelanggan.
- **Sistem POS Sewa:** Pencarian barang real-time, keranjang sewa, dan perhitungan total otomatis.
- **Manajemen Pengembalian:** Pengecekan kondisi barang saat kembali, pengembalian stok otomatis, dan perhitungan denda (Late Fee 50%/hari).
- **Pembayaran:** Pencatatan uang muka (deposit), pelunasan, tunai, transfer, dan QRIS.
- **Dashboard & Laporan:** Analitik real-time sewa aktif, pendapatan hari ini, barang belum kembali (overdue), dan laporan penjualan bulanan/harian.

## Prasyarat
- PHP 8.2 atau lebih baru
- Composer
- Node.js 20+ & npm
- MySQL / MariaDB (via XAMPP atau layanan lain)

## Instalasi

1. **Clone repositori ini:**
   ```bash
   git clone https://github.com/wibisanabama/zona-app.git
   cd zona-app
   ```

2. **Install dependensi PHP & Node:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi & Seeding Database:**
   Proses ini akan membuat tabel yang dibutuhkan beserta data awal (termasuk akun user).
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build Aset Frontend (Tailwind 4 & Alpine):**
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Akses aplikasi melalui `http://127.0.0.1:8000`.

## Kredensial Default (Seeder)

Anda dapat masuk ke dalam sistem menggunakan salah satu akun berikut:

**Administrator:**
- Email: `admin@zonaadventure.id`
- Password: `password`

**Kasir:**
- Email: `kasir@zonaadventure.id`
- Password: `password`

---
*© 2026 Zona Adventure Cianjur. Dibangun sesuai dengan sistem desain AllTrails.*
