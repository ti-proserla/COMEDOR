<?php

namespace App\Http\Controllers;

use App\Model\Pedido;
use App\Model\Personal;
use App\Model\Empresa;
use App\Model\Servicio;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Excel;
use App\Exports\ReportePersonalExport;
use App\Exports\ReporteFechaExport;

class ReporteController extends Controller
{
    public function fecha(Request $request){
        $inicio=$request->inicio;
        $fin=$request->fin;
        $servicios=Servicio::all();
        $queryServicio="";
        foreach ($servicios as $key => $servicio) {
            $id=$servicio->id;
            $nombre=str_replace(" ","_",$servicio->nombre_servicio);
            $queryServicio="$queryServicio IF(servicio_id=$id, 4.5, 0) $nombre,";
        }
        $queryServicio=substr($queryServicio,0,-1);
        $queryPlanilla="";
        if($request->has('planilla_id')){
            if($request->planilla_id!=null){
                $queryPlanilla="AND planilla_id=".$request->planilla_id;
            }
        }
        
        $reporte=DB::select(DB::raw("SELECT codigo,nombres,apellidos,created_at fecha, servicio.nombre_servicio, nombre_planilla, 
                                            $queryServicio
                                            FROM personal INNER JOIN pedido ON personal.codigo= pedido.codigo_personal
                                            INNER JOIN servicio ON servicio.id= pedido.servicio_id
                                            INNER JOIN planilla ON planilla.id= personal.planilla_id
                                            WHERE ( DATE(created_at) BETWEEN '$inicio' AND '$fin' )
                                                AND empresa_id=?
                                                $queryPlanilla
                                            ORDER BY codigo ASC, created_at ASC"), [(int)$request->empresa_id]);
        if ($request->has('excel')) {
            $empresa=Empresa::where('id',(int)$request->empresa_id)->first();
            $nombre_empresa=$empresa->nombre_empresa;
            return Excel::download(new ReporteFechaExport($reporte,$servicios), "Reporte-fecha-$inicio-$fin-$nombre_empresa.xlsx");
        }
        return response()->json($reporte);
    }

    public function personal(Request $request){
        $inicio=$request->inicio;
        $fin=$request->fin;
        $codigo=$request->codigo;

        $reporte=DB::select(DB::raw("SELECT personal.codigo,nombres,apellidos,created_at fecha,nombre_servicio
                                            FROM personal INNER JOIN pedido ON personal.codigo= pedido.codigo_personal
                                            INNER JOIN servicio ON servicio.id= pedido.servicio_id
                                            WHERE ( DATE(pedido.created_at) BETWEEN '$inicio' AND '$fin' )
                                                AND personal.codigo=?"), 
                                            [$codigo]);
        if ($request->has('excel')) {
            $personal=Personal::where('codigo',$codigo)->first();
            $nombre_personal=$personal->apellidos;
            return Excel::download(new ReportePersonalExport($reporte), "Reporte-personal-$inicio-$fin-$nombre_personal.xlsx");
        }
        return response()->json($reporte);
    }
}
