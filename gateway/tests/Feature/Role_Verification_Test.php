<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Role_Verification_Test extends TestCase
{

    public function test_printRole()
    {


        $user = User::where('email', 'cristian@gmail.com')->with('role')->first();
        dump('ROL (name):', $user->role?->name);
        dump('ROL (label):', $user->role?->label);

        if ($user->role_id === 1) {
            echo "\nRol asignado: Admin\n";
        } elseif ($user->role_id === 2) {
            echo "\nRol asignado: User\n";
        } else {
            echo "\nRol asignado desconocido (ID: {$user->role_id})\n";
        }

    }
}
