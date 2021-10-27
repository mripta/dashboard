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
     * Display a listing of the Teams.
     * /admin/teams
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all the teams
        $teams = Team::all();

        $params = [
            'title' => 'Pontos de Recolha',
            'teams' => $teams,
        ];

        return view('teams.index')->with($params);
    }

    /**
     * Show the form for editing the specified Team.
     * /admin/teams/{teamid}/edit
     *
     * @param  int $teamid
     * @return \Illuminate\Http\Response
     */
    public function edit($teamid)
    {
        // find the team
        $team = Team::findOrFail($teamid);

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

    /**
     * Update the specified Team in storage.
     * /admin/teams/{teamid}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $teamid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $teamid)
    {
        $team = Team::findOrFail($teamid);

        $this->validate($request, [
            'name' => 'required|string|max:50|unique:teams,name,'.$teamid,
            'description' => 'required|string|max:100',
            'username' => 'required|string|max:10|alpha_dash|unique:teams,username,'.$teamid,
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
     * Remove the specified Team from storage.
     * /admin/teams/{teamid}
     *
     * @param  int  $teamid
     * @return \Illuminate\Http\Response
     */
    public function destroy($teamid)
    {
        // get the team
        $team = Team::findOrFail($teamid);

        $team->delete();

        return redirect()->route('teams.index')->with('success', "Ponto de recolha eliminado com sucesso");
    }
}