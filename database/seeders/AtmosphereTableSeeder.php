<?php

namespace Database\Seeders;

use App\Models\Atmosphere;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AtmosphereTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Atmosphere::insert([
            [
                'name' => 'Development',
                'key' => 'sandbox',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Production',
                'key' => 'documents',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}
