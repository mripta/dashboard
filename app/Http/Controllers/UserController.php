<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $users = User::all();
        $params = [
            'title' => 'Utilizadores',
            'users' => $users,
        ];
        return view('users.index')->with($params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $user = User::findOrFail($id);
            $params = [
                'title' => "Editar Utilizador",
                'user' => $user
            ];
            return view('users.edit')->with($params);
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('users.index')->with('error', "Não foi possível encontrar o utilizador especificado");
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $user = User::findOrFail($id);

            $this->validate($request, [
                'name' => 'required|string|min:3|max:50',
                'bio' => 'nullable|string|min:5|max:100',
                'email' => 'required|email:rfc,dns|max:100|unique:users,email,'.$id,
                'admin' => 'boolean',
                'teamadmin' => 'array'
            ]);
            
            // if null -> user is not owner of any team
            if (is_null($request->teamadmin))
            {
                foreach($user->teams as $team)
                {
                    // detach
                    $team->users()->detach($user->id);
                    //attach
                    $team->users()->attach($user->id, ['owner' => 0]);
                }   
            }
            else // there are ownership
            {
                foreach($user->teams as $team)
                {
                    // clear perms
                    // detach
                    $team->users()->detach($user->id);
                    //attach
                    $team->users()->attach($user->id, ['owner' => 0]);

                    foreach($request->teamadmin as $key => $state)
                    {
                        if ($team->id == $key)
                        {
                            // detach
                            $team->users()->detach($user->id);
                            //attach
                            $team->users()->attach($user->id, ['owner' => 1]);
                        }
                    }
                }
            }

            if (!is_null($request->input('password')))
            {
                $this->validate($request, [
                    'password' => 'required|string|min:6|confirmed'
                ]);
                $user->password = bcrypt($request->input('password'));
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->bio = $request->bio;

            // check if it has admin tag
            if($request->admin)
            {
                $user->admin = true;
            } else {
                $user->admin = false;
            }
            $user->save();

            return redirect()->route('users.index')->with('success', "Utilizador editado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('users.index')->with('error', "Não foi possível encontrar o utilizador especificado");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            if (Auth::user()->id == $id || $id == 1)
                return redirect()->route('users.index')->with('error', "Não é possível eliminar este utilizador");

            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('users.index')->with('success', "Utilizador eliminado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('users.index')->with('error', "Não foi possível encontrar o utilizador especificado");
            }
        }
    }
}