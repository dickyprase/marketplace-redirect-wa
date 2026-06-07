<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed sample products covering every stock status.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Kopi Arabika Gayo 250g',
                'description' => 'Biji kopi arabika single origin dari dataran tinggi Gayo, '
                    . 'dengan profil rasa cokelat dan sedikit keasaman buah. Roasting medium.',
                'price' => 85000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ],
            [
                'name' => 'Tumbler Stainless 500ml',
                'description' => 'Tumbler stainless steel tahan panas dan dingin hingga 12 jam. '
                    . 'Cocok untuk dibawa bepergian.',
                'price' => 120000,
                'stock_status' => Product::STATUS_UNAVAILABLE,
            ],
            [
                'name' => 'Hoodie Limited Edition',
                'description' => 'Hoodie katun fleece premium edisi terbatas. '
                    . 'Produksi dibuka melalui sistem pre-order.',
                'price' => 250000,
                'stock_status' => Product::STATUS_PREORDER,
            ],
            [
                'name' => 'Madu Hutan Murni 500ml',
                'description' => 'Madu hutan murni tanpa campuran gula, dipanen langsung dari peternak lokal.',
                'price' => 95000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
