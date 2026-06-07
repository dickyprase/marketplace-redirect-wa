<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_settings_page_requires_authentication(): void
    {
        $this->get('/admin/settings')->assertRedirect('/login');
    }

    public function test_admin_can_view_settings_page(): void
    {
        Setting::put(Setting::WHATSAPP_NUMBER, '6281111111111');
        Setting::put(Setting::CHECKOUT_TEMPLATE, Setting::DEFAULT_TEMPLATE);

        $response = $this->actingAs($this->admin())->get('/admin/settings');

        $response->assertOk();
        $response->assertSee('Pengaturan WhatsApp');
        $response->assertSee('6281111111111');
        $response->assertSee('{customer_name}');
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin())->put('/admin/settings', [
            'whatsapp_number' => '6289999999999',
            'checkout_template' => 'Halo {customer_name}, total {total}',
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $response->assertSessionHas('success');

        $this->assertSame('6289999999999', Setting::get(Setting::WHATSAPP_NUMBER));
        $this->assertSame('Halo {customer_name}, total {total}', Setting::get(Setting::CHECKOUT_TEMPLATE));
    }

    public function test_invalid_whatsapp_number_is_rejected(): void
    {
        $response = $this->actingAs($this->admin())->put('/admin/settings', [
            'whatsapp_number' => '+62 812-3456',
            'checkout_template' => 'Halo {customer_name}',
        ]);

        $response->assertSessionHasErrors('whatsapp_number');
    }

    public function test_preview_endpoint_renders_whatsapp_styling_to_html(): void
    {
        $response = $this->actingAs($this->admin())->postJson('/admin/settings/preview', [
            'checkout_template' => '*Halo* {customer_name} _selamat_',
        ]);

        $response->assertOk();
        $html = $response->json('html');

        $this->assertStringContainsString('<strong>Halo</strong>', $html);
        $this->assertStringContainsString('<em>selamat</em>', $html);
        // Placeholder terisi data contoh.
        $this->assertStringContainsString('Budi Santoso', $html);
    }
}
