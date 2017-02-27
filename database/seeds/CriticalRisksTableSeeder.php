<?php

use App\Area;
use App\CriticalRisk;
use App\Location;
use App\User;
use Illuminate\Database\Seeder;

class CriticalRisksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CriticalRisk::create([
            'name' => 'Riesgo crítico 1'
        ]);
        CriticalRisk::create([
            'name' => 'Riesgo crítico 2'
        ]);

    }
}
