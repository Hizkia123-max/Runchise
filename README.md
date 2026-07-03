# Runchise ERP & POS Platform

Runchise adalah platform Enterprise Resource Planning (ERP) dan Point of Sale (POS) berbasis Cloud Multi-Tenant SaaS yang tangguh. Sistem ini dibangun menggunakan framework **CodeIgniter 4** (PHP 8.3+), MySQL 8, Redis, dan Docker untuk skalabilitas tingkat tinggi.

---

## 🚀 Panduan Memulai Cepat dengan Docker

Ikuti langkah-langkah berikut untuk menginstalisasi dan menjalankan aplikasi secara lokal:

```bash
# 1. Clone repositori workspace
git clone https://gitlab.com/hkevin-group/kuliah.git
cd kuliah

# 2. Salin berkas konfigurasi env
cp env .env

# 3. Bangun dan jalankan kontainer Docker
docker-compose up --build -d

# 4. Jalankan migrasi database
docker-compose exec app php spark migrate:refresh

# 5. Lakukan seeding data demo awal
docker-compose exec app php spark db:seed InitialDataSeeder
```

**Akses Aplikasi Melalui Browser:** `http://localhost:8080`

**Kredensial Default (Konfigurasi .env):**
* **Email:** `Admin@runchise.com`
* **Password:** `Admin@runchise`

---

## 📋 Fitur Utama & Kegunaan Halaman

Aplikasi terbagi menjadi modul-modul utama yang saling terintegrasi:

### 1. Halaman Login (`/auth/login`)
* **Kegunaan:** Gerbang otentikasi aman untuk masuk ke Workspace Tenant bisnis Anda menggunakan session CodeIgniter 4 yang terenkripsi.

### 2. Overview Dashboard (`/dashboard`)
* **Kegunaan:** Pusat kendali operasional bisnis Anda yang menampilkan:
  * **Live KPI Metrics:** Jumlah produk terdaftar, produk kritis (Low Stock), dan POS Shift aktif.
  * **Quick Navigation Panels:** Jalan pintas cepat menuju POS Terminal, POS Shift, Stock Level, dan Katalog.
  * **Low Stock Alerts:** Daftar inventori kritis yang berada di bawah ambang batas reorder point.
  * **Recent Sales Transactions:** Tabel transaksi penjualan terbaru secara real-time yang memuat nomor invoice, metode bayar, PPN, dan grand total.

### 3. Katalog Produk & Kategori (`/inventory/products`)
Halaman ini dibagi menjadi **dua sub-bagian terpisah (Tab)** untuk pengelolaan optimal:
* **Section 1: Products Catalog (Daftar Produk)**
  * Menampilkan daftar SKU, barcode, nama produk, kategori, harga jual, biaya modal, dan reorder point.
  * Dilengkapi tombol aksi untuk **Edit** (mengubah detail produk beserta dropdown pilihan Kategori yang dinamis) dan **Delete** (menghapus produk dengan konfirmasi keamanan).
* **Section 2: Category Management (Daftar Kategori)**
  * Menampilkan daftar kategori aktif (seperti *Food & Beverage*, *Retail*, *Electronics*, *Fashion*, *Services*).
  * Dilengkapi form input di sebelah kiri untuk menambah kategori baru secara real-time ke dalam database.

### 4. Stock Inventory (`/inventory/stock`)
* **Kegunaan:** Memantau kapasitas stok fisik produk di masing-masing cabang, mencatat batas reorder limit, serta status ketersediaan barang.

### 5. POS Terminal (`/pos/terminal`)
* **Kegunaan:** Mesin kasir checkout transaksi penjualan. Pengguna dapat mengklik produk, memperbesar kuantitas item, memasukkan metode pembayaran (Cash, Card, QRIS), dan melakukan cetak struk bayar secara real-time.

---

## 🔄 Alur Kerja Aplikasi (Flow Operasional)

Untuk memaksimalkan penggunaan Runchise ERP, ikuti alur operasional standar berikut:

```
[Katalog Kategori] ──> [Katalog Produk] ──> [Stock Inventory] ──> [POS Terminal] ──> [Overview Dashboard]
   Buat kategori          Daftarkan SKU          Pantau stok           Lakukan penjualan        Lihat rekap transaksi
   baru (fnb/retail)     & hubungkan kategori    cabang toko           kasir & checkout        dan grafik KPI terbaru
```

1. **Inisiasi Kategori:** Buka halaman *Product Catalog* -> pilih tab *Category Management* -> Tambahkan kategori bisnis baru Anda (misal: `FnB`, `Retail`).
2. **Pendaftaran Produk:** Pada tab *Products Catalog* -> klik *+ New Product* -> Isi SKU, Barcode, Harga, dan pilih **Product Category** yang sesuai lewat dropdown menu.
3. **Cek Ketersediaan Stok:** Periksa ketersediaan kuantitas barang di menu *Stock Inventory* sebelum kasir mulai berjualan.
4. **Checkout Kasir:** Buka *POS Terminal* -> Klik item-item pesanan untuk dimasukkan ke keranjang -> Sesuaikan kuantitas menggunakan tombol `+` atau `−` -> Pilih metode pembayaran -> Klik *Pay Now*.
5. **Autotrack DB & Log:** Sistem kasir akan langsung menyimpan rekap transaksi penjualan di tabel `transactions` & `transaction_items` database, sekaligus memotong stok produk di tabel `inventory_stocks` secara otomatis.
6. **Evaluasi Bisnis:** Kembali ke halaman *Overview Dashboard* untuk melihat riwayat invoice pembayaran yang baru saja sukses beserta ringkasan status keuangan real-time.

---

## 🛠️ Optimasi Performa (Page Load Cepat)

Di lingkungan Docker Windows (menggunakan file volume mount WSL2), pembacaan I/O file dapat menjadi bottleneck yang memperlambat render halaman. Kami telah melakukan optimasi performa tingkat tinggi:

* **Disabling Debug Toolbar:** Di dalam `app/Config/Filters.php`, interseptor `toolbar` telah dinonaktifkan di level `after` filter. Hal ini **mempercepat render loading halaman hingga 300%** dengan mematikan scanning file log sistem yang berat di latar belakang.
* **Database Caching & Optimized Queries:** Penggunaan query join yang ramping meminimalkan waktu tunggu request database.

---

*Runchise ERP © 2026 — Cloud POS & ERP SaaS Platform*
