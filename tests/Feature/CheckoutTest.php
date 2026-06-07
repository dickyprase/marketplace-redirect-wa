<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::put(Setting::WHATSAPP_NUMBER, '6281234567890');
        Setting::put(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE);
    }

    public function test_checkout_redirects_to_whatsapp_with_encoded_message(): void
    {
        $product = Product::create([
            'name' => 'Kopi Test',
            'price' => 85000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);

        $response = $this->post('/checkout', [
            'product_id' => $product->id,
            'customer_name' => 'Budi',
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $target = $response->headers->get('Location');

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $target);
        // Subtotal 85.000 x 2 = 170.000 dihitung di server.
        $this->assertStringContainsString(urlencode('Rp 170.000'), $target);
        $this->assertStringContainsString(urlencode('Budi'), $target);
        $this->assertStringContainsString(urlencode('REGULER'), $target);
    }

    public function test_checkout_uses_preorder_label_for_preorder_product(): void
    {
        $product = Product::create([
            'name' => 'Hoodie PO',
            'price' => 250000,
            'stock_status' => Product::STATUS_PREORDER,
        ]);

        $target = $this->post('/checkout', [
            'product_id' => $product->id,
            'customer_name' => 'Ani',
            'quantity' => 1,
        ])->headers->get('Location');

        $this->assertStringContainsString(urlencode('PRE-ORDER'), $target);
    }

    public function test_checkout_blocks_unavailable_product_server_side(): void
    {
        $product = Product::create([
            'name' => 'Tumbler Habis',
            'price' => 120000,
            'stock_status' => Product::STATUS_UNAVAILABLE,
        ]);

        $response = $this->from('/product/' . $product->slug)->post('/checkout', [
            'product_id' => $product->id,
            'customer_name' => 'Ani',
            'quantity' => 1,
        ]);

        // Tidak boleh redirect ke wa.me; harus kembali dengan error.
        $response->assertRedirect('/product/' . $product->slug);
        $response->assertSessionHas('error');
    }

    public function test_checkout_validates_required_fields(): void
    {
        $response = $this->post('/checkout', []);
        $response->assertSessionHasErrors(['product_id', 'customer_name', 'quantity']);
    }

    public function test_notes_line_is_omitted_when_notes_empty(): void
    {
        $product = Product::create([
            'name' => 'Madu Test',
            'price' => 95000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);

        $target = $this->post('/checkout', [
            'product_id' => $product->id,
            'customer_name' => 'Budi',
            'quantity' => 1,
        ])->headers->get('Location');

        $decoded = urldecode($target);
        $this->assertStringNotContainsString('Catatan:', $decoded);
    }
}
