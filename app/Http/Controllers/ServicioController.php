<?php

namespace App\Http\Controllers;

use App\Model\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('all')) {
            $servicios=Servicio::all();
        }else{
            $servicios=Servicio::paginate(10);
        }
        return response()->json($servicios);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $servicio=new Servicio();
        $servicio->nombre_servicio=$request->nombre_servicio;
        $servicio->inicio=$request->inicio;
        $servicio->fin=$request->fin;
        $servicio->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Servicio Registrado"
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
        $servicio=Servicio::where('id',$id)->first();
        return response()->json($servicio);
    }

    /**
     * 
     */
    public function update(Request $request, $id)
    {
        $servicio=Servicio::where('id',$id)->first();
        $servicio->nombre_servicio=$request->nombre_servicio;
        $servicio->inicio=$request->inicio;
        $servicio->fin=$request->fin;
        $servicio->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Servicio Actualizado."
        ]);
    }
}
