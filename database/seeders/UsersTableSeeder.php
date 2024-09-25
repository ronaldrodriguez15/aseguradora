<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ronald Danilo Rodriguez Quintero',
            'document' => 1000455655,
            'phone' => 3214194839,
            'birthdate' => '2002-03-15',
            'email' => 'ronald@gmail.com',
            'password' => bcrypt('Andes2025*'),
        ], [
            'name' => 'Antonio',
            'document' => 10475505,
            'phone' => 3223447891,
            'birthdate' => '1995-02-10',
            'email' => 'antonio.guevara@svgseguros.com',
            'password' => bcrypt('Estasseguro2024*'),
        ]);
    }
}
