<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Data;
use App\Models\Team;
use App\Models\User;
use App\Models\Param;
use Illuminate\Http\Request;

class PontoController extends Controller
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
     * Display the user teams page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // user teams
        $teams = User::find(auth()->user()->id)->teams;

        $params = [
            'title' => 'Pontos de Recolha',
            'teams' => $teams
        ];

        $title = "Pontos de Recolha";
        return view('pontos.index')->with($params);
    }

    /**
     * Display the Ponto de Recolha individual page
     *
     * @return \Illuminate\Http\Response
     */
    public function info($teamid)
    {
        try
        {
            // force teamid to int
            $teamid = intval($teamid);

            $dataset = array();

            $team = Team::findOrFail($teamid);
            $datac = Data::where('teamid', $teamid)->count();
            
            $paramc = 0;
            foreach($team->refs as $ref)
            {
                $paramc += Param::where('ref_id', $ref->id)->count();
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

            $params = [
                'title' => "Ponto de Recolha",
                'team' => $team,
                'data' => $datac,
                'params' => $paramc,
                'dataset' => $dataset
            ];
            //dd($datac);
            return view('pontos.info')->with($params);
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('pontos.index')->with('error', "Não foi possível encontrar o Ponto de recolha especificado");
            }
        }
    }

    /**
     * Cria um Ponto de Recolha num POST
     * Mostra view num GET
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if $request is POST
        if($request->method() == "POST")
        {
            $this->validate($request, [
                'name' => 'required|string|max:50',
                'desc' => 'required|string|max:100',
                'username' => 'required|string|max:10|unique:teams',
                'password' => 'required|string|min:6|confirmed',
                'users' => 'array'
            ]);
    
            $team = Team::create([
                'name' => $request->input('name'),
                'description' => $request->input('desc'),
                'username' => $request->input('username'),
                'password' => bcrypt($request->input('password'))
            ]);

            // add the auth user to the team with ownership
            $team->users()->attach(auth()->user()->id, ['owner' => 1]);

            // iterate users list, check if the users exists :)
            // and add them to the team
            if ($request->input('users') != null)
            {
                foreach($request->input('users') as $user)
                {
                    if (User::where('id', $user)->count() == 1)
                        $team->users()->attach($user);
                }
            }

            // iterate refs and add them to the team
            if ($request->input('refs') != null)
            {
                foreach($request->input('refs') as $key => $ref)
                {
                    if ($ref != null){
                        $sense = new Ref();
                        $sense->name = $request->input('refsname')[$key];
                        $sense->ref = $ref;
                        $sense->team_id = $team->id;
                        $sense->save();
                    }
                }
            }
    
            return redirect()->route('pontos.index')->with('success', "Ponto de Recolha criado com sucesso");
        }
        else
        {
            // get all users
            $users = User::where('id', '<>', auth()->user()->id)->get();

            $params = [
                'title' => "Criar Ponto de Recolha",
                'users' => $users
            ];
            return view('pontos.create')->with($params);
        }
    }
}
