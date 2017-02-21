<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'role_id' => 1,
            'password' => bcrypt('123123'),
            'confirmed' => true,
            'confirmation_code' => null,
            'enable' => 1,
            'image'=> 'jpg'
        ]);

        User::create([
            'name' => 'Juan Ramos',
            'email' => 'juancagb.17@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('123123'),
            'confirmation_code' => null,
            'confirmed' => true,
            'enable' => 1,
            'image'=> 'png'
        ]);

        User::create([
            'name' => 'Jose Perez',
            'email' => 'jose_perez.17@gmail.com',
            'role_id' => 3,
            'password' => bcrypt('123123'),
            'confirmed' => true,
            'confirmation_code' => null,
            'enable' => 1,
            'image'=> 'jpg'
        ]);

        User::create([
            'name' => 'Jorge Gonzales',
            'email' => 'joryes1894@gmail.com',
            'role_id' => 2,
            'password' => bcrypt('123123'),
            'confirmed' => true,
            'confirmation_code' => null,
            'enable' => 1,
            'image'=> 'png'
        ]);
    }
}
