<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParamController extends Controller
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
     * Show the form for editing the Params from RefID.
     * /params/{refid}
     * 
     * @param  int  $refid
     * @return \Illuminate\Http\Response
     */
    public function edit($refid)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }

        // get the params of the ref if they exist
        $param = Param::where('ref_id', $ref->id)->get();

        $params = [
            'title' => "Editar Parâmetros - ".$ref->team->name,
            'ref' => $ref,
            'params' => $param
        ];

        return view('pontos.param')->with($params);
    }

    /**
     * Update the Params from RefID in BD.
     * /params/{refid}
     * 
     * @param  int  $refid
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patch($refid, Request $request)
    {
        // force refid to int
        $refid = intval($refid);

        // get the ref
        $ref = Ref::findOrFail($refid);

        // if the user is not admin of the team
        if (!Auth::user()->isOwner($ref->team))
        {
            return redirect()->route('pontos.index')->with('error', "Não tem acesso para editar este recurso");
        }
        
        $this->validate($request, [
            'param' => 'required|array',
            'param.*' => 'required|string|distinct|max:30',
            'paramname' => 'required|array',
            'paramname.*' => 'required_with:param.*|string|max:50',
        ]);

        // foreach deleteparam -> delete the params
        if(!is_null($request->input('deleteparam')))
        {
            foreach($request->input('deleteparam') as $paramdel)
            {

                $del = Param::where('ref_id', $refid)->where('param', $paramdel)->first();
                if(!is_null($del))
                    $del->delete();
            }
        }

        // foreach param 
        foreach($request->input('param') as $key => $param)
        {
            // check if the param exists
            $temp = Param::where('ref_id', $refid)->where('param', $param)->first();
            
            // if the param exists, update
            if(!is_null($temp))
            {
                $temp->name = $request->input('paramname')[$key];
                $temp->param = $param;
                $temp->save();
            }
            else // creates a new param
            {
                $pm = new Param();
                $pm->name = $request->input('paramname')[$key];
                $pm->param = $param;
                $pm->ref_id = $refid;
                $pm->save();
            }
        }

        return redirect()->route('pontos.info', [$ref->team->id])->with('success', "Parâmetro modificado com sucesso");
    }
}
