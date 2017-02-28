<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PositionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(WorkFrontsTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(CriticalRisksTableSeeder::class);
        $this->call(InformesTableSeeder::class);
    }
}
