<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admins_can_authenticate_using_the_login_screen(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasNoErrors();
    }

    public function test_admins_can_not_authenticate_with_invalid_password(): void
    {
        Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
        ]);

        $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
