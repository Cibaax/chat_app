<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChatTest extends TestCase
{
    public function test_chat_routes_exist(): void
    {
        $response = $this->get('/api/chats');
        $this->assertNotEquals(404, $response->getStatusCode());
        
        $response = $this->post('/api/chats');
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_message_routes_exist(): void
    {
        $response = $this->get('/api/chats/1/messages');
        $this->assertNotEquals(404, $response->getStatusCode());
        
        $response = $this->post('/api/chats/1/messages');
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_user_routes_exist(): void
    {
        $response = $this->get('/api/users');
        $this->assertNotEquals(404, $response->getStatusCode());
    }
}