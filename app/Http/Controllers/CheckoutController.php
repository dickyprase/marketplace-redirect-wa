<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WhatsappMessageBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'customer_name' => ['required', 'string', 'max:100'],
            'notes'         => ['nullable', 'string', 'max:500'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        // Re-fetch the product (and its price) from the database. Never trust
        // any price submitted by the client.
        $product = Product::findOrFail($validated['product_id']);

        // Block checkout for unavailable products.
        if (! $product->isOrderable()) {
            return back()
                ->withInput()
                ->with('error', 'Produk ini sedang tidak tersedia dan tidak dapat dipesan.');
        }

        $message = $this->builder->build(
            $product,
            $validated['customer_name'],
            $validated['notes'] ?? null,
            (int) $validated['quantity']
        );

        $waUrl = $this->builder->buildUrl($message);

        if ($waUrl === null) {
            return back()
                ->withInput()
                ->with('error', 'Nomor WhatsApp admin belum dikonfigurasi. Hubungi administrator.');
        }

        return redirect()->away($waUrl);
    }
}
