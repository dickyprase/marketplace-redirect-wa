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

    public function process(Request $request): RedirectResponse
    {
        if ($request->filled('product_id')) {
            return $this->processDirect($request);
        }

        return $this->processCart($request);
    }

    /* ------------------------------------------------------------------ */
    /*  Mode A: Cart checkout (multi-item)                                  */
    /* ------------------------------------------------------------------ */
    private function processCart(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'address'       => ['required', 'string', 'max:500'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $cartRaw = $request->session()->get('cart', []);

        if (empty($cartRaw)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        $items = [];
        foreach ($cartRaw as $item) {
            $product = Product::with('sizes')->find($item['product_id']);
            if (! $product) {
                continue;
            }

            $size = null;
            if (! empty($item['size_id'])) {
                $size = $product->sizes->firstWhere('id', $item['size_id']);
            }

            $orderable = $size ? $size->isOrderable() : $product->isOrderable();
            if (! $orderable) {
                continue;
            }

            $unitPrice = $size ? (float) $size->price : (float) $product->price;

            $items[] = [
                'name'       => $product->name,
                'size_label' => $size ? $size->label : null,
                'price'      => $unitPrice,
                'qty'        => (int) $item['qty'],
            ];
        }

        if (empty($items)) {
            return back()->with('error', 'Semua item di keranjang tidak tersedia.');
        }

        $message = $this->builder->buildMulti(
            $request->input('customer_name'),
            $request->input('address'),
            $request->input('notes'),
            $items
        );

        $waUrl = $this->builder->buildUrl($message);

        if ($waUrl === null) {
            return back()->with('error', 'Nomor WhatsApp admin belum dikonfigurasi.');
        }

        $request->session()->forget('cart');

        return redirect()->away($waUrl);
    }

    /* ------------------------------------------------------------------ */
    /*  Mode B: Direct checkout (single product)                            */
    /* ------------------------------------------------------------------ */
    private function processDirect(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id'    => ['required', 'integer', 'exists:products,id'],
            'size_id'       => ['nullable', 'integer', 'exists:product_sizes,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'address'       => ['required', 'string', 'max:500'],
            'notes'         => ['nullable', 'string', 'max:500'],
            'quantity'      => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $product = Product::with('sizes')->findOrFail($validated['product_id']);
        $size = $this->resolveSize($product, $validated['size_id'] ?? null);

        $orderable = $size ? $size->isOrderable() : $product->isOrderable();
        if (! $orderable) {
            return back()->withInput()
                ->with('error', 'Item ini sedang tidak tersedia dan tidak dapat dipesan.');
        }

        $message = $this->builder->build(
            $product,
            $validated['customer_name'],
            $validated['address'],
            $validated['notes'] ?? null,
            (int) $validated['quantity'],
            $size
        );

        $waUrl = $this->builder->buildUrl($message);

        if ($waUrl === null) {
            return back()->withInput()
                ->with('error', 'Nomor WhatsApp admin belum dikonfigurasi. Hubungi administrator.');
        }

        return redirect()->away($waUrl);
    }

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
