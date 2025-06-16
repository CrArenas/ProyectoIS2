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

        // Simula los datos de registro
        $response = $this->post('api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
        ]);

        // Asegúrate de que el usuario fue creado en la base de datos
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
 
        $userId = $response->json('user.id');
        // Obtén el usuario de la base de datos
        $user = User::find($userId);

        // Verifica que la contraseña esté encriptada (NO en texto plano)
        $this->assertNotEquals('secret123', $user->password);

        // Verifica que la contraseña encriptada sea correcta
        $this->assertTrue(Hash::check('secret123', $user->password));
        
        $user->delete();
    }
}
