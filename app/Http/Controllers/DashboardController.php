<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\User;
use App\Models\Team;
use App\Models\Data;
use App\Models\Param;
use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
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
     * Show the dashboard home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $users = User::all()->count();
        $teams = Team::all()->count();
        $msgc = Data::count();
        $refc = Ref::all()->count();
        $paramc = Param::all()->count();

        // pedidos de convite -> quando o token é null
        $rinvc = Invite::where('token', null)->count();
        // convites pendentes -> quando o user nao se encontra registado -> quando o registered_at é null
        $pinvc = Invite::where('registered_at', null)->where('token', '<>', null)->count();
        // convites utilizados -> registo completo -> quando o registered_at é diferente de  null
        $uinvc = Invite::where('registered_at', '<>', null)->count();

        $params = [
            'usersc' => $users,
            'teamsc' => $teams,
            'msgc' => $msgc,
            'refc' => $refc,
            'paramc' => $paramc,
            'rinvc' => $rinvc,
            'pinvc' => $pinvc,
            'uinvc' => $uinvc,
            'title' => 'Home'
        ];

        return view('dashboard.home')->with($params);
    }
}