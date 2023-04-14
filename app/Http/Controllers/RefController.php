<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Ref;
use App\Models\Team;
use Illuminate\Http\Request;

class RefController extends Controller
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
     * Show the form for creating a new Ref.
     * /ref/create/{teamid}
     *
     * @return \Illuminate\Http\Response
     */
    public function create($teamid)
    {
        // force teamid to int
        $teamid = intval($teamid);

        $team = Team::findOrFail($teamid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $params = [
            'title' => "Criar Referência",
            'teamid' => $teamid
        ];

        return view('refs.create')->with($params);
    }

    /**
     * Store a newly created Ref in DB.
     * /ref/create/{teamid}
     *
     * @param  int  $teamid
     * @return \Illuminate\Http\Response
     */
    public function store($teamid, Request $request)
    {
        // force teamid to int
        $teamid = intval($teamid);

        $team = Team::findOrFail($teamid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $this->validate($request, [
            'ref' => 'required|string|max:50|regex:/^[a-zA-Z][a-zA-Z0-9]*$/|unique:refs,ref,NULL,id,team_id,'.$team->id,
            'name' => 'required|string|max:100',
        ]);

        $ref = Ref::create([
            'ref' => $request->input('ref'),
            'name' => $request->input('name'),
            'team_id' => $teamid
        ]);

        return redirect()->route('pontos.info', $teamid)->with('success', "Referência criada com sucesso");
    }

    /**
     * Remove the specified Ref from the DB.
     * /ref/{refid}
     *
     * @param  int  $refid
     * @return \Illuminate\Http\Response
     */
    public function destroy($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref by the name and team id
        $ref = Ref::with('team')->findOrFail($refid);

        $teamid = $ref->team->id;

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

    /**
     * Show the form for editing the specified Ref.
     * /ref/{refid}/edit
     *
     * @param  int  $refid
     * @return \Illuminate\Http\Response
     */
    public function edit($refid)
    {
        // force teamid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::with('team')->findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $params = [
            'title' => "Editar Referência - ".$ref->team->name,
            'ref' => $ref
        ];

        return view('refs.edit')->with($params);
    }

    /**
     * Update the Ref in BD.
     * /ref/{refid}
     *
     * @param  int  $refid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patch($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::with('team')->findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $this->validate($request, [
            'ref' => 'required|string|max:50|unique:refs,ref,NULL,id,team_id,'.$ref->team->id,
            'name' => 'required|string|max:100'
        ]);

        $ref->update($request->all());

        return redirect()->route('pontos.info', $ref->team->id)->with('success', "Referência modificado com sucesso");
    }
}