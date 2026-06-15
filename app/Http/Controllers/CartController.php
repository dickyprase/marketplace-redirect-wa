<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang (JSON).
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->buildResponse($request));
    }

    /**
     * Tambah item ke keranjang.
     */
    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'size_id'    => ['nullable', 'integer', 'exists:product_sizes,id'],
            'qty'        => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $product = Product::with('images')->findOrFail($validated['product_id']);
        $size = null;

        if ($product->hasSizes()) {
            if (empty($validated['size_id'])) {
                return response()->json(['error' => 'Pilih ukuran terlebih dahulu.'], 422);
            }
            $size = $product->sizes()->whereKey($validated['size_id'])->first();
            if (! $size) {
                return response()->json(['error' => 'Ukuran tidak valid.'], 422);
            }
        }

        $qty = (int) ($validated['qty'] ?? 1);
        $unitPrice = $size ? (float) $size->price : (float) $product->price;
        $cart = $request->session()->get('cart', []);

        // Cek apakah item yang sama sudah ada di keranjang (product + size sama).
        $key = $product->id . '_' . ($size->id ?? 0);
        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $primaryImage = $product->primaryImage;
            $cart[$key] = [
                'product_id'  => $product->id,
                'size_id'     => $size->id ?? null,
                'name'        => $product->name,
                'size_label'  => $size->label ?? null,
                'price'       => $unitPrice,
                'qty'         => $qty,
                'image'       => $primaryImage ? $primaryImage->url : null,
                'orderable'   => $size ? $size->isOrderable() : $product->isOrderable(),
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json($this->buildResponse($request));
    }

    /**
     * Update qty item.
     */
    public function update(Request $request, int $index): JsonResponse
    {
        $validated = $request->validate([
            'qty' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $cart = $request->session()->get('cart', []);
        $keys = array_keys($cart);

        if (! isset($keys[$index])) {
            return response()->json(['error' => 'Item tidak ditemukan.'], 404);
        }

        $key = $keys[$index];

        if ((int) $validated['qty'] <= 0) {
            unset($cart[$key]);
        } else {
            $cart[$key]['qty'] = (int) $validated['qty'];
        }

        $request->session()->put('cart', $cart);

        return response()->json($this->buildResponse($request));
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(Request $request, int $index): JsonResponse
    {
        $cart = $request->session()->get('cart', []);
        $keys = array_keys($cart);

        if (isset($keys[$index])) {
            unset($cart[$keys[$index]]);
            $request->session()->put('cart', $cart);
        }

        return response()->json($this->buildResponse($request));
    }

    /**
     * Bangun response JSON standar untuk keranjang.
     */
    private function buildResponse(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $lineSubtotal = $item['price'] * $item['qty'];
            $items[] = [
                'product_id'  => $item['product_id'],
                'size_id'     => $item['size_id'],
                'name'        => $item['name'],
                'size_label'  => $item['size_label'],
                'price'       => $item['price'],
                'qty'         => $item['qty'],
                'subtotal'    => $lineSubtotal,
                'image'       => $item['image'],
                'orderable'   => $item['orderable'],
            ];
            $subtotal += $lineSubtotal;
        }

        return [
            'items'    => array_values($items),
            'count'    => count($items),
            'subtotal' => $subtotal,
        ];
    }
}
