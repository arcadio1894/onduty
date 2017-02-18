<?php

use App\Location;
use App\Plant;
use App\User;
use Illuminate\Database\Seeder;

class PlantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plant::create([
            'name' => 'Estrella',
            'description' => 'Planta de prueba 1',
            'location_id' => 1,
            'enable' => 1
        ]);
        Plant::create([
            'name' => 'Concreto',
            'description' => 'Planta de prueba 2',
            'location_id' => 2,
            'enable' => 1
        ]);
    }
}
