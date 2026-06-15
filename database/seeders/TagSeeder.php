<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = ['New Arrivals', 'Best Seller', 'Big Sale'];

        foreach ($tags as $name) {
            Tag::updateOrCreate(['name' => $name]);
        }
    }
}
