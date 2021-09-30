<?php

namespace App\Http\Controllers;

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
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display the Invites page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

    /**
     * Creates the invite and sends the email notification from the admin dashboard
     * /admin/invite
     *
     * @param  StoreInviteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreInviteRequest $request)
    {
        $this->validate($request, [
            'email' => 'required|email:rfc,dns|max:100|unique:invites|unique:users'
        ]);

        $invite = new Invite($request->all());
        $invite->generateInviteToken();
        $invite->save();

        Notification::route('mail', $request->input('email'))->notify(new InviteNotification($invite->getLink()));

        return redirect()->route('invite.index')->with('success', 'Convite enviado com sucesso.');
    }

    /**
     * Remove the specified Invite from storage.
     * /admin/invite/{inviteid}
     *
     * @param  int  $inviteid
     * @return \Illuminate\Http\Response
     */
    public function destroy($inviteid)
    {
        $invite = Invite::findOrFail($inviteid);
        $invite->delete();
        return redirect()->route('invite.index')->with('success', "Convite eliminado com sucesso");
    }

    /**
     * When the Invites gets aproved by the admin sends the mail 
     * notification to the user and adds the created_at field
     * /admin/invite/{inviteid}
     *
     * @param  int  $inviteid
     * @return \Illuminate\Http\Response
     */
    public function notify($inviteid)
    {
        $invite = Invite::where('id', $inviteid)->where('token', null)->firstOrFail();
        $invite->generateInviteToken();
        $invite->save();

        Notification::route('mail', $invite['email'])->notify(new InviteNotification($invite->getLink()));

        return redirect()->route('invite.index')->with('success', "Convite enviado com sucesso");
    }
}