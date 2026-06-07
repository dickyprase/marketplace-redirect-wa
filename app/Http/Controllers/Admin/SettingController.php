<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
     * Tampilkan form pengaturan WhatsApp.
     */
    public function edit(): View
    {
        $settings = [
            'whatsapp_number' => Setting::get(Setting::WHATSAPP_NUMBER, ''),
            'checkout_template' => Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE),
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
            'whatsapp_number'   => ['required', 'string', 'regex:/^[0-9]{8,20}$/'],
            'checkout_template' => ['required', 'string', 'max:5000'],
        ], [
            'whatsapp_number.regex' => 'Nomor WhatsApp harus berupa angka (format internasional tanpa +), contoh: 6281234567890.',
        ]);

        Setting::put(Setting::WHATSAPP_NUMBER, $validated['whatsapp_number']);
        Setting::put(Setting::CHECKOUT_TEMPLATE, $validated['checkout_template']);

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

    /**
     * Render template dengan data contoh tanpa menyentuh setting tersimpan.
     */
    private function renderSample(string $template): string
    {
        $template = str_replace(["\r\n", "\r"], "\n", $template);

        $notes = 'Tolong dikirim cepat';
        $replacements = [
            '{customer_name}' => 'Budi Santoso',
            '{notes}'         => $notes,
            '{notes_line}'    => 'Catatan: ' . $notes . "\n",
            '{order_status}'  => 'REGULER',
            '{product_name}'  => 'Kopi Arabika Gayo 250g',
            '{price}'         => 'Rp 85.000',
            '{quantity}'      => '3',
            '{subtotal}'      => 'Rp 255.000',
            '{total}'         => 'Rp 255.000',
        ];

        return strtr($template, $replacements);
    }

    /**
     * Teks contoh dari template tersimpan untuk preview awal.
     */
    private function samplePreview(): string
    {
        return $this->renderSample(
            Setting::get(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE)
        );
    }
}
