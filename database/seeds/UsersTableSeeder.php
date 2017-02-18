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
            'name' => 'Jorge Gonzales',
            'email' => 'joryes1894@gmail.com',
            'password' => bcrypt('123123'),
            'image'=> '0.png'
        ]);
    }
}
