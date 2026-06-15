<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Salin gambar hero dari front-end/assets/img/hero ke storage/app/public/banners
     * dan buat record Banner aktif.
     */
    public function run(): void
    {
        $sourceDir = base_path('front-end/assets/img/hero');

        if (! is_dir($sourceDir)) {
            return;
        }

        $heroFiles = ['hero1.webp', 'hero2.webp', 'hero3.webp'];
        $disk = Storage::disk('public');

        if (! $disk->exists('banners')) {
            $disk->makeDirectory('banners');
        }

        foreach ($heroFiles as $i => $file) {
            $src = $sourceDir . DIRECTORY_SEPARATOR . $file;
            if (! file_exists($src)) {
                continue;
            }

            $destRel = 'banners/' . $file;
            $destAbs = storage_path('app/public/' . $destRel);

            if (! file_exists($destAbs)) {
                File::copy($src, $destAbs);
            }

            Banner::updateOrCreate(
                ['image_path' => $destRel],
                [
                    'link'       => null,
                    'sort_order' => $i,
                    'is_active'  => true,
                ]
            );
        }
    }
}
