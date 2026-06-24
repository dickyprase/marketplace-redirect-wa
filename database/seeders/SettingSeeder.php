<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            Setting::SITE_NAME          => env('APP_NAME', 'Penjualan WA'),
            Setting::WHATSAPP_NUMBER    => env('WHATSAPP_ADMIN_NUMBER', '6281234567890'),
            Setting::CHECKOUT_TEMPLATE  => Setting::DEFAULT_TEMPLATE,
            Setting::CART_TEMPLATE      => Setting::DEFAULT_CART_TEMPLATE,
            Setting::CONTACT_ADDRESS    => 'Candi - Sidoarjo',
            Setting::CONTACT_EMAIL      => 'marketplace@example.com',
            Setting::CONTACT_PHONE      => '+62 812-3456-7890',
            Setting::CONTACT_HOURS      => 'Senin-Minggu : 09.00 WIB - 17.00 WIB',
            Setting::CONTACT_MAPS_EMBED => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d588.0202614151998!2d112.72751021291934!3d-7.496900335329135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e7bb95208735%3A0xeff3e9a852927a3c!2sRCS%20Komputer%20Sidoarjo!5e0!3m2!1sen!2sid!4v1781486852982!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            Setting::SOCIAL_FACEBOOK    => '#',
            Setting::SOCIAL_INSTAGRAM   => '#',
            Setting::SOCIAL_THREADS     => '#',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
