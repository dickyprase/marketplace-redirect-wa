<?php

namespace App\Support;

use Closure;
use Illuminate\Http\UploadedFile;

class SecureImageRules
{
    /**
     * @return array<int, mixed>
     */
    public static function rules(bool $required = false): array
    {
        return [
            $required ? 'required' : 'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'extensions:jpg,jpeg,png,webp',
            'max:20480',
            'dimensions:max_width=5000,max_height=5000',
            function (string $attribute, mixed $value, Closure $fail): void {
                if (! $value instanceof UploadedFile) {
                    return;
                }

                $filename = strtolower($value->getClientOriginalName());

                if (preg_match('/\.(php|phtml|phar|cgi|pl|asp|aspx|jsp)(\.|$)/i', $filename)) {
                    $fail('Nama file gambar tidak boleh mengandung ekstensi script.');
                }
            },
        ];
    }
}
