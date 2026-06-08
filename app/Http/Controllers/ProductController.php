<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the public product catalog.
     */
    public function index(): View
    {
        $products = Product::with(['images', 'sizes'])->latest()->get();

        return view('products.index', compact('products'));
    }

    /**
     * Display a single product with its order form.
     */
    public function show(Product $product): View
    {
        $product->load(['images', 'sizes']);

        return view('products.show', compact('product'));
    }
}
