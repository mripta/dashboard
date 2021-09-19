<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Create a new controller instance that requires authentication
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','admin']);
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
            'title' => 'Pontos de Recolha',
            'teams' => $teams,
        ];
        return view('teams.index')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $team = Team::findOrFail($id);

            $teamusers = array();

            // get the users from the team
            // and add the ids to an array
            foreach($team->users as $user)
            {
                array_push($teamusers, $user->id);
            }

            // get the users that do not belong to the team
            $users = User::whereNotIn('id', $teamusers)->get();

            $params = [
                'title' => "Editar Ponto de Recolha",
                'team' => $team,
                'users' => $users
            ];
            return view('teams.edit')->with($params);
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('teams.index')->with('error', "Não foi possível encontrar o ponto de recolha especificado");
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:100',
            'username' => 'required|string|max:10|alpha_dash|unique:teams,username,'.$id,
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
        $team->description = $request->input('description');
        $team->username = $request->input('username');
        $team->save();

        // remove regular users
        $team->users()->wherePivot('owner', 0)->detach();

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

        return redirect()->route('teams.index')->with('success', "Ponto de recolha editado com sucesso");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $team = Team::findOrFail($id);
            $team->delete();
            return redirect()->route('teams.index')->with('success', "Ponto de recolha eliminado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('teams.index')->with('error', "Não foi possível encontrar o ponto de recolha especificado");
            }
        }
    }
}
