<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EncryptationTest extends TestCase
{   
      /**
     * A basic feature test example.
     */
    public function test_Encryptation_Password(): void
    {
        
    /** @test */

        $response = $this->post('api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
 
        $userId = $response->json('user.id');
    
        $user = User::find($userId);

        $this->assertNotEquals('secret123', $user->password);

        $this->assertTrue(Hash::check('secret123', $user->password));
        
        $user->delete();
    }
}
