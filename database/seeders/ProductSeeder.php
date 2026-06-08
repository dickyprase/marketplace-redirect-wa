<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed sample products covering every stock status, with sizes & images.
     */
    public function run(): void
    {
        // Produk dengan ukuran (harga berbeda per ukuran) + size chart.
        $hoodie = Product::updateOrCreate(
            ['name' => 'Hoodie Limited Edition'],
            [
                'description' => '<p>Hoodie katun fleece premium edisi terbatas. '
                    . 'Produksi dibuka melalui sistem <strong>pre-order</strong>.</p>',
                'size_chart' => '<table><thead><tr><th>Ukuran</th><th>Lebar Dada (cm)</th><th>Panjang (cm)</th></tr></thead>'
                    . '<tbody><tr><td>M</td><td>52</td><td>68</td></tr>'
                    . '<tr><td>L</td><td>55</td><td>71</td></tr>'
                    . '<tr><td>XL</td><td>58</td><td>74</td></tr></tbody></table>',
                'price' => 250000,
                'stock_status' => Product::STATUS_PREORDER,
            ]
        );
        $hoodie->sizes()->delete();
        $hoodie->sizes()->createMany([
            ['label' => 'M', 'price' => 250000, 'stock_status' => Product::STATUS_PREORDER, 'sort_order' => 0],
            ['label' => 'L', 'price' => 265000, 'stock_status' => Product::STATUS_PREORDER, 'sort_order' => 1],
            ['label' => 'XL', 'price' => 280000, 'stock_status' => Product::STATUS_UNAVAILABLE, 'sort_order' => 2],
        ]);

        // Produk tanpa ukuran (perilaku lama).
        Product::updateOrCreate(
            ['name' => 'Kopi Arabika Gayo 250g'],
            [
                'description' => '<p>Biji kopi arabika single origin dari dataran tinggi Gayo, '
                    . 'dengan profil rasa cokelat dan sedikit keasaman buah. Roasting medium.</p>',
                'price' => 85000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Tumbler Stainless 500ml'],
            [
                'description' => '<p>Tumbler stainless steel tahan panas dan dingin hingga 12 jam.</p>',
                'price' => 120000,
                'stock_status' => Product::STATUS_UNAVAILABLE,
            ]
        );

        Product::updateOrCreate(
            ['name' => 'Madu Hutan Murni 500ml'],
            [
                'description' => '<p>Madu hutan murni tanpa campuran gula, dipanen langsung dari peternak lokal.</p>',
                'price' => 95000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );

        // Produk dengan ukuran berharga sama (sepatu).
        $sepatu = Product::updateOrCreate(
            ['name' => 'Sneakers Canvas Classic'],
            [
                'description' => '<p>Sneakers kanvas klasik, nyaman untuk pemakaian harian.</p>',
                'size_chart' => '<table><thead><tr><th>Ukuran</th><th>Panjang Insole (cm)</th></tr></thead>'
                    . '<tbody><tr><td>39</td><td>24.5</td></tr><tr><td>40</td><td>25.0</td></tr>'
                    . '<tr><td>41</td><td>25.5</td></tr><tr><td>42</td><td>26.0</td></tr></tbody></table>',
                'price' => 180000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );
        $sepatu->sizes()->delete();
        $sepatu->sizes()->createMany([
            ['label' => '39', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE, 'sort_order' => 0],
            ['label' => '40', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE, 'sort_order' => 1],
            ['label' => '41', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE, 'sort_order' => 2],
            ['label' => '42', 'price' => 180000, 'stock_status' => Product::STATUS_UNAVAILABLE, 'sort_order' => 3],
        ]);
    }
}
