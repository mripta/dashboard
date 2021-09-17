<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // delete ref
    public function destroy($refname, Request $request)
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

    public function edit($teamid, $refname)
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

        return view('refs.edit')->with($params);
    }

    public function patch($refid, Request $request)
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
            //'ref' => 'required|string|max:50|unique:refs,ref,'.$ref->id,
            'ref' => 'required|string|max:50|unique:refs,ref,NULL,id,team_id,'.$ref->team->id,
            'name' => 'required|string|max:100'
        ]);

        $ref->update($request->all());

        return redirect()->route('pontos.info', $ref->team->id)->with('success', "Referência modificado com sucesso");
    }
}
