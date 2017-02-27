<?php

use App\Area;
use App\Location;
use App\Plant;
use App\Position;
use App\User;
use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'name' => 'Sin cargo',
            'description' => 'Sin cargo'
        ]);
        Position::create([
            'name' => 'Cargo 1',
            'description' => 'Cargo 1'
        ]);
        Position::create([
            'name' => 'Cargo 2',
            'description' => 'Cargo 2'
        ]);
        Position::create([
            'name' => 'Cargo 3',
            'description' => 'Cargo 3'
        ]);

    }
}
