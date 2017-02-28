<?php

use App\Area;
use App\Informe;
use App\Location;
use App\Plant;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InformesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carbon = new Carbon();

        Informe::create([
            'location_id' => 1,
            'user_id' => 4,
            'from_date' => $carbon->now(),
            'to_date' => $carbon->now()->addDays(5)
        ]);
        Informe::create([
            'location_id' => 2,
            'user_id' => 4,
            'from_date' => $carbon->now(),
            'to_date' => $carbon->now()->addDays(5)
        ]);
    }
}
