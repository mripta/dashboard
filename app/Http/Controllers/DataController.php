<?php

namespace App\Http\Controllers;

use Auth;
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

    /**
     * Display the formated table data page.
     * /table/{teamid}
     *
     * @param  int  $teamid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function table($teamid, Request $request)
    {
        $hard_limit = 2000;
        // force teamid to int
        $teamid = intval($teamid);

        // get the team
        $team = Team::findOrFail($teamid);

        // check if the user belongs to the team
        if (!Auth::user()->isMember($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para visualizar este recurso");
        }

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

            $total = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->count();
            $data = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->skip($total-$hard_limit)->get();
        }
        else
        {
            // get all the data from the team
            $total = Data::where('teamid', $teamid)->count();
            $data = Data::where('teamid', $teamid)->skip($total-$hard_limit)->get();
        }

        $params = [
            'title' => 'Tabela Formatada - '.$team->name,
            'data' => $data,
            'j' => 1,
            'hard_limit' => $hard_limit
        ];

        return view('dashboard.table', $params);
    }

    /**
     * Display the payload table data page.
     * /raw/{teamid}
     *
     * @param  int  $teamid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function raw($teamid, Request $request)
    {
        $hard_limit = 20000;
        // force teamid to int
        $teamid = intval($teamid);

        // get the team
        $team = Team::findOrFail($teamid);

        // check if the user belongs to the team
        if (!Auth::user()->isMember($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para visualizar este recurso");
        }

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

            $total = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->count();
            $data = Data::where('teamid', $teamid)->whereDate('date', '<=', $date[1])->whereDate('date', '>=', $date[0])->skip($total-$hard_limit)->get();
        } 
        else 
        {
            // get all the data from the team
            $total = Data::where('teamid', $teamid)->count();
            $data = Data::where('teamid', $teamid)->skip($total-$hard_limit)->get();
        }

        $params = [
            'title' => 'Tabela RAW - '.$team->name,
            'data' => $data,
            'hard_limit' => $hard_limit
        ];

        return view('dashboard.raw', $params);
    }

    /**
     * Display the charts data page.
     * /charts/{chart}/{teamid}
     *
     * @param  string   $chart
     * @param  int      $teamid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function charts($chart, $teamid, $refid = null, $paramid = null, Request $request)
    {
        $hard_limit = 1000;
        // force teamid to int
        $teamid = intval($teamid);

        // get the team
        $team = Team::with('refs')->findOrFail($teamid);

        // check if the user belongs to the team
        if (!Auth::user()->isMember($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para visualizar este recurso");
        }

        // if $request is POST
        if($request->method() == "POST")
        {
            $sensors = $this->handleChartPostRequests($hard_limit, $request, $refid, $teamid);
        } 
        else // it's a GET
        {
            // get all the data from the team
            $total = Data::where('teamid', $teamid)->count();
            $sensors = Data::where('teamid', $teamid)->skip($total-$hard_limit)->get();
        }

        // init arrays
        $data = array();
        $aux = array();
        $dataset = array();

        //array of allowed charts
        $charts = ['line', 'bar', 'radar'];

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
        if (is_null ($refid)){  // not filtred through refs 
            $refs = Ref::where('team_id', $teamid)->get(['id','ref']);
        }
        else {  // filtred through refs
            $refs = Ref::where('team_id', $teamid)->where('id', $refid)->get(['id','ref']);
        }
        
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

        $params = [
            'title' => 'Gráficos - '.$team->name,
            'dataset' => $dataset,
            'refid' => $refid,
            'paramid' => $paramid,
            'teamid' => $teamid,
            'refs' => $team->refs,
            'chart' => $chart,
            'data' => $data,
            'j' => 0,
            'hard_limit' => $hard_limit
        ];
        return view('dashboard.charts', $params);
    }

    public function handleChartPostRequests($hard_limit, $request, $refid, $teamid)
    {
            $date = trim($request->datepicker);
            $date = explode(" - ", $date);

            // validate date values
            Validator::make($date, [
                0 => 'required|date_format:d/m/Y',
                1 => 'required|date_format:d/m/Y'
            ])->validate();

            if (!is_null($refid)) 
            {
                $refid = intval($refid);

                // get the ref
                $ref = Ref::findOrFail($refid);

                $total = Data::where('teamid', $teamid)->where('ref', $ref->ref)->whereDate('date', '<=', trim($date[1]))->whereDate('date', '>=', trim($date[0]))->count();

                $sensors = Data::where('teamid', $teamid)->where('ref', $ref->ref)->whereDate('date', '<=', trim($date[1]))->whereDate('date', '>=', trim($date[0]))->skip($total-$hard_limit)->get();
            
            } 
            else 
            {
                $total = Data::where('teamid', $teamid)->whereDate('date', '<=', trim($date[1]))->whereDate('date', '>=', trim($date[0]))->count();

                $sensors = Data::where('teamid', $teamid)->whereDate('date', '<=', trim($date[1]))->whereDate('date', '>=', trim($date[0]))->skip($total-$hard_limit)->get(); 
            }
            return $sensors;
    }

    /**
     * Display the live charts data page.
     * /charts/live/{chart}/{teamid}/{refid?}/{paramid?}
     *
     * @param  string  $chart
     * @param  int  $teamid
     * @param  int  $refid
     * @param  int  $paramid
     * @return \Illuminate\Http\Response
     */
    public function live($chart, $teamid, $refid = null, $paramid = null)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // get the team
        $team = Team::with('refs')->findOrFail($teamid);

        // check if the user belongs to the team
        if (!Auth::user()->isMember($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para visualizar este recurso");
        }

        $dataset = array();

        //array of allowed charts
        $charts = ['line', 'bar', 'radar'];

        // check if provided chart is allowed
        if (!isset($chart) || !in_array($chart, $charts))
        {
            $chart = 'line';
        }

        // if a refid is provided
        if(!is_null($refid))
        {
            $refid = intval($refid);

            // get the ref provided
            $ref = Ref::findOrFail($refid);

            $dataset[$ref->ref] = array();

            // get the params of the ref
            if (!is_null($paramid)){
                $paramid = intval($paramid);
                $params = Param::where('ref_id', $ref->id)->where('id', $paramid)->get();
            } else {
                $params = Param::where('ref_id', $ref->id)->get();
            }

            // get all the params to populate the droplist
            $paramsl = Param::where('ref_id', $ref->id)->get();

            // iterate all the params
            foreach ($params as $param)
            {
                array_push($dataset[$ref->ref], $param->param);
            }
        }
        else
        {
            // create dataset var -> array of refs and params
            // Get the refs of the team
            $refs = $team->refs;

            // iterate all refs from the team
            foreach ($refs as $ref)
            {
                $dataset[$ref->ref] = array();
                // get the params of the ref
                $paramsl = Param::where('ref_id', $ref->id)->get();

                // iterate all the params
                foreach ($paramsl as $param)
                {
                    array_push($dataset[$ref->ref], $param->param);
                }
            }
        }

        $view = [
            'title' => "Gráficos em Tempo Real - ".$team->name,
            'teamid' => $teamid,
            'refid' => $refid,
            'paramid' => $paramid,
            'dataset' => $dataset,
            'refs' => $team->refs,
            'paramsl' => $paramsl,
            'chart' => $chart,
            'j' => 0
        ];
        return view('dashboard.live', $view);
    }

    /**
     * Returns the Data after {time} and filtered acording to the @params in json.
     * /charts/live/{teamid}/{refid?}/{paramid?}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamid
     * @param  int  $refid
     * @param  int  $paramid
     * @return json
     */
    public function livepost(Request $request, $teamid, $refid = null, $paramid = null)
    {
        if($request->ajax() && $request->time != null)
        {
            // parse vars
            $timestamp = intval($request->time);
            $teamid = intval($teamid);

            // get the team
            $team = Team::findOrFail($teamid);

            // check if the user belongs to the team
            if (!Auth::user()->isMember($team))
            {
                return response()->json(['error' => 'Not Found'], 404);
            }

            // get all the data from the team after the user open the page
            // if specific ref and params specified, filter
            if ((!is_null($refid) && !is_null($paramid)) || (!is_null($refid) && is_null($paramid)))
            {
                $refid = intval($refid);

                // get the ref
                $ref = Ref::findOrFail($refid);

                $sensors = Data::where('teamid', $teamid)->where('ref', $ref->ref)->where('timestamp', '>', $timestamp)->get(['payload', 'timestamp','ref']);
            }
            else
            {
                $sensors = Data::where('teamid', $teamid)->where('timestamp', '>', $timestamp)->get();
            }

            $data = array();

            // iterate per mongo message
            foreach ($sensors as $sensor)
            {
                // if array key does not exist, initialize it
                if (!isset($data[$sensor->ref]))
                {
                    $data[$sensor->ref] = array();
                }

                // get the mongodb packet payload
                $linha = json_decode($sensor->payload);

                // delete ref from payload
                unset($linha->ref);
                // if param is defined remove all except param
                if (!is_null($paramid))
                {
                    // get the param
                    $paramid = intval($paramid);
                    $param = Param::findOrFail($paramid);

                    // unset the params in the payload that we dont want
                    foreach($linha as $key => $cparam)
                    {
                        if ($key != $param->param)
                            unset($linha->$key);
                    }
                }

                // adds time element and cast the timestamp to date
                $linha->time = date('d/m/Y H:i:s', $sensor->timestamp);

                // cast payload to array of arrays
                $aux = array((array) $linha);

                // creates an array of arrays of the refs with the payload values 
                $data[$sensor->ref] = array_merge_recursive($data[$sensor->ref], $aux);
            }
            return response()->json($data);
        }
        return response()->json(['error' => 'Not Found'], 404);
    }

    /**
     * Redirects to the live route according to the body params.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function genRoute(Request $request)
    {
        $this->validate($request, [
            'teamid' => 'required|integer',
            'chart' => 'required',
            'refid' => 'nullable|integer',
            'paramid' => 'nullable|integer',
            'time' => 'required|integer'
        ]);

        $chart = 'line';

        // check chart vars
        //array of allowed charts
        $charts = ['line', 'bar', 'radar'];

        // check if provided chart is allowed
        if (!in_array($request->chart, $charts))
        {
            $chart = 'line';
        }

        // if null ref
        if($request->refid == 0)
        {
            return redirect()->route('live', [$chart, $request->teamid])->with('time', $request->time);
        }
        elseif($request->paramid == 0) // if null param
        {
            //check ref
            $refs = Ref::where('id', $request->refid)->where('team_id', $request->teamid)->firstOrFail();

            return redirect()->route('live', [$chart, $request->teamid, $request->refid])->with('time', $request->time);
        }

        //check param
        Param::where('id', $request->paramid)->where('ref_id', $request->refid)->firstOrFail();

        return redirect()->route('live', [$chart, $request->teamid, $request->refid, $request->paramid])->with('time', $request->time);
    }
}