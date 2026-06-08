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
| `size_id` | integer | opsional; **wajib** bila produk punya ukuran, harus milik produk terkait |
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

Contoh dengan ukuran (bila ada `size_id`):

```
Produk   : Hoodie Limited Edition
Ukuran: L
Harga    : Rp 265.000
QTY      : 2
Subtotal : Rp 530.000
------------------------------
*Total    : Rp 530.000*
```

**Response gagal:**

- `302` kembali ke halaman sebelumnya dengan `session('error')` bila produk `tidak tersedia`, atau bila nomor WA admin belum dikonfigurasi.
- `302` dengan error validasi (`$errors`) bila input tidak valid.

**Keamanan:** harga & status diambil ulang dari DB berdasarkan `product_id` dan `size_id` (bila ada). Nilai harga/ukuran apa pun dari client diabaikan.

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
| `description` | string | opsional, HTML dari CKEditor (akan disanitasi server-side) |
| `size_chart` | string | opsional, HTML tabel dari CKEditor (akan disanitasi) |
| `price` | numeric | wajib, min 0 — dipakai bila produk **tidak** punya ukuran |
| `stock_status` | string | wajib, salah satu: `tersedia`, `tidak tersedia`, `pre order` — dipakai bila produk tidak punya ukuran |
| `images[]` | file | opsional, image, maks 2048 KB, bisa beberapa file |
| `sizes[N][label]` | string | opsional (contoh: `sizes[0][label]=M`) |
| `sizes[N][price]` | numeric | opsional, min 0 |
| `sizes[N][stock_status]` | string | opsional: `tersedia` / `tidak tersedia` / `pre order` |

- **Response:** `302` ke `admin.products.index` dengan `session('success')`. Slug dibuat otomatis dari `name`.
- Baris `sizes` dengan label kosong diabaikan.

#### PUT/PATCH `/admin/products/{id}` — Update Produk

Field sama dengan store, dengan tambahan:

| Field | Tipe | Aturan |
|-------|------|--------|
| `primary_image` | integer | opsional, ID gambar yang dipilih sebagai gambar utama |
| `delete_images[]` | integer | opsional, array ID gambar yang akan dihapus |

Semua ukuran dihapus lalu dibuat ulang dari input `sizes`. Gambar baru ditambahkan ke gambar yang sudah ada (tidak menimpa).

- **Response:** `302` ke `admin.products.index` dengan `session('success')`.

#### DELETE `/admin/products/{id}` — Hapus Produk

Menghapus produk beserta semua file gambarnya (bila ada).

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
| `description` | text | nullable, HTML (sanitasi: tag format dasar + tabel) |
| `size_chart` | text | nullable, HTML tabel dari CKEditor (sanitasi) |
| `price` | decimal(12,2) | harga dasar (dipakai bila produk tanpa ukuran) |
| `stock_status` | enum | `tersedia` / `tidak tersedia` / `pre order` (default `tersedia`) — dipakai bila tanpa ukuran |
| `created_at`, `updated_at` | timestamp | |

### ProductImage (`product_images`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `product_id` | bigint FK → products | cascade on delete |
| `path` | string | path relatif di storage/app/public |
| `is_primary` | boolean | default false |
| `sort_order` | unsigned int | default 0 |
| timestamps | | |

### ProductSize (`product_sizes`)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | bigint | PK |
| `product_id` | bigint FK → products | cascade on delete |
| `label` | string | mis. "M", "L", "42" |
| `price` | decimal(12,2) | default 0 |
| `stock_status` | enum | `tersedia` / `tidak tersedia` / `pre order` (default `tersedia`) |
| `sort_order` | unsigned int | default 0 |
| timestamps | | |

Bila sebuah produk memiliki setidaknya satu baris `product_sizes`, maka `price` dan `stock_status` di tabel `products` **diabaikan** saat checkout — harga & status diambil dari ukuran terpilih (`size_id`).

### Setting (`settings`)

Penyimpanan key-value (di-cache).

| Kolom | Tipe |
|-------|------|
| `id` | bigint PK |
| `key` | string unik |
| `value` | text nullable |
| timestamps | |

Key yang dipakai: `whatsapp_number`, `checkout_template`.
