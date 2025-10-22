<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_application_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_auth_routes_exist(): void
    {
        $response = $this->post('/api/auth/register');
        $this->assertNotEquals(404, $response->getStatusCode());
        
        $response = $this->post('/api/auth/login');
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_health_check_works(): void
    {
        $response = $this->get('/up');
        $response->assertStatus(200);
    }
}