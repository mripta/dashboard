<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Notifications\InviteNotification;
use App\Http\Requests\StoreInviteRequest;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Create a new controller instance that requires authentication
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
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
            'last_ip_address' => '127.0.0.1'
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

    // returns the Invite User view in the admin dashboard
    public function showInvites()
    {
        // pedidos de convite -> quando created_at é null
        $requests = Invite::where('created_at', null)->get();
        // convites pendentes -> quando o user nao se encontra registado -> registered_at = null
        $pinvites = Invite::where('registered_at', null)->where('created_at', '<>', null)->orderBy('created_at', 'desc')->get();
        // convites utilizados -> registo completo -> registered_at <> null
        $uinvites = Invite::where('registered_at', '<>', null)->orderBy('created_at', 'desc')->get();

        $params=[
            'title' => 'Convites',
            'requests' => $requests,
            'pinvites' => $pinvites,
            'uinvites' => $uinvites,
        ];
        return view('users.invite')->with($params);
    }

    // admin delete invite
    public function inviteDestroy($id)
    {
        try
        {
            $invite = Invite::findOrFail($id);
            $invite->delete();
            return redirect()->route('admin.invites')->with('success', "Convite eliminado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('admin.invites')->with('error', "Não foi possível encontrar o convite especificado");
            }
        }
    }

    // creates the invite and sends the email notification from the admin dashboard
    public function inviteCreate(StoreInviteRequest $request)
    {
        $invite = new Invite($request->all());
        $invite->generateInviteToken();
        $invite->save();

        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($invite->getLink()));

        return redirect()->route('admin.invites')->with('success', 'Convite enviado com sucesso.');
    }

    // Invites gets aproved by the admin
    // sends the mail notification to the user and adds the created_at field
    public function inviteNotify($id)
    {
        try
        {
            $invite = Invite::where('id', $id)->where('created_at', null)->firstOrFail();
            $invite->generateInviteToken();
            $invite->update(['created_at' => now()]);
            $invite->save();

            Notification::route('mail', $invite['email'])->notify(new InviteNotification($invite->getLink()));

            return redirect()->route('admin.invites')->with('success', "Convite enviado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('admin.invites')->with('error', "Não foi possível encontrar o convite especificado");
            }
        }
    }
}