<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener el rol de Administrador
        $adminRole = Role::where('name', 'Administrador')->first();

        // Crear el primer usuario y asignarle el rol de Administrador
        $user1 = User::create([
            'name' => 'Ronald Danilo Rodriguez Quintero',
            'document' => 1000455655,
            'phone' => 3214194839,
            'birthdate' => '2002-03-15',
            'email' => 'ronald@gmail.com',
            'password' => bcrypt('Andes2025*'),
        ]);
        $user1->assignRole($adminRole);

        // Crear el segundo usuario y asignarle el rol de Administrador
        $user2 = User::create([
            'name' => 'Antonio',
            'document' => 10475505,
            'phone' => 3223447891,
            'birthdate' => '1995-02-10',
            'email' => 'antonio.guevara@svgseguros.com',
            'password' => bcrypt('Estasseguro2024*'),
        ]);
        $user2->assignRole($adminRole);
    }
}
