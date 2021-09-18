<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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

    /**
     * Display the Profile settings page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $params = [
            'title' => 'Modificar Perfil',
            'user' => $user
        ];
        return view('dashboard.profile')->with($params);
    }

    /**
     * Update the user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required','min:8'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        $params = [
            'title' => 'Modificar Perfil',
            'success' => 'Palavra-passe modificada com sucesso'
        ];

        return redirect()->route('profile.definicoes')->with($params);
    }

    /**
     * Update the user profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        if($request->hasFile('avatar')){
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // generate new filename
            $filename = Auth()->user()->id.substr(md5(rand(0, 9) . time()), 0, 10).'.'.request()->avatar->getClientOriginalExtension();
            // save file in avatar storage (defined in the filesystems.php)
            $request->avatar->storeAs('/', $filename, 'avatar');
            // updated db
            $user->image = $filename;
            $user->save();
        }

        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'bio' => 'nullable|string|min:5|max:100',
            'email' => 'required|email:rfc,dns|max:100|unique:users,email,'.$user->id,
        ]);

        if($request->email != $user->email)
        {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->save();

        $params = [
            'title' => 'Modificar Perfil',
            'success' => 'Perfil modificado com sucesso'
        ];

        return redirect()->route('profile.definicoes')->with($params);
    }
}