<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Setting;

class WhatsappMessageBuilder
{
    /**
     * Daftar placeholder yang didukung beserta deskripsinya.
     *
     * @return array<string, string>
     */
    public static function placeholders(): array
    {
        return [
            '{customer_name}' => 'Nama pembeli',
            '{notes}'         => 'Catatan pembeli (kosong bila tidak diisi)',
            '{notes_line}'    => 'Baris "Catatan: ..." otomatis (hilang bila catatan kosong)',
            '{order_status}'  => 'REGULER atau PRE-ORDER',
            '{product_name}'  => 'Nama produk',
            '{size}'          => 'Ukuran yang dipilih (kosong bila produk tanpa ukuran)',
            '{size_line}'     => 'Baris "Ukuran: ..." otomatis (hilang bila tanpa ukuran)',
            '{price}'         => 'Harga satuan (format Rupiah)',
            '{quantity}'      => 'Jumlah / QTY',
            '{subtotal}'      => 'Subtotal (harga x qty, format Rupiah)',
            '{total}'         => 'Total keseluruhan (format Rupiah)',
        ];
    }

    /**
     * Bangun teks pesan checkout dari template tersimpan.
     */
    public function build(
        Product $product,
        string $customerName,
        ?string $notes,
        int $quantity,
        ?ProductSize $size = null
    ): string {
        // Harga & status order diambil dari ukuran terpilih bila ada.
        $unitPrice = $size ? (float) $size->price : (float) $product->price;
        $isPreOrder = $size ? $size->isPreOrder() : $product->isPreOrder();
        $subtotal = $unitPrice * $quantity;

        $notes = $notes !== null ? trim($notes) : null;
        $notesLine = ! empty($notes) ? 'Catatan: ' . $notes . "\n" : '';

        $sizeLabel = $size ? $size->label : '';
        $sizeLine = $size ? 'Ukuran: ' . $size->label . "\n" : '';

        $replacements = [
            '{customer_name}' => $customerName,
            '{notes}'         => $notes ?? '',
            '{notes_line}'    => $notesLine,
            '{order_status}'  => $isPreOrder ? 'PRE-ORDER' : 'REGULER',
            '{product_name}'  => $product->name,
            '{size}'          => $sizeLabel,
            '{size_line}'     => $sizeLine,
            '{price}'         => $this->rupiah($unitPrice),
            '{quantity}'      => (string) $quantity,
            '{subtotal}'      => $this->rupiah($subtotal),
            '{total}'         => $this->rupiah($subtotal),
        ];

        $template = Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE);

        // Normalisasi line ending dari textarea (CRLF -> LF) agar rapi di WA.
        $template = str_replace(["\r\n", "\r"], "\n", (string) $template);

        return strtr($template, $replacements);
    }

    /**
     * Bangun URL wa.me lengkap dari nomor admin + teks pesan.
     */
    public function buildUrl(string $message): ?string
    {
        $number = preg_replace('/\D+/', '', (string) Setting::get(Setting::WHATSAPP_NUMBER, ''));

        if (empty($number)) {
            return null;
        }

        return 'https://wa.me/' . $number . '?text=' . urlencode($message);
    }

    /**
     * Format angka menjadi Rupiah, contoh: "Rp 15.000".
     */
    private function rupiah(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    /**
     * Konversi styling WhatsApp menjadi HTML aman untuk preview.
     * Mendukung *bold*, _italic_, ~strikethrough~, dan ```monospace```.
     */
    public static function toHtmlPreview(string $text): string
    {
        // Escape dulu untuk mencegah XSS, baru terapkan styling.
        $escaped = e($text);

        // Monospace blok: ```teks```
        $escaped = preg_replace_callback('/```(.+?)```/s', function ($m) {
            return '<code class="bg-gray-200 rounded px-1">' . $m[1] . '</code>';
        }, $escaped);

        // Bold: *teks*
        $escaped = preg_replace('/(?<!\w)\*(?=\S)(.+?)(?<=\S)\*(?!\w)/s', '<strong>$1</strong>', $escaped);

        // Italic: _teks_
        $escaped = preg_replace('/(?<!\w)_(?=\S)(.+?)(?<=\S)_(?!\w)/s', '<em>$1</em>', $escaped);

        // Strikethrough: ~teks~
        $escaped = preg_replace('/(?<!\w)~(?=\S)(.+?)(?<=\S)~(?!\w)/s', '<del>$1</del>', $escaped);

        return nl2br($escaped);
    }
}
