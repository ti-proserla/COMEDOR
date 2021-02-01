<?php

namespace App\Http\Controllers;

use App\Model\Personal;
use App\Model\Pedido;
use App\Model\TerminoPedido;
use App\Model\Servicio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function store(Request $request){
        $personal=Personal::where('codigo',$request->codigo_personal)->first();
        if ($personal==null) {
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  "Personal no registrado."
            ]);
        }

        $hora_actual=Carbon::now()->format('H:i:s');
        $servicio=Servicio::where('inicio','<=',$hora_actual)
                            ->where('fin','>=',$hora_actual)
                            ->first();
        if ($servicio==null) {
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  "Fuera del Horario de atención."
            ]);
        }
        $fecha_actual=Carbon::now()->format('Y-m-d');
        $pedido_encontrado=Pedido::where('servicio_id',$servicio->id)
                            ->where(DB::raw('date(created_at)'),$fecha_actual)
                            ->where('codigo_personal',$request->codigo_personal)
                            ->first();
        if ($pedido_encontrado!=null) {
            if ($personal->planilla_id==2&&Carbon::parse($pedido_encontrado->created_at)->diffInMinutes(Carbon::now())>0) {
                $termino=new TerminoPedido();
                $termino->codigo_personal=$request->codigo_personal;
                $termino->save();
                return response()->json([
                    "status"    =>  "OK",
                    "data"      =>  $personal->apellidos.", Salida del comedor registrada. ".Carbon::parse($pedido_encontrado->created_at)->diffInMinutes(Carbon::now())." min."
                ]);
            }
            
            return response()->json([
                "status"    =>  "ERROR",
                "data"      =>  "Usted ya fue atentido recientemente."
            ]);
        }
        $pedido=new Pedido();
        $pedido->codigo_personal=$request->codigo_personal;
        $pedido->servicio_id=$servicio->id;
        $pedido->save();
        $nombre_servicio=$servicio->nombre_servicio;
        return response()->json([
            "status"    =>  "OK",
            "data"      =>  $personal->apellidos.", Ya puedes recoger tu $nombre_servicio."
        ]);
    }
}
