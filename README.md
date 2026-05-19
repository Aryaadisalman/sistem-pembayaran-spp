# 🎓 Sistem Pembayaran SPP - Laravel 12 + Blade + Tailwind + Flowbite

Aplikasi web untuk mengelola pembayaran SPP siswa, dibangun menggunakan Laravel 12, Blade, Tailwind CSS, dan Flowbite.

---
---

## ✨ Fitur Utama

- Autentikasi pengguna (Admin, Petugas, Siswa)
- CRUD data Siswa, Kelas, Petugas, dan SPP
- Input & validasi transaksi pembayaran
- Riwayat pembayaran & laporan
- UI responsif dengan Tailwind CSS & Flowbite Components

---

## 🚀 Instalasi

1. **Clone repository:**
   ```bash
   git clone https://github.com/Aryaadisalman/sistem-pembayaran-spp.git
   cd sistem-pembayaran-spp
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Edit .env dan sesuaikan database
   ```

4. **Migrasi dan seeding database:**
   ```bash
   php artisan migrate --seed
   ```

5. **Build frontend:**
   ```bash
   npm run dev
   ```

6. **Jalankan server:**
   ```bash
   php artisan serve
   ```

---


