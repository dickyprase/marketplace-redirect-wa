<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'size_chart',
        'price',
        'stock_status',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Stock status constants.
     */
    public const STATUS_AVAILABLE = 'tersedia';
    public const STATUS_UNAVAILABLE = 'tidak tersedia';
    public const STATUS_PREORDER = 'pre order';

    /**
     * Boot model events: auto-generate a unique slug from the name.
     */
    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug) && ! empty($product->name)) {
                $product->slug = static::uniqueSlug($product->name, $product->id);
            }
        });
    }

    /**
     * Generate a slug that is unique within the products table.
     */
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Use the slug for implicit route-model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Price formatted as Indonesian Rupiah, e.g. "Rp 15.000".
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Whether the product can currently be ordered.
     */
    public function isOrderable(): bool
    {
        return $this->stock_status !== self::STATUS_UNAVAILABLE;
    }

    /**
     * Whether the product is a pre-order item.
     */
    public function isPreOrder(): bool
    {
        return $this->stock_status === self::STATUS_PREORDER;
    }

    /**
     * Human-friendly order label used in the WhatsApp message.
     */
    public function orderLabel(): string
    {
        return $this->isPreOrder() ? 'PRE-ORDER' : 'REGULER';
    }

    /**
     * Gambar-gambar produk (urut: utama dulu, lalu sort_order).
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Daftar ukuran produk.
     */
    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * Gambar utama (primary), atau gambar pertama bila tak ada penanda primary.
     */
    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true) ?? $this->images->first();
    }

    /**
     * Apakah produk memiliki opsi ukuran.
     */
    public function hasSizes(): bool
    {
        return $this->sizes->isNotEmpty();
    }

    /**
     * Harga terendah (mempertimbangkan ukuran bila ada).
     */
    public function minPrice(): float
    {
        if ($this->hasSizes()) {
            return (float) $this->sizes->min('price');
        }

        return (float) $this->price;
    }

    /**
     * Harga tertinggi (mempertimbangkan ukuran bila ada).
     */
    public function maxPrice(): float
    {
        if ($this->hasSizes()) {
            return (float) $this->sizes->max('price');
        }

        return (float) $this->price;
    }

    /**
     * Label harga untuk katalog: satu harga atau rentang "Rp.. - Rp..".
     */
    public function priceRangeLabel(): string
    {
        $min = $this->minPrice();
        $max = $this->maxPrice();

        if ($min === $max) {
            return $this->rupiah($min);
        }

        return $this->rupiah($min) . ' - ' . $this->rupiah($max);
    }

    /**
     * Status efektif untuk badge katalog. Bila ada ukuran, produk dianggap
     * "tersedia" jika minimal satu ukuran bisa dipesan; "tidak tersedia" bila
     * semua ukuran habis; "pre order" bila tidak ada yang reguler tersedia
     * namun ada yang pre-order.
     */
    public function effectiveStatus(): string
    {
        if (! $this->hasSizes()) {
            return $this->stock_status;
        }

        $statuses = $this->sizes->pluck('stock_status');

        if ($statuses->contains(self::STATUS_AVAILABLE)) {
            return self::STATUS_AVAILABLE;
        }

        if ($statuses->contains(self::STATUS_PREORDER)) {
            return self::STATUS_PREORDER;
        }

        return self::STATUS_UNAVAILABLE;
    }

    /**
     * Apakah produk bisa dipesan (mempertimbangkan ukuran bila ada).
     */
    public function isOrderableNow(): bool
    {
        if ($this->hasSizes()) {
            return $this->sizes->contains(fn (ProductSize $s) => $s->isOrderable());
        }

        return $this->isOrderable();
    }

    /**
     * Helper format Rupiah.
     */
    private function rupiah(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
