<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
    /**
     * List products for the admin.
     */
    public function index(): View
    {
        $products = Product::with(['images', 'sizes', 'category', 'tags'])->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the create form.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'tags'));
    }

    /**
     * Persist a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);

        $product = DB::transaction(function () use ($request, $validated) {
            $product = Product::create($this->productAttributes($validated));

            $this->syncSizes($product, $validated['sizes'] ?? []);
            $product->tags()->sync($validated['tags'] ?? []);
            $this->storeImages($product, $request);
            $this->ensurePrimaryImage($product);

            return $product;
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Product $product): View
    {
        $product->load(['images', 'sizes', 'tags']);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update an existing product, including its stock status.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateData($request);

        DB::transaction(function () use ($request, $product, $validated) {
            $product->update($this->productAttributes($validated));

            $this->syncSizes($product, $validated['sizes'] ?? []);
            $product->tags()->sync($validated['tags'] ?? []);
            $this->deleteSelectedImages($product, $request);
            $this->storeImages($product, $request);
            $this->applyPrimarySelection($product, $request);
            $this->ensurePrimaryImage($product);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Delete a product and all of its image files.
     */
    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            // product_images & product_sizes ikut terhapus via cascade FK.
            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Shared validation rules for store/update.
     *
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'                 => ['required', 'string', 'max:150'],
            'description'          => ['nullable', 'string'],
            'category_id'          => ['nullable', 'integer', 'exists:categories,id'],
            'tags'                 => ['nullable', 'array'],
            'tags.*'               => ['integer', 'exists:tags,id'],
            'size_chart'           => ['nullable', 'string'],
            'price'                => ['required', 'numeric', 'min:0'],
            'stock_status'         => ['required', 'in:tersedia,tidak tersedia,pre order'],
            'images'               => ['nullable', 'array'],
            'images.*'             => ['image', 'max:2048'],
            'primary_image'        => ['nullable'],
            'delete_images'        => ['nullable', 'array'],
            'delete_images.*'      => ['integer'],
            'sizes'                => ['nullable', 'array'],
            'sizes.*.label'        => ['required_with:sizes.*.price', 'nullable', 'string', 'max:50'],
            'sizes.*.price'        => ['nullable', 'numeric', 'min:0'],
            'sizes.*.stock_status' => ['nullable', 'in:tersedia,tidak tersedia,pre order'],
        ]);
    }

    /**
     * Atribut produk dengan deskripsi & size chart yang sudah disanitasi.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function productAttributes(array $validated): array
    {
        return [
            'name'         => $validated['name'],
            'category_id'  => $validated['category_id'] ?? null,
            'description'  => ! empty($validated['description'])
                ? Purifier::clean($validated['description'], 'description')
                : null,
            'size_chart'   => ! empty($validated['size_chart'])
                ? Purifier::clean($validated['size_chart'], 'sizechart')
                : null,
            'price'        => $validated['price'],
            'stock_status' => $validated['stock_status'],
        ];
    }

    /**
     * Sinkronkan ukuran produk (hapus semua lalu buat ulang dari input valid).
     *
     * @param  array<int, array<string, mixed>>  $sizes
     */
    private function syncSizes(Product $product, array $sizes): void
    {
        $product->sizes()->delete();

        $order = 0;
        foreach ($sizes as $size) {
            $label = trim((string) ($size['label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $product->sizes()->create([
                'label'        => $label,
                'price'        => $size['price'] ?? 0,
                'stock_status' => $size['stock_status'] ?? Product::STATUS_AVAILABLE,
                'sort_order'   => $order++,
            ]);
        }
    }

    /**
     * Simpan gambar-gambar yang diunggah.
     */
    private function storeImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $maxOrder = (int) $product->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create([
                'path'       => $path,
                'is_primary' => false,
                'sort_order' => ++$maxOrder,
            ]);
        }
    }

    /**
     * Hapus gambar yang dicentang untuk dihapus (saat update).
     */
    private function deleteSelectedImages(Product $product, Request $request): void
    {
        $ids = $request->input('delete_images', []);
        if (empty($ids)) {
            return;
        }

        $images = $product->images()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    /**
     * Terapkan pilihan gambar utama (radio "primary_image" berisi ID gambar).
     */
    private function applyPrimarySelection(Product $product, Request $request): void
    {
        $primaryId = $request->input('primary_image');
        if (! $primaryId) {
            return;
        }

        $exists = $product->images()->whereKey($primaryId)->exists();
        if (! $exists) {
            return;
        }

        $product->images()->update(['is_primary' => false]);
        $product->images()->whereKey($primaryId)->update(['is_primary' => true]);
    }

    /**
     * Pastikan selalu ada satu gambar utama bila produk punya gambar.
     */
    private function ensurePrimaryImage(Product $product): void
    {
        $product->load('images');

        if ($product->images->isEmpty()) {
            return;
        }

        if ($product->images->firstWhere('is_primary', true)) {
            return;
        }

        $product->images()->whereKey($product->images->first()->id)
            ->update(['is_primary' => true]);
    }
}
