<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class Admin_Transactions_Test extends TestCase
{

    public function test_AdminTransactions()
    {

        $loginResponse = $this->post('/api/login', [
            'email' => 'cristian@gmail.com',
            'password' => '1243'
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse['token'];

        $user = User::where('email', 'cristian@gmail.com')->with('role')->first();
        dump('ROL (name):', $user->role?->name);
        dump('ROL (label):', $user->role?->label);


        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->get('/api/transactions');

        $response->dump();
        $response->assertStatus(200); 
    }
}
