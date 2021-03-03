<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\User::Create([
            'name' => 'helal',
            'password' => Hash::make('12345678'),
            'email' => 'helal6694@gmail.com'
        ]);

        $user = \App\User::Create([
            'name' => 'mohamed',
            'password' => Hash::make('12345678'),
            'email' => 'mohamed@gmail.com'
        ]);
    }
}
