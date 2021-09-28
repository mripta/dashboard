<?php

namespace Database\Seeders;

use App\Models\Param;
use Illuminate\Database\Seeder;

class ParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // optod
        $temp = new Param();
        $temp->param = 'temp';
        $temp->name = 'Temperatura';
        $temp->ref_id = 1;
        $temp->save();

        $oxSat = new Param();
        $oxSat->param = 'oxSat';
        $oxSat->name = 'Saturação Oxigénio';
        $oxSat->ref_id = 1;
        $oxSat->save();

        $oxMg = new Param();
        $oxMg->param = 'oxMg';
        $oxMg->name = 'Sensor oxMg';
        $oxMg->ref_id = 1;
        $oxMg->save();

        $oxPpm = new Param();
        $oxPpm->param = 'oxPpm';
        $oxPpm->name = 'Sensor oxPpm';
        $oxPpm->ref_id = 1;
        $oxPpm->save();

        // ph
        $temp = new Param();
        $temp->param = 'temp';
        $temp->name = 'Sensor Temperatura';
        $temp->ref_id = 2;
        $temp->save();

        $pH = new Param();
        $pH->param = 'pH';
        $pH->name = 'Sensor pH';
        $pH->ref_id = 2;
        $pH->save();

        $redox = new Param();
        $redox->param = 'redox';
        $redox->name = 'Sensor Redox';
        $redox->ref_id = 2;
        $redox->save();

        //c4e
        $temp = new Param();
        $temp->param = 'temp';
        $temp->name = 'Sensor Temperatura';
        $temp->ref_id = 3;
        $temp->save();

        $condutivity = new Param();
        $condutivity->param = 'condutivity';
        $condutivity->name = 'Sensor Condutividade';
        $condutivity->ref_id = 3;
        $condutivity->save();

        $salinity = new Param();
        $salinity->param = 'salinity';
        $salinity->name = 'Sensor Salinidade';
        $salinity->ref_id = 3;
        $salinity->save();

        $tds = new Param();
        $tds->param = 'tds';
        $tds->name = 'Sensor tds';
        $tds->ref_id = 3;
        $tds->save();
    }
}
