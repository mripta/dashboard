<?php

namespace App\Http\Controllers;

use App\Models\Param;
use App\Models\Alert;
use Illuminate\Http\Request;

class AdminAlertController extends Controller
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
     * Display a listing of the Alerts.
     * /admin/alerts
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::with('team')->with('ref')->with('param')->get();
        $params = [
            'title' => 'Alertas',
            'alerts' => $alerts,
        ];
        return view('alerts.index')->with($params);
    }

    /**
     * Show the form for editing the Alarm.
     * /admin/alerts/{alertid}/edit
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
        return view('alerts.editadmin')->with($params);
    }

    /**
     * Update the Alert in BD.
     * /admin/alerts/{alertid}
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
        $alert = Alert::findOrFail($alertid);

        $this->validate($request, [
            'name' => 'required|string|min:3|max:60',
            'min' => 'required|numeric|different:max',
            'max' => 'required|numeric|gt:min',
            'param' => 'required|integer'
        ]);

        // check if param is tampered
        Param::where('id', $request->param)->where('ref_id', $alert->ref->id)->firstOrFail();

        $alert->name = $request->name;
        $alert->min = floatval($request->min);
        $alert->max = floatval($request->max);
        $alert->param_id = $request->param;
        $alert->save();

        return redirect()->route('alerts.index')->with('success', "Alerta modificado com sucesso");
    }

    /**
     * Remove the specified Alert from the DB.
     * /admin/alerts/{alertid}
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

        // delete alert
        $alert->delete();

        return redirect()->route('alerts.index')->with('success', "Alerta eliminado com sucesso");
    }

    /**
     * Toggles the enabled state of the Alert.
     * /alerts/{alertid}/toggle
     *
     * @param  int  $alertid
     * @return \Illuminate\Http\Response
     */
    public function toggle($alertid)
    {
        // force refid to int
        $alertif = intval($alertid);

        $alert = Alert::findOrFail($alertid);

        if ($alert->enabled)
        {
            $alert->enabled = 0;
        } else {
            $alert->enabled = 1;
        }
        $alert->save();

        return redirect()->route('alerts.index')->with('success', "Estado do Alerta alterado com sucesso");
    }
}
