<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use App\Services\WhatsappMessageBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(private WhatsappMessageBuilder $builder)
    {
    }

    /**
     * Validate the order, build the WhatsApp message, and redirect to wa.me.
     */
    public function process(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'size_id'       => ['nullable', 'integer', 'exists:product_sizes,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'notes'         => ['nullable', 'string', 'max:500'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        // Re-fetch the product from the database. Never trust client-side price.
        $product = Product::with('sizes')->findOrFail($validated['product_id']);

        $size = $this->resolveSize($product, $validated['size_id'] ?? null);

        // Tentukan status order efektif (dari ukuran bila ada).
        $orderable = $size ? $size->isOrderable() : $product->isOrderable();

        if (! $orderable) {
            return back()
                ->withInput()
                ->with('error', 'Item ini sedang tidak tersedia dan tidak dapat dipesan.');
        }

        $message = $this->builder->build(
            $product,
            $validated['customer_name'],
            $validated['notes'] ?? null,
            (int) $validated['quantity'],
            $size
        );

        $waUrl = $this->builder->buildUrl($message);

        if ($waUrl === null) {
            return back()
                ->withInput()
                ->with('error', 'Nomor WhatsApp admin belum dikonfigurasi. Hubungi administrator.');
        }

        return redirect()->away($waUrl);
    }

    /**
     * Validasi & ambil ukuran terpilih. Ukuran wajib bila produk punya ukuran,
     * dan ukuran harus milik produk terkait.
     */
    private function resolveSize(Product $product, ?int $sizeId): ?ProductSize
    {
        if (! $product->hasSizes()) {
            return null;
        }

        if (empty($sizeId)) {
            throw ValidationException::withMessages([
                'size_id' => 'Silakan pilih ukuran terlebih dahulu.',
            ]);
        }

        $size = $product->sizes->firstWhere('id', $sizeId);

        if (! $size) {
            throw ValidationException::withMessages([
                'size_id' => 'Ukuran yang dipilih tidak valid untuk produk ini.',
            ]);
        }

        return $size;
    }
}
