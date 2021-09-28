<?php

namespace Database\Seeders;

use App\Models\Ref;
use Illuminate\Database\Seeder;

class RefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $optod = new Ref();
        $optod->ref = 'optod';
        $optod->name = 'Sensor Óptico';
        $optod->team_id = 1;
        $optod->save();

        $ph = new Ref();
        $ph->ref = 'ph';
        $ph->name = 'Sensor Ph';
        $ph->team_id = 1;
        $ph->save();

        $c4e = new Ref();
        $c4e->ref = 'c4e';
        $c4e->name = 'Sensor Condutividade';
        $c4e->team_id = 1;
        $c4e->save();
    }
}
