<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'price',
        'stock_status',
        'image_path',
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
}
