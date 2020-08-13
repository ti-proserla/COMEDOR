<?php

namespace App\Http\Controllers;

use App\Model\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personals=Personal::paginate(10);
        return response()->json($personals);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $personal=new Personal();
        $personal->nombres=$request->nombres;
        $personal->apellidos=$request->apellidos;
        $personal->empresa_id=$request->empresa_id;
        $personal->planilla_id=$request->planilla_id;
        $personal->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Personal Registrado."
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
        $personal=Personal::where('id',$id)->first();
        return response()->json($personal);
    }

    /**
     * 
     */
    public function update(Request $request, $id)
    {
        $personal=Personal::where('id',$id)->first();
        $personal->nombres=$request->nombres;
        $personal->apellidos=$request->apellidos;
        $personal->empresa_id=$request->empresa_id;
        $personal->planilla_id=$request->planilla_id;
        $personal->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Personal Actualizado."
        ]);
    }
}
