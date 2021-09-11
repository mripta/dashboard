<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Data;
use App\Models\Team;
use App\Models\Param;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    /**
     * Create a new controller instance that requires authentication
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function table($teamid, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // if $request is POST
        if($request->method() == "POST")
        {
            $date = trim($request->datepicker);
            $date = explode(" - ", $date);

            // validate date values
            Validator::make($date, [
                0 => 'required|date_format:d/m/Y',
                1 => 'required|date_format:d/m/Y'
            ])->validate();

            $data = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->get();
        }
        else
        {
            // get all the data from the team
            $data = Data::where('teamid', $teamid)->get();
        }

        $team = Team::where('id', $teamid)->get();

        $params = [
            'title' => 'Dados Formatados',
            'data' => $data,
            'teamname' => $team[0]->name
        ];

        return view('dashboard.table', $params);
    }

    public function raw($teamid, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // if $request is POST
        if($request->method() == "POST")
        {
            $date = trim($request->datepicker);
            $date = explode(" - ", $date);

            // validate date values
            Validator::make($date, [
                0 => 'required|date_format:d/m/Y',
                1 => 'required|date_format:d/m/Y'
            ])->validate();

            $data = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->get();
        } 
        else 
        {
            // get all the data from the team
            $data = Data::where('teamid', $teamid)->get();
        }

        $team = Team::where('id', $teamid)->get();

        $params = [
            'title' => 'Dados RAW',
            'data' => $data,
            'teamname' => $team[0]->name
        ];

        return view('dashboard.raw', $params);
    }

    public function charts($teamid, $chart = null, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // if $request is POST
        if($request->method() == "POST")
        {
            $date = trim($request->datepicker);
            $date = explode(" - ", $date);

            // validate date values
            Validator::make($date, [
                0 => 'required|date_format:d/m/Y',
                1 => 'required|date_format:d/m/Y'
            ])->validate();

            $sensors = Data::where('teamid', $teamid)->whereDate('date', '<=', trim($date[1]))->whereDate('date', '>=', trim($date[0]))->get();
        } 
        else 
        {
            // get all the data from the team
            $sensors = Data::where('teamid', $teamid)->get();
        }

        // init arrays
        $data = array();
        $aux = array();
        $dataset = array();

        //array of allowed charts
        $charts = ['line', 'bar', 'radar' ];

        // check if provided chart is allowed
        if (!isset($chart) || !in_array($chart, $charts))
        {
            $chart = 'line';
        }

        // iterate per mongo message
        foreach ($sensors as $sensor)
        {
            //$sensor->ref -> ref

            // if array key does not exist, initialize it
            if (!isset($data[$sensor->ref]))
            {
                $data[$sensor->ref] = array();
            }

            // get the mongodb packet payload
            $linha = json_decode($sensor->payload);
            unset($linha->ref);
            // adds time element and cast the timestamp to date
            $linha->time = date('d/m/Y H:i:s', $sensor->timestamp);

            // cast payload to array of arrays
            $aux = array((array) $linha);

            // creates an array of arrays of the refs with the payload values 
            $data[$sensor->ref] = array_merge_recursive($data[$sensor->ref], $aux);
        }

        // iterate data refs
        foreach($data as $key => $refs)
        {
            // encodes the arrays to json 
            $data[$key] = json_encode(array($key => $refs));
        }

        // create dataset var -> array of refs and params
        // Get the refs of the team
        $refs = Ref::where('team_id', $teamid)->get(['id','ref']);

        // iterate all refs from the team
        foreach ($refs as $ref)
        {
            $dataset[$ref->ref] = array();
            // get the params of the ref
            $params = Param::where('ref_id', $ref->id)->get();

            // iterate all the params
            foreach ($params as $param)
            {
                array_push($dataset[$ref->ref], $param->param);
            }
        }

        $team = Team::where('id', $teamid)->get(['name']);

        $params = [
            'title' => 'Gráficos',
            'dataset' => $dataset,
            'chart' => $chart,
            'data' => $data,
            'teamname' => $team[0]->name,
            'j' => 0
        ];
        return view('dashboard.charts', $params);
    }

    public function live($teamid)
    {
        // force teamid to int
        $teamid = intval($teamid);
        $params = [
            'title' => "Charts - Live",
            'teamid' => $teamid
        ];
        return view('dashboard.live', $params);
    }
}