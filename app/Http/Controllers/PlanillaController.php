<?php

namespace App\Http\Controllers;

use App\Model\Planilla;
use Illuminate\Http\Request;

class PlanillaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $planillas=Planilla::all();
        }else{
            $planillas=Planilla::paginate(10);
        }
        return response()->json($planillas);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $planilla=new Planilla();
        $planilla->nombre_planilla=$request->nombre_planilla;
        $planilla->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Planilla Registrada"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $planilla=Planilla::where('id',$id)->first();
        return response()->json($planilla);
    }

    /**
     * 
     */
    public function update(Request $request, $id)
    {
        $planilla=Planilla::where('id',$id)->first();
        $planilla->nombre_planilla=$request->nombre_planilla;
        $planilla->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Planilla Actualizada."
        ]);
    }
}
