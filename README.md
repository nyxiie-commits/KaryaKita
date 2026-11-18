# KaryaKita – Platform E-Commerce UMKM

**KaryaKita** merupakan platform e-commerce berbasis website yang dirancang untuk membantu pelaku Usaha Mikro, Kecil, dan Menengah (UMKM) dalam memasarkan serta menjual produk secara online.

Website ini menyediakan fasilitas katalog produk, pencarian produk, keranjang belanja, proses pemesanan, pembayaran, hingga pengelolaan transaksi melalui halaman administrator.

## 📌 Tentang Proyek

KaryaKita dikembangkan sebagai sistem informasi penjualan berbasis website yang bertujuan memperluas jangkauan pemasaran produk UMKM dan mempermudah proses transaksi antara penjual dan pelanggan.

Platform ini memiliki dua bagian utama, yaitu halaman pelanggan (user) dan halaman administrator (admin).

## ✨ Fitur Utama

### 1. Fitur Pelanggan
- Registrasi akun dan login pengguna.
- Melihat katalog produk UMKM.
- Menampilkan detail produk.
- Pencarian dan filter kategori produk.
- Menambahkan produk ke keranjang belanja.
- Melakukan proses checkout.
- Mengunggah bukti pembayaran.
- Melihat riwayat pesanan.
- Melihat dan mencetak bukti transaksi.
- Mengelola informasi akun pengguna.

### 2. Fitur Administrator
- Dashboard pengelolaan toko.
- Mengelola data produk.
- Mengelola kategori produk.
- Memantau pesanan pelanggan.
- Mengelola transaksi penjualan.
- Memantau status pembayaran.
- Mengelola informasi dan aktivitas toko.

### 3. Fitur Pendukung
- Antarmuka website responsif.
- Pengelolaan database menggunakan MySQL.
- Sistem autentikasi pengguna.
- Pengelolaan gambar produk.
- Integrasi informasi pengiriman dan ongkos kirim.

## 🛠️ Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP | Pemrograman sisi server |
| HTML5 | Struktur halaman website |
| CSS3 | Desain dan tampilan website |
| JavaScript | Interaksi halaman website |
| MySQL | Pengelolaan database |
| XAMPP | Server lokal untuk pengembangan |
| Visual Studio Code | Code editor |
| InfinityFree | Hosting website |

## 📂 Struktur Direktori

```text
KaryaKita/
│
├── admin/                 # Halaman administrator
├── assets/                # File CSS, JavaScript, dan aset
├── uploads/               # File unggahan
│   └── products/          # Gambar produk
│
├── index.php              # Halaman utama
├── index2.html            # Halaman alternatif
├── header.php             # Komponen header
├── footer.php             # Komponen footer
├── functions.php          # Fungsi pendukung
│
├── account.php             # Halaman akun
├── login.php               # Login pengguna
├── register.php            # Registrasi pengguna
├── logout.php              # Logout pengguna
│
├── all-products.php        # Katalog seluruh produk
├── category.php            # Kategori produk
├── product.php             # Detail produk
│
├── cart.php                 # Keranjang belanja
├── checkout.php             # Proses checkout
├── payment.php              # Pembayaran
├── orders.php               # Riwayat pesanan
├── receipt.php              # Bukti transaksi
│
├── database.sql             # Struktur database
├── config.php               # Konfigurasi database
├── .htaccess                # Konfigurasi server
└── README.md                # Dokumentasi proyek
```

## ⚙️ Persyaratan Sistem

Sebelum menjalankan KaryaKita secara lokal, pastikan perangkat telah memiliki:

- PHP 8.x atau versi yang sesuai dengan konfigurasi proyek.
- MySQL / MariaDB.
- Apache Web Server.
- XAMPP.
- Visual Studio Code atau code editor lainnya.
- Browser seperti Google Chrome atau Microsoft Edge.

## 🚀 Instalasi dan Konfigurasi

### 1. Clone Repository

Jalankan perintah berikut melalui terminal:

```bash
git clone https://github.com/nyxiie-commits/KaryaKita.git
```

### 2. Masuk ke Direktori Proyek

```bash
cd KaryaKita
```

### 3. Pindahkan Folder ke htdocs

Letakkan folder proyek ke direktori:

```text
C:\xampp\htdocs\KaryaKita
```

### 4. Konfigurasi Database

- Jalankan Apache dan MySQL melalui XAMPP.
- Buka phpMyAdmin melalui browser.
- Buat database baru sesuai konfigurasi proyek.
- Import file `database.sql`.
- Sesuaikan konfigurasi koneksi database pada `config.php`.

### 5. Jalankan Website

Buka browser dan akses:

```text
http://localhost/KaryaKita/
```

Website KaryaKita siap dijalankan melalui server lokal.

## 🌐 Deployment

KaryaKita telah dikembangkan dan dipublikasikan menggunakan layanan hosting **InfinityFree** sehingga website dapat diakses secara online melalui domain hosting yang telah dikonfigurasi.

## 🎯 Tujuan Pengembangan

Pengembangan KaryaKita bertujuan untuk:

1. Membantu UMKM memasarkan produk secara digital.
2. Mempermudah pelanggan dalam mencari dan membeli produk.
3. Meningkatkan efisiensi pengelolaan transaksi penjualan.
4. Menyediakan sistem pengelolaan produk dan pesanan secara terintegrasi.
5. Mendukung perkembangan digitalisasi UMKM di Indonesia.

## 👨‍💻 Pengembang

**Luqman Hakim**

Proyek ini dikembangkan sebagai bagian dari penelitian dan pengembangan sistem informasi penjualan berbasis website.

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik dan pembelajaran dalam bidang pengembangan sistem informasi berbasis website.

---

**KaryaKita – Mendukung UMKM, Mengembangkan Karya, Membangun Ekonomi Digital.**
