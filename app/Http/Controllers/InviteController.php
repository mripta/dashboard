<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Notifications\InviteNotification;
use App\Http\Requests\StoreInviteRequest;
use Illuminate\Support\Facades\Notification;

class InviteController extends Controller
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

    public function show()
    {
        // pedidos de convite -> quando o token é null
        $requests = Invite::where('token', null)->get();
        // convites pendentes -> quando o user nao se encontra registado -> quando o registered_at é null
        $pinvites = Invite::where('registered_at', null)->where('token', '<>', null)->orderBy('created_at', 'desc')->get();
        // convites utilizados -> registo completo -> quando o registered_at é diferente de  null
        $uinvites = Invite::where('registered_at', '<>', null)->orderBy('created_at', 'desc')->get();

        $params=[
            'title' => 'Convites',
            'requests' => $requests,
            'pinvites' => $pinvites,
            'uinvites' => $uinvites,
        ];
        return view('users.invite')->with($params);
    }

    // creates the invite and sends the email notification from the admin dashboard
    public function create(StoreInviteRequest $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|unique:invites|unique:users'
        ]);

        $invite = new Invite($request->all());
        $invite->generateInviteToken();
        $invite->save();

        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($invite->getLink()));

        return redirect()->route('invite.show')->with('success', 'Convite enviado com sucesso.');
    }

        // admin delete invite
        public function destroy($id)
        {
            try
            {
                $invite = Invite::findOrFail($id);
                $invite->delete();
                return redirect()->route('invite.show')->with('success', "Convite eliminado com sucesso");
            }
            catch (ModelNotFoundException $ex)
            {
                if ($ex instanceof ModelNotFoundException)
                {
                    return redirect()->route('invite.show')->with('error', "Não foi possível encontrar o convite especificado");
                }
            }
        }

    // Invites gets aproved by the admin
    // sends the mail notification to the user and adds the created_at field
    public function notify($id)
    {
        try
        {
            $invite = Invite::where('id', $id)->where('token', null)->firstOrFail();
            $invite->generateInviteToken();
            $invite->save();

            Notification::route('mail', $invite['email'])->notify(new InviteNotification($invite->getLink()));

            return redirect()->route('invite.show')->with('success', "Convite enviado com sucesso");
        }
        catch (ModelNotFoundException $ex)
        {
            if ($ex instanceof ModelNotFoundException)
            {
                return redirect()->route('invite.show')->with('error', "Não foi possível encontrar o convite especificado");
            }
        }
    }
}
