<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create(array(
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role_id'  => 1,
            'type'=>''
        ));
    }
}
