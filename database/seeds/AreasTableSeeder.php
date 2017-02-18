<?php

use App\Area;
use App\Location;
use App\Plant;
use App\User;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create([
            'name' => 'Administración',
            'description' => 'Área de administración',
            'enable' => 1
        ]);
        Area::create([
            'name' => 'Mantenimiento',
            'description' => 'Área de mantenimiento',
            'enable' => 1
        ]);
        Area::create([
            'name' => 'Producción',
            'description' => 'Área de producción',
            'enable' => 1
        ]);
        Area::create([
            'name' => 'HSE',
            'description' => 'Área de HSE',
            'enable' => 1
        ]);
        Area::create([
            'name' => 'MQ',
            'description' => 'Área de MQ',
            'enable' => 1
        ]);
    }
}
