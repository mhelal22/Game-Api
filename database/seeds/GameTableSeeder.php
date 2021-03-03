<?php

use Illuminate\Database\Seeder;

class GameTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game = \App\Game::Create([
            'name' => 'FiFa 2021',
            'img' => "/img/fifa.png"
        ]);

        $game = \App\Game::Create([
            'name' => 'PES 2021',
            'img' => "/img/pes.png"
        ]);
    }
}
