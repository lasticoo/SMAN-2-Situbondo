<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ColorSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminColorSettingTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@smada.sch.id',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function guest_cannot_access_color_settings_page(): void
    {
        $response = $this->get(route('admin.color_settings.edit'));

        $response->assertRedirect(route('admin.login'));
    }

    /** @test */
    public function authenticated_admin_can_access_color_settings_page(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.color_settings.edit'));

        $response->assertOk();
        $response->assertViewIs('admin.color_setting.edit');
        $response->assertViewHas('colorSetting');
    }

    /** @test */
    public function admin_can_update_color_settings_with_valid_hex_codes(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.color_settings.update'), [
                'primary_color' => '#1A2B3C',
                'secondary_color' => '#4D5E6F',
            ]);

        $response->assertRedirect(route('admin.color_settings.edit'));
        $response->assertSessionHas('success', 'Konfigurasi warna berhasil disimpan.');

        $this->assertDatabaseHas('color', [
            'primary_color' => '#1A2B3C',
            'secondary_color' => '#4D5E6F',
            'updated_by' => $this->admin->id,
        ]);

        $current = ColorSetting::current();
        $this->assertEquals('#1A2B3C', $current->primary_color);
        $this->assertEquals('#4D5E6F', $current->secondary_color);
    }

    /** @test */
    public function color_setting_uses_default_config_fallback_when_database_is_empty(): void
    {
        $this->assertDatabaseCount('color', 0);

        $current = ColorSetting::current();

        $this->assertEquals(config('theme.primary', '#001C4D'), $current->primary_color);
        $this->assertEquals(config('theme.secondary', '#5C5F60'), $current->secondary_color);
    }

    /** @test */
    public function color_setting_updates_existing_singleton_row_without_creating_new_rows(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.color_settings.update'), [
                'primary_color' => '#111111',
                'secondary_color' => '#222222',
            ]);

        $this->assertDatabaseCount('color', 1);

        $this->actingAs($this->admin, 'admin')
            ->put(route('admin.color_settings.update'), [
                'primary_color' => '#333333',
                'secondary_color' => '#444444',
            ]);

        $this->assertDatabaseCount('color', 1);
        $this->assertDatabaseHas('color', [
            'primary_color' => '#333333',
            'secondary_color' => '#444444',
        ]);
    }

    /** @test */
    public function validation_fails_for_invalid_hex_color_format(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.color_settings.update'), [
                'primary_color' => 'invalid-hex',
                'secondary_color' => '#1234567899',
            ]);

        $response->assertSessionHasErrors(['primary_color', 'secondary_color']);
    }
}
