<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Invite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Http\Requests\StoreInviteRequest;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'exists:invites'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $invite = Invite::where('email', $data['email'])->firstOrFail();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $invite->registered_at = $user->created_at;
        $invite->save();

        return $user;
    }

    // Returns the request invite public view
    public function inviteRequest() {
        return view('auth.request');
    }

    // save the public invite request in the db
    public function inviteStore(StoreInviteRequest $request)
    {
        $invite = new Invite($request->all());
        //$invite->generateInviteToken();
        $invite->timestamps = false;
        $invite->save();

        return redirect()->route('login')
            ->with('success', 'Pedido de registo enviado com sucesso.');
    }

    // shows the registration form and send the email var
    public function inviteRegisterForm(Request $request)
    {
        $token = $request->get('token');
        $invite = Invite::where('token', $token)->firstOrFail();
        $email = $invite->email;

        return view('auth.register', compact('email'));
    }
}
