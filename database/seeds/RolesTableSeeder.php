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
        Role::create([ // 1
            'name' => 'Súper administrador',
            'description' => 'Rol súper administrador'
        ]);
        Role::create([ // 2
            'name' => 'Administrador',
            'description' => 'Rol administrador'
        ]);
        Role::create([ // 3
            'name' => 'Responsable',
            'description' => 'Rol responsable'
        ]);
        Role::create([ // 4
            'name' => 'Visitante',
            'description' => 'Rol visitante'
        ]);
    }
}
