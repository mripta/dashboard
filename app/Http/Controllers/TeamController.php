<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::all();
        $params = [
            'title' => 'Equipas',
            'teams' => $teams,
        ];
        return view('teams.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params = [
            'title' => 'Criar Equipa',
        ];
        return view('teams.create')->with($params);
    }

    /**
     * Store the user
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'last_ip_address' => '127.0.0.1' //$request->ip(),
        ]);

        return redirect()->route('teams.index')->with('success', "Equipa criada com sucesso");
    }

    public function edit($id)
    {
        try
        {
            $user = User::findOrFail($id);
            $params = [
                'title' => "Editar Equipa",
                'user' => $user
            ];
            return view('teams.edit')->with($params);
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('teams.index')->with('error', "Não foi possível encontrar o utilizador especificado");
            }
        }
    }

    public function update(Request $request, $id)
    {
        try
        {
            $team = Team::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            ]);
            $team->name = $request->input('name');
            $team->description = $request->input('description');
            $team->save();

            return redirect()->route('teams.index')->with('success', "Equipa editada com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('teams.index')->with('error', "Não foi possível encontrar a equipa especificado");
            }
        }
    }

    public function destroy($id)
    {
        try
        {
            $team = Team::findOrFail($id);
            $team->delete();
            return redirect()->route('teams.index')->with('success', "Equipa eliminada com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('teams.index')->with('error', "Não foi possível encontrar a equipa especificado");
            }
        }
    }
}
