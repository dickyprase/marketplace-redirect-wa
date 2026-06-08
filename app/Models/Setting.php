<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['key', 'value'];

    /**
     * Setting key constants.
     */
    public const WHATSAPP_NUMBER = 'whatsapp_number';
    public const CHECKOUT_TEMPLATE = 'checkout_template';

    /**
     * Default checkout message template. Supports placeholders and
     * WhatsApp-style formatting (*bold*, _italic_, ~strike~, ```mono```).
     */
    public const DEFAULT_TEMPLATE = <<<'TPL'
*Pesanan Baru*

Nama Pembeli: {customer_name}
{notes_line}Status Order: {order_status}

------------------------------
Produk   : {product_name}
{size_line}Harga    : {price}
QTY      : {quantity}
Subtotal : {subtotal}
------------------------------
*Total    : {total}*
TPL;

    /**
     * Cache key used to memoize all settings.
     */
    private const CACHE_KEY = 'app_settings';

    /**
     * Retrieve a setting value by key, falling back to a default.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $all = Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });

        return $all[$key] ?? $default;
    }

    /**
     * Persist a setting value by key and flush the cache.
     */
    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Flush the cached settings (called on model writes).
     */
    protected static function booted(): void
    {
        $flush = fn () => Cache::forget(self::CACHE_KEY);

        static::saved($flush);
        static::deleted($flush);
    }
}
