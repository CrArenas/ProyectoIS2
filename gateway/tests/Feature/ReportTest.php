<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_report_user(): void
    {
        $response = $this->post('api/login', [
            'email' => 'santi@gmail.com',
            'password' => 123456
        ]);
        $response->assertStatus(200);
        $token = $response->json('token');

        $response2 = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('api/userReport');
        $response->assertStatus(200);
    }

    public function test_all_report_admin(): void
    {
        $response = $this->post('api/login', [
            'email' => 'cristian@gmail.com',
            'password' => 1243
        ]);
        $response->assertStatus(200);
        $token = $response->json('token');

        $response2 = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('api/adminReport');
        $response->assertStatus(200);
    }

    public function test_user_report_admin(): void
    {
        $response = $this->post('api/login', [
            'email' => 'cristian@gmail.com',
            'password' => 1243
        ]);
        $response->assertStatus(200);
        $token = $response->json('token');

        $response2 = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->get('api/adminReport/2');
        $response->assertStatus(200);
    }
}
