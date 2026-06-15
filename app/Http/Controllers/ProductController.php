<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Homepage: hero slider, kategori, produk + filter tag.
     */
    public function index(Request $request): View
    {
        $banners = Banner::active()->ordered()->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $activeTagSlug = $request->query('tag');
        $activeTag = $activeTagSlug ? $tags->firstWhere('slug', $activeTagSlug) : null;

        $productsQuery = Product::with(['images', 'sizes', 'tags'])->latest();

        if ($activeTag) {
            $productsQuery->whereHas('tags', fn ($q) => $q->where('tags.id', $activeTag->id));
        }

        $products = $productsQuery->limit(24)->get();

        return view('products.index', compact('banners', 'categories', 'tags', 'products', 'activeTag'));
    }

    /**
     * Halaman daftar produk per kategori.
     */
    public function category(Category $category): View
    {
        $products = Product::with(['images', 'sizes', 'tags'])
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        return view('products.category', compact('category', 'products'));
    }

    /**
     * Detail produk + form pemesanan.
     */
    public function show(Product $product): View
    {
        $product->load(['images', 'sizes', 'tags', 'category']);

        return view('products.show', compact('product'));
    }
}
