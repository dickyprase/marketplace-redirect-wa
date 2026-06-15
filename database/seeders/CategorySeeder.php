<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Atasan',   'sort_order' => 1],
            ['name' => 'Kaos',     'sort_order' => 2],
            ['name' => 'Bawahan',  'sort_order' => 3],
            ['name' => 'Outer',    'sort_order' => 4],
            ['name' => 'Aksesoris','sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }
    }
}
