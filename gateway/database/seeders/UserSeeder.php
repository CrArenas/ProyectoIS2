<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([        //10 REGISTROS DE GÉNERO
            [
                'role_id' => 1,
                'name' => 'Cristian',
                'email' => 'cristian@gmail.com',
                'password' => bcrypt('1243')
            ],
            [
                'role_id' => 2,
                'name' => 'Santiago',
                'email' => 'santiago@gmail.com',
                'password' => bcrypt('1234')
            ],
            [
                'role_id' => 2,
                'name' => 'Alex',
                'email' => 'alex@gmail.com',
                'password' => bcrypt('1234')
            ],
            [
                'role_id' => 2,
                'name' => 'Lina',
                'email' => 'lina@gmail.com',
                'password' => bcrypt('lina123')
            ]  
        ]);
    }
}