<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSize extends Model
{
    protected $fillable = [
        'product_id',
        'label',
        'price',
        'stock_status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Harga ukuran dalam format Rupiah.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
    }

    /**
     * Apakah ukuran ini bisa dipesan.
     */
    public function isOrderable(): bool
    {
        return $this->stock_status !== Product::STATUS_UNAVAILABLE;
    }

    /**
     * Apakah ukuran ini pre-order.
     */
    public function isPreOrder(): bool
    {
        return $this->stock_status === Product::STATUS_PREORDER;
    }
}
