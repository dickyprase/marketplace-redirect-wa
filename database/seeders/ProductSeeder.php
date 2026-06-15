<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $atasan = Category::where('name', 'Atasan')->first();
        $aksesoris = Category::where('name', 'Aksesoris')->first();
        $bawahan = Category::where('name', 'Bawahan')->first();

        $newArrivals = Tag::where('name', 'New Arrivals')->first();
        $bestSeller  = Tag::where('name', 'Best Seller')->first();
        $bigSale     = Tag::where('name', 'Big Sale')->first();

        $disk = Storage::disk('public');
        if (! $disk->exists('products')) {
            $disk->makeDirectory('products');
        }

        $sourceDir = base_path('front-end/assets/img/product');
        $productImages = ['dress1.webp', 'dress2.webp', 'dress3.webp', 'dress4.webp', 'dress5.webp'];

        // Salin semua gambar produk dari front-end ke storage (sekali saja).
        foreach ($productImages as $file) {
            $src = $sourceDir . DIRECTORY_SEPARATOR . $file;
            $destAbs = storage_path('app/public/products/' . $file);
            if (file_exists($src) && ! file_exists($destAbs)) {
                File::copy($src, $destAbs);
            }
        }

        // ─── Hoodie (Atasan, pre-order, dengan ukuran) ────────────────────
        $hoodie = Product::updateOrCreate(
            ['name' => 'Hoodie Limited Edition'],
            [
                'category_id'  => $atasan?->id,
                'description'  => '<p>Hoodie katun fleece premium edisi terbatas. '
                    . 'Produksi dibuka melalui sistem <strong>pre-order</strong>.</p>',
                'size_chart'   => '<table><thead><tr><th>Ukuran</th><th>Lebar Dada (cm)</th><th>Panjang (cm)</th></tr></thead>'
                    . '<tbody><tr><td>M</td><td>52</td><td>68</td></tr>'
                    . '<tr><td>L</td><td>55</td><td>71</td></tr>'
                    . '<tr><td>XL</td><td>58</td><td>74</td></tr></tbody></table>',
                'price'        => 250000,
                'stock_status' => Product::STATUS_PREORDER,
            ]
        );
        $hoodie->sizes()->delete();
        $hoodie->sizes()->createMany([
            ['label' => 'M',  'price' => 250000, 'stock_status' => Product::STATUS_PREORDER,    'sort_order' => 0],
            ['label' => 'L',  'price' => 265000, 'stock_status' => Product::STATUS_PREORDER,    'sort_order' => 1],
            ['label' => 'XL', 'price' => 280000, 'stock_status' => Product::STATUS_UNAVAILABLE, 'sort_order' => 2],
        ]);
        if ($newArrivals) $hoodie->tags()->syncWithoutDetaching([$newArrivals->id]);
        $this->attachImages($hoodie, ['dress2.webp', 'dress3.webp', 'dress1.webp']);

        // ─── Kopi (Aksesoris, tanpa ukuran) ───────────────────────────────
        $kopi = Product::updateOrCreate(
            ['name' => 'Kopi Arabika Gayo 250g'],
            [
                'category_id'  => $aksesoris?->id,
                'description'  => '<p>Biji kopi arabika single origin dari dataran tinggi Gayo, '
                    . 'dengan profil rasa cokelat dan sedikit keasaman buah. Roasting medium.</p>',
                'price'        => 85000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );
        if ($bestSeller) $kopi->tags()->syncWithoutDetaching([$bestSeller->id]);
        $this->attachImages($kopi, ['dress1.webp', 'dress4.webp']);

        // ─── Tumbler (Aksesoris, tidak tersedia) ─────────────────────────
        $tumbler = Product::updateOrCreate(
            ['name' => 'Tumbler Stainless 500ml'],
            [
                'category_id'  => $aksesoris?->id,
                'description'  => '<p>Tumbler stainless steel tahan panas dan dingin hingga 12 jam.</p>',
                'price'        => 120000,
                'stock_status' => Product::STATUS_UNAVAILABLE,
            ]
        );
        $this->attachImages($tumbler, ['dress3.webp']);

        // ─── Madu (Aksesoris, tanpa ukuran) ──────────────────────────────
        $madu = Product::updateOrCreate(
            ['name' => 'Madu Hutan Murni 500ml'],
            [
                'category_id'  => $aksesoris?->id,
                'description'  => '<p>Madu hutan murni tanpa campuran gula, dipanen langsung dari peternak lokal.</p>',
                'price'        => 95000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );
        if ($bigSale) $madu->tags()->syncWithoutDetaching([$bigSale->id]);
        $this->attachImages($madu, ['dress4.webp', 'dress5.webp']);

        // ─── Sneakers (Bawahan, dengan ukuran) ───────────────────────────
        $sepatu = Product::updateOrCreate(
            ['name' => 'Sneakers Canvas Classic'],
            [
                'category_id'  => $bawahan?->id,
                'description'  => '<p>Sneakers kanvas klasik, nyaman untuk pemakaian harian.</p>',
                'size_chart'   => '<table><thead><tr><th>Ukuran</th><th>Panjang Insole (cm)</th></tr></thead>'
                    . '<tbody><tr><td>39</td><td>24.5</td></tr><tr><td>40</td><td>25.0</td></tr>'
                    . '<tr><td>41</td><td>25.5</td></tr><tr><td>42</td><td>26.0</td></tr></tbody></table>',
                'price'        => 180000,
                'stock_status' => Product::STATUS_AVAILABLE,
            ]
        );
        $sepatu->sizes()->delete();
        $sepatu->sizes()->createMany([
            ['label' => '39', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE,    'sort_order' => 0],
            ['label' => '40', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE,    'sort_order' => 1],
            ['label' => '41', 'price' => 180000, 'stock_status' => Product::STATUS_AVAILABLE,    'sort_order' => 2],
            ['label' => '42', 'price' => 180000, 'stock_status' => Product::STATUS_UNAVAILABLE, 'sort_order' => 3],
        ]);
        if ($newArrivals) $sepatu->tags()->syncWithoutDetaching([$newArrivals->id]);
        if ($bestSeller) $sepatu->tags()->syncWithoutDetaching([$bestSeller->id]);
        $this->attachImages($sepatu, ['dress5.webp', 'dress2.webp', 'dress3.webp']);
    }

    /**
     * Attach images to a product (skip if already has images).
     * First image becomes the primary image.
     */
    private function attachImages(Product $product, array $files): void
    {
        if ($product->images()->exists()) {
            return; // sudah ada gambar, lewati
        }

        foreach ($files as $i => $file) {
            $path = 'products/' . $file;
            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $product->images()->create([
                'path'       => $path,
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }
    }
}
