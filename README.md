# Marketplace Redirect WA

Platform e-commerce minimalis berbasis web (MVP) yang memakai **WhatsApp URL API** (`wa.me`) sebagai jalur checkout. Tidak ada payment gateway; saat pembeli submit form pesanan, server memvalidasi data, menyusun teks pesanan yang rapi, lalu mengalihkan browser ke chat WhatsApp admin dengan pesan yang sudah terisi.

Seluruh perhitungan harga & penyusunan pesan dilakukan di sisi server untuk menjamin integritas data.

## Fitur

- **Katalog produk** dengan badge status stok berwarna (hijau `tersedia`, merah `tidak tersedia`, kuning `pre order`).
- **Halaman detail + form pemesanan** (nama pembeli, catatan, jumlah). Tombol otomatis nonaktif bila stok habis, dan berubah jadi "Pre-Order via WhatsApp" untuk produk pre-order.
- **Checkout aman**: harga diambil ulang dari database (tidak percaya input frontend), lalu redirect ke `https://wa.me/<nomor>?text=<pesan>`.
- **Dashboard admin** (Laravel Breeze): CRUD produk + upload gambar.
- **Pengaturan WhatsApp di dashboard** (bukan di `.env`):
  - Nomor WhatsApp admin bisa diubah kapan saja.
  - Template pesan checkout bisa diedit, mendukung **placeholder** dinamis.
  - Mendukung **styling WhatsApp**: `*tebal*`, `_miring_`, `~coret~`, ` ```mono``` `.
  - **Pratinjau live** ala bubble chat WhatsApp memakai data contoh.

## Tech Stack

| Komponen | Versi / Pilihan |
|----------|-----------------|
| Framework | Laravel 11 (PHP 8.2+) |
| Auth | Laravel Breeze (Blade) |
| Frontend | Blade + Tailwind CSS (Vite) + Alpine.js |
| Database | MySQL 8 |

## Prasyarat

- PHP 8.2+ dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`, `curl`, `gd`
- Composer 2.x
- Node.js 18+ & npm
- MySQL 8 (atau MariaDB)

## Instalasi

```bash
# 1. Clone & masuk folder
git clone https://github.com/<user>/marketplace-redirect-wa.git
cd marketplace-redirect-wa

# 2. Install dependency
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Sesuaikan koneksi DB di .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
#    Buat database-nya terlebih dahulu, misalnya:
#    CREATE DATABASE penjualan_wa;

# 5. Migrasi + seed data contoh (produk, admin, pengaturan WA)
php artisan migrate --seed

# 6. Build aset frontend
npm run build

# 7. Symlink storage (agar gambar produk tampil)
php artisan storage:link

# 8. Jalankan
php artisan serve
```

Buka http://127.0.0.1:8000.

### Akun admin default (hasil seeder)

| Email | Password |
|-------|----------|
| `admin@example.com` | `password` |

> Ganti kredensial ini sebelum dipakai di produksi.

## Konfigurasi WhatsApp

Nomor admin dan template pesan **diatur lewat dashboard**, bukan `.env`:

1. Login admin -> menu **Pengaturan WA** (`/admin/settings`).
2. Isi **Nomor WhatsApp Admin** (format internasional tanpa `+`, contoh `6281234567890`).
3. Edit **Template Pesan Checkout** sesuai kebutuhan dan lihat pratinjaunya secara langsung.
4. Simpan.

Nilai `WHATSAPP_ADMIN_NUMBER` di `.env` hanya dipakai sebagai nilai awal saat seeding pertama kali.

### Placeholder template

| Placeholder | Keterangan |
|-------------|------------|
| `{customer_name}` | Nama pembeli |
| `{notes}` | Catatan pembeli (kosong bila tidak diisi) |
| `{notes_line}` | Baris `Catatan: ...` otomatis (hilang bila catatan kosong) |
| `{order_status}` | `REGULER` atau `PRE-ORDER` |
| `{product_name}` | Nama produk |
| `{price}` | Harga satuan (format Rupiah) |
| `{quantity}` | Jumlah / QTY |
| `{subtotal}` | Subtotal (harga x qty) |
| `{total}` | Total keseluruhan |

### Styling teks (gaya WhatsApp)

| Tulis | Hasil |
|-------|-------|
| `*teks*` | **tebal** |
| `_teks_` | _miring_ |
| `~teks~` | ~~coret~~ |
| ` ```teks``` ` | monospace |

### Contoh template default

```
*Pesanan Baru*

Nama Pembeli: {customer_name}
{notes_line}Status Order: {order_status}

------------------------------
Produk   : {product_name}
Harga    : {price}
QTY      : {quantity}
Subtotal : {subtotal}
------------------------------
*Total    : {total}*
```

## Struktur Penting

```
app/
  Http/Controllers/
    ProductController.php          # Katalog & detail (publik)
    CheckoutController.php         # Proses checkout -> redirect wa.me
    Admin/ProductController.php    # CRUD produk (admin)
    Admin/SettingController.php    # Pengaturan WA + preview (admin)
  Models/
    Product.php                    # Produk + slug otomatis + helper Rupiah
    Setting.php                    # Key-value setting + cache
  Services/
    WhatsappMessageBuilder.php     # Template engine + styling WA -> HTML
resources/views/
  products/                        # index.blade.php, show.blade.php (publik)
  admin/products/                  # CRUD produk
  admin/settings/                  # Editor + preview
  components/                      # stock-badge, layouts/public
routes/web.php
database/
  migrations/                      # products, settings
  seeders/                         # ProductSeeder, SettingSeeder
```

## Testing

```bash
php artisan test
```

Test memakai database terpisah `penjualan_wa_test` (lihat `phpunit.xml`). Buat dulu database tersebut:

```sql
CREATE DATABASE penjualan_wa_test;
```

Cakupan test meliputi checkout (redirect wa.me, label pre-order, blokir produk habis, validasi) dan pengaturan admin (auth gate, update, validasi nomor, preview styling).

## Dokumentasi API

Lihat [`docs/API.md`](docs/API.md) untuk detail seluruh endpoint (publik & admin), parameter, dan contoh response.

## Catatan Keamanan

- Registrasi publik Breeze **aktif secara default** (`/register`). Untuk toko satu-admin, sebaiknya nonaktifkan route register di `routes/auth.php`.
- Ganti password admin default sebelum produksi.
- Harga selalu diambil ulang dari DB saat checkout; input harga dari client diabaikan.

## Lisensi

MIT.
