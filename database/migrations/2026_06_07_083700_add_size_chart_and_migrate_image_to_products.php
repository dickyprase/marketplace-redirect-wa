<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('size_chart')->nullable()->after('description');
        });

        // Pindahkan image_path lama (bila ada) ke product_images sebagai gambar utama.
        if (Schema::hasColumn('products', 'image_path')) {
            $rows = DB::table('products')
                ->whereNotNull('image_path')
                ->where('image_path', '!=', '')
                ->get(['id', 'image_path']);

            foreach ($rows as $row) {
                DB::table('product_images')->insert([
                    'product_id' => $row->id,
                    'path'       => $row->image_path,
                    'is_primary' => true,
                    'sort_order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
            $table->dropColumn('size_chart');
        });
    }
};
