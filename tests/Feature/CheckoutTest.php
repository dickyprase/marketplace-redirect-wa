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
        Setting::put(Setting::CART_TEMPLATE, Setting::DEFAULT_CART_TEMPLATE);
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
            'address' => 'Jl. Contoh No. 1, Sidoarjo',
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $target = $response->headers->get('Location');

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $target);
        $this->assertStringContainsString(urlencode('Rp 170.000'), $target);
        $this->assertStringContainsString(urlencode('Budi'), $target);
        $this->assertStringContainsString(urlencode('REGULER'), $target);
        $this->assertStringContainsString(urlencode('Alamat'), $target);
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
            'address' => 'Jl. Test',
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
            'address' => 'Jl. Test',
            'quantity' => 1,
        ]);

        $response->assertRedirect('/product/' . $product->slug);
        $response->assertSessionHas('error');
    }

    public function test_checkout_validates_required_fields(): void
    {
        $product = Product::create([
            'name' => 'Test P', 'price' => 10000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);
        $response = $this->post('/checkout', ['product_id' => $product->id]);
        $response->assertSessionHasErrors(['customer_name', 'address', 'quantity']);
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
            'address' => 'Jl. Test',
            'quantity' => 1,
        ])->headers->get('Location');

        $decoded = urldecode($target);
        $this->assertStringNotContainsString('Catatan:', $decoded);
    }

    public function test_size_is_required_when_product_has_sizes(): void
    {
        $product = Product::create([
            'name' => 'Hoodie',
            'price' => 250000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);
        $product->sizes()->create([
            'label' => 'M', 'price' => 250000, 'stock_status' => Product::STATUS_AVAILABLE,
        ]);

        $response = $this->post('/checkout', [
            'product_id' => $product->id,
            'customer_name' => 'Budi',
            'address' => 'Jl. Test',
            'quantity' => 1,
        ]);

        $response->assertSessionHasErrors('size_id');
    }

    public function test_price_is_taken_from_selected_size(): void
    {
        $product = Product::create([
            'name' => 'Hoodie',
            'price' => 250000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);
        $sizeL = $product->sizes()->create([
            'label' => 'L', 'price' => 265000, 'stock_status' => Product::STATUS_PREORDER,
        ]);

        $target = $this->post('/checkout', [
            'product_id' => $product->id,
            'size_id' => $sizeL->id,
            'customer_name' => 'Budi',
            'address' => 'Jl. Test',
            'quantity' => 2,
        ])->headers->get('Location');

        $decoded = urldecode($target);
        $this->assertStringContainsString('Rp 530.000', $decoded);
        $this->assertStringContainsString('Ukuran: L', $decoded);
        $this->assertStringContainsString('PRE-ORDER', $decoded);
    }

    public function test_checkout_blocks_unavailable_size(): void
    {
        $product = Product::create([
            'name' => 'Sepatu',
            'price' => 180000,
            'stock_status' => Product::STATUS_AVAILABLE,
        ]);
        $sizeHabis = $product->sizes()->create([
            'label' => '42', 'price' => 180000, 'stock_status' => Product::STATUS_UNAVAILABLE,
        ]);

        $response = $this->from('/product/' . $product->slug)->post('/checkout', [
            'product_id' => $product->id,
            'size_id' => $sizeHabis->id,
            'customer_name' => 'Ani',
            'address' => 'Jl. Test',
            'quantity' => 1,
        ]);

        $response->assertRedirect('/product/' . $product->slug);
        $response->assertSessionHas('error');
    }
}
