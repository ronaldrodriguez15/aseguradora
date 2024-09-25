<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CitiesTableSeeder;
use Database\Seeders\BanksTableSeeder;
use Database\Seeders\EntitiesTableSeeder;
use Database\Seeders\EpsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(EntitiesTableSeeder::class);
        $this->call(EpsTableSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
