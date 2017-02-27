<?php

use App\Location;
use App\User;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::create([
            'name' => 'CON 2113-2013',
            'description' => 'Locación de prueba 1'
        ]);
        Location::create([
            'name' => 'CON 2113-2014',
            'description' => 'Locación de prueba 2'
        ]);
        Location::create([
            'name' => 'CON 2113-2015',
            'description' => 'Locación de prueba 3'
        ]);
    }
}
