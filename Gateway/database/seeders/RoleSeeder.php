<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([        //10 REGISTROS DE GÉNERO
            [
                'label' => 'admin',
                'name' => 'Administrador'
            ],
            [
                'label' => 'user',
                'name' => 'Usuario'
            ]  
        ]);
    }
}
