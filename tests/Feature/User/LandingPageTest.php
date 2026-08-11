<?php

namespace Tests\Feature\User;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_landing_page_without_login(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SMA NEGERI 2');
    }

    public function test_landing_page_displays_active_banners_and_news(): void
    {
        $admin = Admin::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        Banner::create([
            'title' => 'Banner Utama Test',
            'description' => 'Deskripsi Banner Test',
            'image_url' => '/build/assets/banner smada.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        News::create([
            'title' => 'Berita Utama Test',
            'summary' => 'Ringkasan berita test',
            'content' => 'Konten berita test',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $admin->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Banner Utama Test');
        $response->assertSee('Berita Utama Test');
    }
}
