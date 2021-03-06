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
            'description' => 'Área de administración'
        ]);
        Area::create([
            'name' => 'Mantenimiento',
            'description' => 'Área de mantenimiento'
        ]);
        Area::create([
            'name' => 'Producción',
            'description' => 'Área de producción'
        ]);
        Area::create([
            'name' => 'HSE',
            'description' => 'Área de HSE'
        ]);
        Area::create([
            'name' => 'MQ',
            'description' => 'Área de MQ'
        ]);
    }
}
