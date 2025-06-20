<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsultaTransactionUserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_transactions_query_user(): void
    {
        $transactionData = [
            'amount' => 149.62,
            'V1' => -1.3598,
            'V2' => -0.07278,
            'V3' => 2.5363,
            'V4' => 1.3781,
            'V5' => -0.3383,
            'V6' => 0.4624,
            'V7' => 0.2396,
            'V8' => 0.0987,
            'V9' => 0.3638,
            'V10' => 0.0908,
            'V11' => -0.5516,
            'V12' => -0.6178,
            'V13' => -0.9914,
            'V14' => -0.3112,
            'V15' => 1.4682,
            'V16' => -0.4704,
            'V17' => 0.2080,
            'V18' => 0.0258,
            'V19' => 0.4040,
            'V20' => 0.2514,
            'V21' => -0.0183,
            'V22' => 0.2778,
            'V23' => -0.1105,
            'V24' => 0.0669,
            'V25' => 0.1285,
            'V26' => -0.1891,
            'V27' => 0.1336,
            'V28' => -0.0211
        ];

        //Inicio de sesión
        $response = $this->post('api/login', [
            'email' => 'lina@gmail.com','password' => "lina123"
        ]);

        $response->assertStatus(200);
        $token = $response->json('token');

        $response2 = $this->withHeaders([
            'Authorization' => "Bearer $token",
            ])->post('api/predict',$transactionData);
        #dump($response2->json());
        $response2->assertStatus(200);
        $response2->assertJson(['message' => 'Se guardo']);

        // Consulta de transacciones del usuario
        $consulta = $this->withHeaders([
            'Authorization' => "Bearer $token",
            ])->get('api/userstransactions');
        dump($consulta->json());
        $consulta->assertStatus(200);
        $consulta->assertJsonFragment(['amount' => 149.62]);
    }
}
