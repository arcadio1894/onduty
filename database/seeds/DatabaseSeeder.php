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
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LocationsTableSeeder::class);
        $this->call(PlantsTableSeeder::class);
        $this->call(WorkFrontsTableSeeder::class);
        $this->call(AreasTableSeeder::class);
    }
}