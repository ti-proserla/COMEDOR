<?php

namespace App\Http\Controllers;

use App\Model\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use Peru\Http\ContextClient;
// use Peru\Jne\{Dni, DniParser};
use App\Http\Requests\ValidatePersonalNuevo;
use App\Http\Requests\ValidatePersonalEditar;

class PersonalController extends Controller
{
    /**
     * Visualiza todos los Personales
     */
    public function index(Request $request)
    {
        if ($request->search==null||$request->search=="null") {
            $request->search="";
        }
        /**
         * Genera un array de palabras de busqueda
         */
        $texto_busqueda=explode(" ",$request->search);

        $personales=Personal::where(DB::raw("CONCAT(codigo,' ',nombres,' ',apellidos)"),"like","%".$texto_busqueda[0]."%");
        
        for ($i=1; $i < count($texto_busqueda); $i++) { 
            $personales=$personales->where(DB::raw("CONCAT(codigo,' ',nombres,' ',apellidos)"),"like","%".$texto_busqueda[$i]."%");
        }

        if ($request->all==true) {
            $personales=$personales->get();
        }else{
            $personales=$personales->paginate(10);
        }
        return response()->json($personales);
    }
    
    /**
     * Registra un nuevo Personal
     */
    public function store(ValidatePersonalNuevo $request)
    {
        $personal=Personal::where('codigo',$request->codigo)->first();
        if ($personal!=null) {
            return response()->json([
                "status"=> "ERROR",
                "data"  => "Personal ya registrado."
            ]);
        }
        $personal=new Personal();
        $personal->codigo=$request->codigo;
        $personal->nombres=strtoupper($request->nombres);
        $personal->apellidos=strtoupper($request->apellidos);
        $personal->planilla_id=($request->planilla_id==0) ? null : $request->planilla_id;
        $personal->empresa_id=($request->empresa_id==0) ? null : $request->empresa_id;
        $personal->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Personal Registrado."
        ]);
    }

    public function masivo(Request $request){
        DB::beginTransaction();
        $planilla_id=$request->planilla_id;
        $empresa_id=$request->empresa_id;

        $repetidos=0;
        $arrayRepetidos=[];
        $nuevos=0;
        foreach ($request->personales as $key => $item) {
            if (!isset($item["codigo"])||!isset($item["nombres"])||!isset($item["apellidos"])) {
                
                DB::rollback();
                return response()->json([
                    "status"=> "ERROR",
                    "data"  => "Existen datos vacios."
                ]);
            }

            $codigo=$item["codigo"];
            $nombres=$item["nombres"];
            $apellidos=$item["apellidos"];
            
            $personal=Personal::where('codigo',$codigo)->first();

            if ($personal==null) {
                $personal=new Personal();
                $personal->codigo=$codigo;
                $personal->nombres=strtoupper($nombres);
                $personal->apellidos=strtoupper($apellidos);
                $personal->planilla_id=$planilla_id;
                $personal->empresa_id=$empresa_id;
                $personal->save();
                $nuevos++;
            }else{
                array_push($arrayRepetidos,$personal);
                $repetidos++;
            }
        }
        DB::commit();
        return response()->json([
            "status"=> "OK",
            "data"  => "Carga Masiva: $nuevos Nuevos - $repetidos Repetidos.",
            "repetidos" => $arrayRepetidos
        ]);
    }
    
    /**
     * Visualiza datos de un solo Personal
     */
    public function show($id)
    {
        $personal=Personal::where('id',$id)->first();
        return response()->json($personal);
    }
        
    public function update(ValidatePersonalEditar $request, $id)
    {
        $personal=Personal::where('id',$id)->first();
        $personal->nombres=strtoupper($request->nombres);
        $personal->apellidos=strtoupper($request->apellidos);
        $personal->planilla_id=($request->planilla_id==0) ? null : $request->planilla_id;
        $personal->empresa_id=($request->empresa_id==0) ? null : $request->empresa_id;
        $personal->save();
        
        return response()->json([
            "status"=> "OK",
            "data"  => "Personal Actualizado."
        ]);
    }

    /**
     * Desabilita al Personal
     */
    public function estado($id)
    {
        $personal=Personal::where('id',$id)->first();
        $personal->estado=($personal->estado=='0') ? '1' : '0';
        $personal->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Estado Actualizado"
        ]);
    }

    public function jne(Request $request,$dni){
        if ($request->all!=true) {
            $personal=Personal::where('dni',$dni)->first();
            if ($personal!=null) {
                $id=$personal->id;
                return json_encode([
                    "status" => "INFO",
                    "data"   => "El Trabajador ya se encuentra registrado",
                    "id"     => $id
                ]); 
            }
        }
        $cs = new Dni(new ContextClient(), new DniParser());
        $person = $cs->get($dni);
        if (!$person) {
            return json_encode([
                "status" => "ERROR",
                "data"   => "No encontrado"
            ]);
            // echo 'Not found';
            exit();
        }
        return json_encode([
                "status" => "OK",
                "data"   =>$person
            ]);
    }
}
