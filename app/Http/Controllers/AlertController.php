<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Param;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
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
     * Show the form for editing the Alarm.
     * /alert/{alertid}/edit
     *
     * @param  int  $alertid
     * @return \Illuminate\Http\Response
     */
    public function edit($alertid)
    {
        // force alertid to int
        $alertid = intval($alertid);

        // get the alert
        $alert = Alert::findOrFail($alertid);

        $params = Param::where('ref_id', $alert->ref->id)->get();

        $params = [
            'title' => "Editar Alerta",
            'alert' => $alert,
            'params' => $params
        ];
        return view('alerts.edit')->with($params);
    }

    /**
     * Update the Alert in BD.
     * /alert/{alertid}
     *
     * @param  int  $alertid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($alertid, Request $request)
    {
        // force alertid to int
        $alertid = intval($alertid);

        // get the alert
        $alert = Alert::with('team')->findOrFail($alertid);

        $this->validate($request, [
            'name' => 'required|string|min:3|max:60',
            'min' => 'required|numeric|different:max',
            'max' => 'required|numeric|gt:min',
            'param' => 'required|integer',
            'enabled' => 'nullable|boolean'
        ]);

        // check if param is tampered
        Param::where('id', $request->param)->where('ref_id', $alert->ref->id)->firstOrFail();

        $alert->name = $request->name;
        $alert->min = floatval($request->min);
        $alert->max = floatval($request->max);
        $alert->param_id = $request->param;

        // check if it has enabled tag
        if($request->enabled)
        {
            $alert->enabled = true;
        } else {
            $alert->enabled = false;
        }

        $alert->save();

        return redirect()->route('pontos.info', $alert->team->id)->with('success', "Alerta modificado com sucesso");
    }

    /**
     * Remove the specified Alert from the DB.
     * /alert/{alertid}
     *
     * @param  int  $alertid
     * @return \Illuminate\Http\Response
     */
    public function destroy($alertid)
    {
        // force alertid to int
        $alertid = intval($alertid);

        // get the alert
        $alert = Alert::findOrFail($alertid);

        $teamid = $alert->team->id;

        // if the user is not admin of the team or is not admin
        if (!Auth::user()->isOwner($alert->team) || !Auth::user()->isAdmin())
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // delete alert
        $alert->delete();

        return redirect()->route('pontos.info', $teamid)->with('success', "Alerta eliminado com sucesso");
    }

    /**
     * Show the form for creating a new Alert.
     * /alert/create/{refid}
     *
     * @return \Illuminate\Http\Response
     */
    public function create($refid)
    {
        // force teamid to int
        $refid = intval($refid);

        $ref = Ref::with('team')->findOrFail($refid);

        // get ref params
        $params = Param::where('ref_id', $ref->id)->get();

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $params = [
            'title' => "Criar Alerta",
            'ref' => $ref,
            'params' => $params
        ];

        return view('alerts.create')->with($params);
    }

    /**
     * Store a newly created Alert in DB.
     * /alert/create/{refid}
     *
     * @param  int  $refid
     * @return \Illuminate\Http\Response
     */
    public function store($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        $ref = Ref::with('team')->findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        $this->validate($request, [
            'name' => 'required|string|min:3|max:60',
            'min' => 'required|numeric|different:max',
            'max' => 'required|numeric|gt:min',
            'param' => 'required|integer',
            'enabled' => 'nullable|nullable'
        ]);

        $enabled = false;
        if ($request->enabled)
            $enabled = true;

        $alert = Alert::create([
            'name' => $request->name,
            'team_id' => $ref->team->id,
            'ref_id' => $ref->id,
            'param_id' => $request->param,
            'min' => $request->min,
            'max' => $request->max,
            'enabled' => $enabled
        ]);

        return redirect()->route('pontos.info', $ref->team->id)->with('success', "Alerta criada com sucesso");
    }

    /**
     * Toggles the enabled state of the Alert.
     * /alert/{alertid}/toggle
     *
     * @param  int  $alertid
     * @return \Illuminate\Http\Response
     */
    public function toggle($alertid)
    {
        // force refid to int
        $alertif = intval($alertid);

        $alert = Alert::with('team')->findOrFail($alertid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($alert->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        if ($alert->enabled)
        {
            $alert->enabled = 0;
        } else {
            $alert->enabled = 1;
        }
        $alert->save();

        return redirect()->route('pontos.info', $alert->team->id)->with('success', "Estado do Alerta alterado com sucesso");
    }
}
