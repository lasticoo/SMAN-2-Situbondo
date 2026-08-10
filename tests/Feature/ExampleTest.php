<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test health check route.
     */
    public function test_health_check_returns_successful_response(): void
    {
        $response = $this->get('/up');

        $response->assertStatus(200);
    }

    /**
     * Test login page accessibility.
     */
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
