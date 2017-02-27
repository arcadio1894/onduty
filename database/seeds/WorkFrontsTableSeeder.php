<?php

use App\Location;
use App\Plant;
use App\User;
use App\WorkFront;
use Illuminate\Database\Seeder;

class WorkFrontsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkFront::create([
            'name' => 'Estrella',
            'description' => 'Frente de trabajo de prueba 1',
            'location_id' => 1
        ]);
        WorkFront::create([
            'name' => 'Concreto',
            'description' => 'Frente de trabajo de prueba 2',
            'location_id' => 2
        ]);
    }
}
