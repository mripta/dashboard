<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Data;
use App\Models\Team;
use App\Models\User;
use App\Models\Param;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $parammax = 0;
            $paramc = 0;

            $team = Team::findOrFail($teamid);
            $datac = Data::where('teamid', $teamid)->count();
            
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
                
                // save max number of params
                if (count($params) > $parammax)
                    $parammax = count($params);

                // iterate all the params
                foreach ($params as $param)
                {
                    array_push($dataset[$ref->ref], $param->param);
                }
            }

            $params = [
                'title' => "Ponto de Recolha",
                'team' => $team,
                'datac' => $datac,
                'paramc' => $paramc,
                'dataset' => $dataset,
                'refs' => $refs,
                'parammax' => $parammax
            ];

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
                'users' => 'array',
                'refs' => 'array',
                'refs.*' => 'distinct|max:30',
                'refsname' => 'array',
                'refsname.*' => 'required_with:refs.*|max:50'
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
            if (!is_null($request->input('users')))
            {
                foreach($request->input('users') as $user)
                {
                    if (User::where('id', $user)->count() == 1)
                        $team->users()->attach($user);
                }
            }

            // iterate refs and add them to the team
            if (!is_null($request->input('refs')))
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

    // returns edit view
    public function edit($teamid)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // get the ref
        $team = team::findOrFail($teamid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // get all users
        $users = User::where('id', '<>', auth()->user()->id)->get();

        $params = [
            'title' => "Editar Ponto de Recolha - ".$team->name,
            'team' => $team,
            'users' => $users
        ];

        return view('pontos.edit')->with($params);
    }

    public function patch($teamid, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // get the ref
        $team = team::findOrFail($teamid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $this->validate($request, [
            'name' => 'required|string|max:50',
            'desc' => 'required|string|max:100',
            'username' => 'required|string|max:10|unique:teams,username,'.$team->id,
            'users' => 'array'
        ]);

        if (!is_null($request->input('password')))
        {
            $this->validate($request, [
                'password' => 'required|string|min:6|confirmed'
            ]);
            $team->password = bcrypt($request->input('password'));
        }

        $team->name = $request->input('name');
        $team->description = $request->input('desc');
        $team->username = $request->input('username');

        $team->save();

        // remove all the users
        $team->users()->detach();

        // add the auth user to the team with ownership
        $team->users()->attach(auth()->user()->id, ['owner' => 1]);

        // iterate users list, check if the users exists :)
        // and add them to the team
        if (!is_null($request->input('users')))
        {
            foreach($request->input('users') as $user)
            {
                if (User::where('id', $user)->count() == 1)
                    $team->users()->attach($user);
            }
        }

        return redirect()->route('pontos.index')->with('success', "Ponto de Recolha editado com sucesso");
    }

    // ============================== PARAMS ==============================

    /**
     * /params/{refid}
     * Edit the params from the refid
     */
    public function paramEdit($refid)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // get the params of the ref if they exist
        $param = Param::where('ref_id', $ref->id)->get();

        $params = [
            'title' => "Editar Parâmetros - ".$ref->team->name,
            'ref' => $ref,
            'params' => $param
        ];

        return view('pontos.param')->with($params);
    }

    /**
     * Process the PATCH from the form edit params from ref
     * /params/{refid}
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function paramPatch($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }
        
        $this->validate($request, [
            'param' => 'required|array',
            'param.*' => 'required|string|distinct|max:30',
            'paramname' => 'required|array',
            'paramname.*' => 'required_with:param.*|string|max:50',
        ]);

        // foreach deleteparam -> delete the params
        if(!is_null($request->input('deleteparam')))
        {
            foreach($request->input('deleteparam') as $paramdel)
            {

                $del = Param::where('ref_id', $refid)->where('param', $paramdel)->first();
                if(!is_null($del))
                    $del->delete();
            }
        }

        // foreach param 
        foreach($request->input('param') as $key => $param)
        {
            // check if the param exists
            $temp = Param::where('ref_id', $refid)->where('param', $param)->first();
            
            // if the param exists, update
            if(!is_null($temp))
            {
                $temp->name = $request->input('paramname')[$key];
                $temp->param = $param;
                $temp->save();
            }
            else // creates a new param
            {
                $pm = new Param();
                $pm->name = $request->input('paramname')[$key];
                $pm->param = $param;
                $pm->ref_id = $refid;
                $pm->save();
            }
        }

        return redirect()->route('pontos.info', [$ref->team->id])->with('success', "Parâmetro modificado com sucesso");
    }

    // ============================== REFS ==============================

    public function refCreate($teamid, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        $team = Team::findOrFail($teamid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // if $request is POST
        if($request->method() == "POST")
        {
            $this->validate($request, [
                'ref' => 'required|string|max:50|unique:refs,ref,NULL,id,team_id,'.$teamid,
                'name' => 'required|string|max:100',
            ]);

            $ref = Ref::create([
                'ref' => $request->input('ref'),
                'name' => $request->input('name'),
                'team_id' => $teamid
            ]);
    
            return redirect()->route('pontos.info', [$teamid])->with('success', "Referência criada com sucesso");
        }
        else // return the view
        {
            $params = [
                'title' => "Criar Referência",
                'teamid' => $teamid
            ];
            return view('pontos.createref')->with($params);
        }
    }

    // delete ref
    public function refDestroy($refname, Request $request)
    {
        // force teamid to int
        $teamid = intval($request->input('teamid'));

        // get the ref by the name and team id
        $ref = Ref::where('ref', $refname)->where('team_id', $teamid)->firstOrFail();

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // delete params
        $ref->params()->delete();
        // delete ref
        $ref->delete();

        return redirect()->route('pontos.info', $teamid)->with('success', "Referência eliminada com sucesso");
    }

    public function refEdit($teamid, $refname)
    {
        // force teamid to int
        $teamid = intval($teamid);

        // get the ref
        $ref = Ref::where('ref', $refname)->where('team_id', $teamid)->firstOrFail();

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $params = [
            'title' => "Editar Referência - ".$ref->team->name,
            'ref' => $ref
        ];

        return view('pontos.editref')->with($params);
    }

    public function refPatch($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $this->validate($request, [
            'ref' => 'required|string|max:50|unique:refs,ref,'.$ref->id,
            'name' => 'required|string|max:100'
        ]);

        $ref->update($request->all());

        return redirect()->route('pontos.info', $ref->team->id)->with('success', "Referência modificado com sucesso");
    }
}