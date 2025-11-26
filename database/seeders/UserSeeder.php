<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('Aa123456'),
            'role' => 'admin',
        ]);*/

        User::factory()->create([
            'name' => 'Operador Padrão',
            'email' => 'operador@neovita.com',
            'password' => bcrypt('senha123'),
            'role' => 'operator',
        ]);
    }
}