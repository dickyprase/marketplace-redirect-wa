# Dokumentasi API — Marketplace Redirect WA

Base URL (lokal): `http://127.0.0.1:8000`

Aplikasi ini adalah monolith Laravel + Blade. Endpoint "API" di bawah adalah route HTTP yang dipakai oleh aplikasi. Endpoint publik mengembalikan HTML; endpoint checkout mengembalikan redirect; endpoint preview admin mengembalikan JSON.

## Ringkasan Endpoint

| Method | URI | Nama Route | Auth | Response |
|--------|-----|------------|------|----------|
| GET | `/` | `products.index` | - | HTML katalog |
| GET | `/product/{slug}` | `products.show` | - | HTML detail + form |
| POST | `/checkout` | `checkout.process` | - | 302 redirect |
| GET | `/admin/products` | `admin.products.index` | auth | HTML |
| GET | `/admin/products/create` | `admin.products.create` | auth | HTML |
| POST | `/admin/products` | `admin.products.store` | auth | 302 redirect |
| GET | `/admin/products/{id}/edit` | `admin.products.edit` | auth | HTML |
| PUT/PATCH | `/admin/products/{id}` | `admin.products.update` | auth | 302 redirect |
| DELETE | `/admin/products/{id}` | `admin.products.destroy` | auth | 302 redirect |
| GET | `/admin/settings` | `admin.settings.edit` | auth | HTML |
| PUT | `/admin/settings` | `admin.settings.update` | auth | 302 redirect |
| POST | `/admin/settings/preview` | `admin.settings.preview` | auth | JSON |

> Semua request `POST`/`PUT`/`PATCH`/`DELETE` wajib menyertakan token CSRF (`_token`) karena memakai session web Laravel.

---

## Publik

### GET `/` — Katalog

Menampilkan seluruh produk (terbaru dulu) sebagai kartu dengan badge status stok.

- **Response:** `200 OK`, HTML.

### GET `/product/{slug}` — Detail Produk

Menampilkan detail produk dan form pemesanan. Route-model binding memakai kolom `slug`.

- **Parameter path:** `slug` (string) — slug unik produk.
- **Response:** `200 OK` HTML, atau `404` bila slug tidak ditemukan.
- **Catatan:** Form disembunyikan bila `stock_status = tidak tersedia`. Label tombol berubah "Pre-Order via WhatsApp" untuk produk pre-order.

### POST `/checkout` — Proses Checkout

Memvalidasi pesanan, membangun pesan dari template tersimpan, lalu **redirect** ke `wa.me`.

**Body (form-urlencoded):**

| Field | Tipe | Aturan |
|-------|------|--------|
| `_token` | string | CSRF token (wajib) |
| `product_id` | integer | wajib, harus ada di tabel `products` |
| `customer_name` | string | wajib, maks 100 |
| `notes` | string | opsional, maks 500 |
| `quantity` | integer | wajib, min 1, maks 1000 |

**Response sukses:** `302 Found` ke `https://wa.me/<nomor>?text=<pesan-terurlencode>`.

Contoh header `Location`:

```
https://wa.me/6281234567890?text=%2APesanan+Baru%2A%0A%0ANama+Pembeli%3A+Budi...
```

Pesan (setelah decode) mengikuti template di **Pengaturan WA**, contoh default:

```
*Pesanan Baru*

Nama Pembeli: Budi
Status Order: REGULER

------------------------------
Produk   : Kopi Arabika Gayo 250g
Harga    : Rp 85.000
QTY      : 2
Subtotal : Rp 170.000
------------------------------
*Total    : Rp 170.000*
```

**Response gagal:**

- `302` kembali ke halaman sebelumnya dengan `session('error')` bila produk `tidak tersedia`, atau bila nomor WA admin belum dikonfigurasi.
- `302` dengan error validasi (`$errors`) bila input tidak valid.

**Keamanan:** harga & status diambil ulang dari DB berdasarkan `product_id`. Nilai harga apa pun dari client diabaikan.

---

## Admin (butuh login)

Akses tanpa login akan di-redirect ke `/login`.

### Produk

#### POST `/admin/products` — Tambah Produk

**Body (multipart/form-data):**

| Field | Tipe | Aturan |
|-------|------|--------|
| `_token` | string | CSRF token |
| `name` | string | wajib, maks 150 |
| `description` | string | opsional |
| `price` | numeric | wajib, min 0 |
| `stock_status` | string | wajib, salah satu: `tersedia`, `tidak tersedia`, `pre order` |
| `image` | file | opsional, image, maks 2048 KB |

- **Response:** `302` ke `admin.products.index` dengan `session('success')`. Slug dibuat otomatis dari `name`.

#### PUT/PATCH `/admin/products/{id}` — Update Produk

Field sama dengan store. `image` opsional; bila diisi, gambar lama dihapus dan diganti. Termasuk mengubah `stock_status`.

- **Response:** `302` ke `admin.products.index` dengan `session('success')`.

#### DELETE `/admin/products/{id}` — Hapus Produk

Menghapus produk beserta file gambarnya (bila ada).

- **Body:** `_token`, `_method=DELETE`.
- **Response:** `302` ke `admin.products.index` dengan `session('success')`.

### Pengaturan WhatsApp

#### PUT `/admin/settings` — Simpan Pengaturan

**Body (form-urlencoded):**

| Field | Tipe | Aturan |
|-------|------|--------|
| `_token` | string | CSRF token |
| `_method` | string | `PUT` |
| `whatsapp_number` | string | wajib, regex `^[0-9]{8,20}$` (angka saja) |
| `checkout_template` | string | wajib, maks 5000 |

- **Response:** `302` ke `admin.settings.edit` dengan `session('success')`.
- **Validasi gagal:** `302` balik dengan `$errors` (mis. nomor mengandang `+`/spasi ditolak).

#### POST `/admin/settings/preview` — Pratinjau Template

Merender template memakai data contoh dan mengonversi styling WhatsApp ke HTML. Dipakai oleh editor untuk live preview (AJAX).

**Request (JSON):**

```json
{ "checkout_template": "*Halo* {customer_name}, total {total}" }
```

Header: `Content-Type: application/json`, `X-CSRF-TOKEN: <token>`, `Accept: application/json`.

**Response `200 OK` (JSON):**

```json
{ "html": "<strong>Halo</strong> Budi Santoso, total Rp 255.000" }
```

Data contoh yang dipakai untuk placeholder:

| Placeholder | Nilai contoh |
|-------------|--------------|
| `{customer_name}` | Budi Santoso |
| `{notes}` | Tolong dikirim cepat |
| `{order_status}` | REGULER |
| `{product_name}` | Kopi Arabika Gayo 250g |
| `{price}` | Rp 85.000 |
| `{quantity}` | 3 |
| `{subtotal}` / `{total}` | Rp 255.000 |

---

## Model Data

### Product (`products`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `name` | string | |
| `slug` | string | unik, dibuat otomatis |
| `description` | text | nullable |
| `price` | decimal(12,2) | |
| `stock_status` | enum | `tersedia` / `tidak tersedia` / `pre order` (default `tersedia`) |
| `image_path` | string | nullable |
| `created_at`, `updated_at` | timestamp | |

### Setting (`settings`)

Penyimpanan key-value (di-cache).

| Kolom | Tipe |
|-------|------|
| `id` | bigint PK |
| `key` | string unik |
| `value` | text nullable |
| timestamps | |

Key yang dipakai: `whatsapp_number`, `checkout_template`.
