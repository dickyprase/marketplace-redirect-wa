<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\WhatsappMessageBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private WhatsappMessageBuilder $builder)
    {
    }

    /**
     * Tampilkan form pengaturan site umum.
     */
    public function siteEdit(): View
    {
        $settings = [
            'site_name'          => Setting::get(Setting::SITE_NAME, config('app.name')),
            'contact_address'    => Setting::get(Setting::CONTACT_ADDRESS, ''),
            'contact_email'      => Setting::get(Setting::CONTACT_EMAIL, ''),
            'contact_phone'      => Setting::get(Setting::CONTACT_PHONE, ''),
            'contact_hours'      => Setting::get(Setting::CONTACT_HOURS, ''),
            'contact_maps_embed' => Setting::get(Setting::CONTACT_MAPS_EMBED, ''),
            'social_facebook'    => Setting::get(Setting::SOCIAL_FACEBOOK, ''),
            'social_instagram'   => Setting::get(Setting::SOCIAL_INSTAGRAM, ''),
            'social_threads'     => Setting::get(Setting::SOCIAL_THREADS, ''),
        ];

        return view('admin.settings.site', compact('settings'));
    }

    /**
     * Simpan pengaturan site umum.
     */
    public function siteUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_name'          => ['required', 'string', 'max:100'],
            'contact_address'    => ['nullable', 'string', 'max:500'],
            'contact_email'      => ['nullable', 'string', 'email', 'max:150'],
            'contact_phone'      => ['nullable', 'string', 'max:50'],
            'contact_hours'      => ['nullable', 'string', 'max:200'],
            'contact_maps_embed' => ['nullable', 'string', 'max:2000'],
            'social_facebook'    => ['nullable', 'max:255'],
            'social_instagram'   => ['nullable', 'max:255'],
            'social_threads'     => ['nullable', 'max:255'],
        ]);

        Setting::put(Setting::SITE_NAME, $validated['site_name']);
        Setting::put(Setting::CONTACT_ADDRESS, $validated['contact_address'] ?? null);
        Setting::put(Setting::CONTACT_EMAIL, $validated['contact_email'] ?? null);
        Setting::put(Setting::CONTACT_PHONE, $validated['contact_phone'] ?? null);
        Setting::put(Setting::CONTACT_HOURS, $validated['contact_hours'] ?? null);
        Setting::put(Setting::CONTACT_MAPS_EMBED, $validated['contact_maps_embed'] ?? null);
        Setting::put(Setting::SOCIAL_FACEBOOK, $validated['social_facebook'] ?? null);
        Setting::put(Setting::SOCIAL_INSTAGRAM, $validated['social_instagram'] ?? null);
        Setting::put(Setting::SOCIAL_THREADS, $validated['social_threads'] ?? null);

        return redirect()
            ->route('admin.site-settings.edit')
            ->with('success', 'Site setting berhasil disimpan.');
    }

    /**
     * Tampilkan form pengaturan WhatsApp.
     */
    public function edit(): View
    {
        $settings = [
            'whatsapp_number'    => Setting::get(Setting::WHATSAPP_NUMBER, ''),
            'checkout_template'  => Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE),
            'cart_template'      => Setting::get(Setting::CART_TEMPLATE, Setting::DEFAULT_CART_TEMPLATE),
        ];

        $placeholders = WhatsappMessageBuilder::placeholders();
        $previewHtml = $this->builder->toHtmlPreview($this->samplePreview());

        return view('admin.settings.edit', compact('settings', 'placeholders', 'previewHtml'));
    }

    /**
     * Simpan pengaturan WhatsApp.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number'    => ['required', 'string', 'regex:/^[0-9]{8,20}$/'],
            'checkout_template'  => ['required', 'string', 'max:5000'],
            'cart_template'      => ['required', 'string', 'max:5000'],
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus berupa angka (format internasional tanpa +), contoh: 6281234567890.',
        ]);

        Setting::put(Setting::WHATSAPP_NUMBER, $validated['whatsapp_number']);
        Setting::put(Setting::CHECKOUT_TEMPLATE, $validated['checkout_template']);
        Setting::put(Setting::CART_TEMPLATE, $validated['cart_template']);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
    }

    /**
     * Preview template (AJAX) — render styling WA ke HTML memakai data contoh.
     */
    public function preview(Request $request): JsonResponse
    {
        $request->validate([
            'checkout_template' => ['required', 'string', 'max:5000'],
        ]);

        $rendered = $this->renderSample($request->input('checkout_template'));

        return response()->json([
            'html' => $this->builder->toHtmlPreview($rendered),
        ]);
    }

    private function renderSample(string $template): string
    {
        $template = str_replace(["\r\n", "\r"], "\n", $template);

        $notes = 'Tolong dikirim cepat';
        $itemsBlock = "2x Kopi Arabika Gayo 250g - Rp 170.000\n1x Hoodie Limited Edition (L) - Rp 265.000";

        $replacements = [
            '{customer_name}' => 'Budi Santoso',
            '{address}'       => 'Jl. Contoh No. 123, Sidoarjo',
            '{address_line}'  => 'Alamat: Jl. Contoh No. 123, Sidoarjo' . "\n",
            '{notes}'         => $notes,
            '{notes_line}'    => 'Catatan: ' . $notes . "\n",
            '{order_status}'  => 'REGULER',
            '{product_name}'  => 'Kopi Arabika Gayo 250g',
            '{size}'          => 'L',
            '{size_line}'     => 'Ukuran: L' . "\n",
            '{price}'         => 'Rp 85.000',
            '{quantity}'      => '3',
            '{subtotal}'      => 'Rp 255.000',
            '{total}'         => 'Rp 255.000',
            '{items}'         => $itemsBlock,
            '{grand_total}'   => 'Rp 435.000',
        ];

        return strtr($template, $replacements);
    }

    private function samplePreview(): string
    {
        return $this->renderSample(
            Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE)
        );
    }
}
