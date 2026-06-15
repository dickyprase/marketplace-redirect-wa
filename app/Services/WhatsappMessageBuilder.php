<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Setting;

class WhatsappMessageBuilder
{
    public static function placeholders(): array
    {
        return [
            '{customer_name}' => 'Nama pembeli',
            '{address}'       => 'Alamat pembeli',
            '{address_line}'  => 'Baris "Alamat: ..." otomatis',
            '{notes}'         => 'Catatan pembeli (kosong bila tidak diisi)',
            '{notes_line}'    => 'Baris "Catatan: ..." otomatis (hilang bila catatan kosong)',
            '{order_status}'  => 'REGULER atau PRE-ORDER (untuk checkout langsung)',
            '{product_name}'  => 'Nama produk (untuk checkout langsung)',
            '{size}'          => 'Ukuran yang dipilih (kosong bila produk tanpa ukuran)',
            '{size_line}'     => 'Baris "Ukuran: ..." otomatis (hilang bila tanpa ukuran)',
            '{price}'         => 'Harga satuan (format Rupiah)',
            '{quantity}'      => 'Jumlah / QTY',
            '{subtotal}'      => 'Subtotal (harga x qty, format Rupiah)',
            '{total}'         => 'Total keseluruhan (format Rupiah)',
            '{items}'         => 'Daftar item keranjang: "qty x Nama (Ukuran) - Harga" per baris',
            '{grand_total}'   => 'Total grand seluruh item keranjang (format Rupiah)',
        ];
    }

    /**
     * Bangun pesan checkout langsung (single item).
     */
    public function build(
        Product $product,
        string $customerName,
        string $address,
        ?string $notes,
        int $quantity,
        ?ProductSize $size = null
    ): string {
        $unitPrice = $size ? (float) $size->price : (float) $product->price;
        $isPreOrder = $size ? $size->isPreOrder() : $product->isPreOrder();
        $subtotal = $unitPrice * $quantity;

        $notes = $notes !== null ? trim($notes) : null;
        $notesLine = ! empty($notes) ? 'Catatan: ' . $notes . "\n" : '';

        $addressLine = 'Alamat: ' . trim($address) . "\n";

        $replacements = [
            '{customer_name}' => $customerName,
            '{address}'       => trim($address),
            '{address_line}'  => $addressLine,
            '{notes}'         => $notes ?? '',
            '{notes_line}'    => $notesLine,
            '{order_status}'  => $isPreOrder ? 'PRE-ORDER' : 'REGULER',
            '{product_name}'  => $product->name,
            '{size}'          => $size ? $size->label : '',
            '{size_line}'     => $size ? 'Ukuran: ' . $size->label . "\n" : '',
            '{price}'         => $this->rupiah($unitPrice),
            '{quantity}'      => (string) $quantity,
            '{subtotal}'      => $this->rupiah($subtotal),
            '{total}'         => $this->rupiah($subtotal),
        ];

        $template = Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE);
        $template = str_replace(["\r\n", "\r"], "\n", (string) $template);

        return strtr($template, $replacements);
    }

    /**
     * Bangun URL wa.me.
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
     * Bangun pesan multi-item dari isi keranjang.
     *
     * @param  array<int, array<string, mixed>>  $cartItems
     */
    public function buildMulti(string $customerName, string $address, ?string $notes, array $cartItems): string
    {
        $notes = $notes !== null ? trim($notes) : null;
        $notesLine = ! empty($notes) ? 'Catatan: ' . $notes . "\n" : '';

        $addressLine = 'Alamat: ' . trim($address) . "\n";

        $grandTotal = 0;
        $lines = [];
        foreach ($cartItems as $item) {
            $subtotal = (float) $item['price'] * (int) $item['qty'];
            $grandTotal += $subtotal;

            $label = $item['name'];
            if (! empty($item['size_label'])) {
                $label .= ' (' . $item['size_label'] . ')';
            }
            $lines[] = (int) $item['qty'] . 'x ' . $label . ' - ' . $this->rupiah($subtotal);
        }

        $itemsBlock = implode("\n", $lines);

        $template = Setting::get(Setting::CART_TEMPLATE, Setting::DEFAULT_CART_TEMPLATE);
        $template = str_replace(["\r\n", "\r"], "\n", (string) $template);

        return strtr($template, [
            '{customer_name}' => $customerName,
            '{address}'       => trim($address),
            '{address_line}'  => $addressLine,
            '{notes}'         => $notes ?? '',
            '{notes_line}'    => $notesLine,
            '{items}'         => $itemsBlock,
            '{grand_total}'   => $this->rupiah($grandTotal),
        ]);
    }

    private function rupiah(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    public static function toHtmlPreview(string $text): string
    {
        $escaped = e($text);

        $escaped = preg_replace_callback('/```(.+?)```/s', function ($m) {
            return '<code class="bg-gray-200 rounded px-1">' . $m[1] . '</code>';
        }, $escaped);

        $escaped = preg_replace('/(?<!\w)\*(?=\S)(.+?)(?<=\S)\*(?!\w)/s', '<strong>$1</strong>', $escaped);
        $escaped = preg_replace('/(?<!\w)_(?=\S)(.+?)(?<=\S)_(?!\w)/s', '<em>$1</em>', $escaped);
        $escaped = preg_replace('/(?<!\w)~(?=\S)(.+?)(?<=\S)~(?!\w)/s', '<del>$1</del>', $escaped);

        return nl2br($escaped);
    }
}
