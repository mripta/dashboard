<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $users = User::all();
        $params = [
            'title' => 'Utilizadores',
            'users' => $users,
        ];
        return view('users.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $params = [
            'title' => 'Adicionar Utilizador',
        ];
        return view('users.create')->with($params);
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

        return redirect()->route('users.index')->with('success', "Utilizador adicionado com sucesso");
    }

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

    public function update(Request $request, $id)
    {
        try
        {
            $user = User::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            ]);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
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
