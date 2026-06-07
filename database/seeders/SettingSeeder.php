<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed default application settings.
     */
    public function run(): void
    {
        $defaults = [
            Setting::WHATSAPP_NUMBER => env('WHATSAPP_ADMIN_NUMBER', '6281234567890'),
            Setting::CHECKOUT_TEMPLATE => Setting::DEFAULT_TEMPLATE,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
