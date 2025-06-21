<?php

namespace Tests\Feature;

use Tests\TestCase;

class Login_Test extends TestCase
{
    public function test_Login()
    {
        $loginResponse = $this->post('/api/login', [
            'email' => 'laura@gmail.com',
            'password' => '1234567'
        ]);

        $loginResponse->assertStatus(200);
        $this->assertArrayHasKey('token', $loginResponse->json());

        $token = $loginResponse['token'];

        $logoutResponse = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->post('/api/logout');

        $logoutResponse->assertStatus(200);
    }
}
