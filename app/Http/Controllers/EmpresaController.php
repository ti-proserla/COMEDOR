<?php

namespace App\Http\Controllers;

use App\Model\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->has('all')) {
            $empresas=Empresa::all();
        }else{
            $empresas=Empresa::paginate(10);
        }
        return response()->json($empresas);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa=new Empresa();
        $empresa->nombre_empresa=$request->nombre_empresa;
        $empresa->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Empresa Registrada"
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
        $empresa=Empresa::where('id',$id)->first();
        return response()->json($empresa);
    }

    /**
     * 
     */
    public function update(Request $request, $id)
    {
        $empresa=Empresa::where('id',$id)->first();
        $empresa->nombre_empresa=$request->nombre_empresa;
        $empresa->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Empresa Actualizada."
        ]);
    }
}
