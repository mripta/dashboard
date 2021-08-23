<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $team = new Team();
        $team->name = 'Equipa 1';
        $team->description = 'Ponto de recolha de Tomar';
        $team->username = 'equipa1';
        $team->password = bcrypt('password');
        $team->save();

        // add user id 1 to the team with ownership 
        $team->users()->attach(1, ['owner' => 1]);
        // add user id 2 to the team
        $team->users()->attach(2);
    }
}
