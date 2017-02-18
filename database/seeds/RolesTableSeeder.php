<?php

use App\Area;
use App\Location;
use App\Plant;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Master',
            'description' => 'Rol Súper administrador',
            'enable' => 1
        ]);
        Role::create([
            'name' => 'Administrador',
            'description' => 'Rol Administrador',
            'enable' => 1
        ]);
        Role::create([
            'name' => 'Responsable',
            'description' => 'Rol Responsable',
            'enable' => 1
        ]);
        Role::create([
            'name' => 'Supervisor',
            'description' => 'Rol Supervisor',
            'enable' => 1
        ]);
        Role::create([
            'name' => 'HSE',
            'description' => 'Rol HSE',
            'enable' => 1
        ]);
    }
}
