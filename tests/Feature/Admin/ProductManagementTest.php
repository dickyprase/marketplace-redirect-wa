<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_store_sanitizes_description_and_removes_script(): void
    {
        $this->actingAs($this->admin())->post('/admin/products', [
            'name' => 'Produk XSS',
            'price' => 10000,
            'stock_status' => 'tersedia',
            'description' => '<p>Aman</p><script>alert(1)</script>',
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::firstWhere('name', 'Produk XSS');
        $this->assertNotNull($product);
        $this->assertStringNotContainsString('<script>', (string) $product->description);
        $this->assertStringContainsString('Aman', (string) $product->description);
    }

    public function test_store_persists_sizes(): void
    {
        $this->actingAs($this->admin())->post('/admin/products', [
            'name' => 'Hoodie',
            'price' => 250000,
            'stock_status' => 'tersedia',
            'sizes' => [
                ['label' => 'M', 'price' => 250000, 'stock_status' => 'tersedia'],
                ['label' => 'L', 'price' => 265000, 'stock_status' => 'pre order'],
                // Baris kosong harus diabaikan.
                ['label' => '', 'price' => '', 'stock_status' => 'tersedia'],
            ],
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::with('sizes')->firstWhere('name', 'Hoodie');
        $this->assertCount(2, $product->sizes);
        $this->assertSame('M', $product->sizes[0]->label);
        $this->assertEquals(265000, (float) $product->sizes[1]->price);
    }

    public function test_store_uploads_multiple_images_and_sets_primary(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post('/admin/products', [
            'name' => 'Produk Galeri',
            'price' => 50000,
            'stock_status' => 'tersedia',
            'images' => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.jpg'),
            ],
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::with('images')->firstWhere('name', 'Produk Galeri');
        $this->assertCount(2, $product->images);
        // Tepat satu gambar utama.
        $this->assertSame(1, $product->images->where('is_primary', true)->count());

        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->path);
        }
    }
}
