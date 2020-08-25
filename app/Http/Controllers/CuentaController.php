<?php

namespace App\Http\Controllers;

use App\Model\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

// use App\Http\Requests\CuentaNuevo;
// use App\Http\Requests\CuentaEditar;
use Carbon\Carbon;

class CuentaController extends Controller
{
    /**
     * Visualiza todos los cuentaes
     */
    public function index(Request $request)
    {
        if ($request->search==null||$request->search=="null") {
            $request->search="";
        }
        $cuentaes=Cuenta::where(DB::raw('CONCAT(nombre,apellido)'),'like','%'.$request->search.'%')->paginate(8);
        return response()->json($cuentaes);
    }

    /**
     * Registra un nuevo cuenta
     */
    public function store(CuentaNuevo $request)
    {
        $cuenta=new Cuenta();
        $cuenta->nombre=strtoupper($request->nombre);
        $cuenta->apellido=strtoupper($request->apellido);
        $cuenta->usuario=strtoupper($request->usuario);
        $cuenta->password=strtoupper($request->password);
        $cuenta->api_token='moment';
        $cuenta->estado='0';
        $cuenta->rol_id=$request->rol_id;
        $cuenta->fundo_id=$request->fundo_id;
        $cuenta->save();
        $cuenta->api_token=$cuenta->id.'_'.Carbon::now()->format('YmdHisu');
        $cuenta->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "cuenta Registrado"
        ]);
        
    }
        
        /**
         * Visualiza datos de un solo cuenta
         */
    public function show($id)
    {
        $cuenta=Cuenta::where('id',$id)->first();
        return response()->json($cuenta);
    }
        
    public function update(CuentaEditar $request, $id)
    {
        $cuenta=Cuenta::where('id',$id)->first();
        $cuenta->nombre=strtoupper($request->nombre);
        $cuenta->apellido=strtoupper($request->apellido);
        $cuenta->fundo_id=strtoupper($request->fundo_id);
        $cuenta->rol_id=$request->rol_id;
        $cuenta->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "cuenta Actualizado"
        ]);
    }

    /**
     * Desabilita al cuenta
     */
    public function estado($id)
    {
        $cuenta=Cuenta::where('id',$id)->first();
        $cuenta->estado=($cuenta->estado=='0') ? '1' : '0';
        $cuenta->save();
        return response()->json([
            "status"=> "OK",
            "data"  => "Estado Actualizado"
        ]);
    }

    public function rutas(Request $request){
        $rol=$request->rol;
        $listaRutas=[];
        switch ($rol) {
            case 'ADMINISTRADOR':
                $listaRutas=["/atencion","/empresa","/planilla","/personal","/reporte-personal","/reporte-fecha","/servicio"];
                break;
            case 'REPORTEADOR':
                $listaRutas=["/reporte-fecha","/reporte-personal"];
                break;
            case 'TERMINAL':
                $listaRutas=["/atencion"];
                break;
            default:
                $listaRutas=[];
                break;
        }
        array_push($listaRutas,"/");
        return response()->json($listaRutas);
    }

    public function login(Request $request){
        $cuenta=Cuenta::where('usuario',$request->usuario)
            ->where('password',$request->password)
            ->select('api_token','id','nombre','apellido','usuario','rol')
            ->first();
        // dd($request->all());
        if ($cuenta==null) {
            return response()->json([
                "status"=> "ERROR",
                "data"  => "Usuario o Contraseña incorrecta."
            ]);
        }else{
            return response()->json([
                "status"=> "OK",
                "data"  => $cuenta
            ]);
        }
    }
}
