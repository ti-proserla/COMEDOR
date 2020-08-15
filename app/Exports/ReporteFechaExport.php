<?php
namespace App\Exports;

// use App\Model\Personal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ReporteFechaExport implements FromView,ShouldAutoSize
{
    private $datos;

    public function __construct($datos,$header)
    {
        $this->datos = $datos;
        $this->header = $header;
    }

    public function view(): View
    {
        $header=[];
        foreach ($this->header as $key => $servicio) {
            $header[]=str_replace(" ","_",$servicio->nombre_servicio);
        }

        // dd(json_decode(json_encode($this->datos),true)[0]);
        return view('Excel.reporte-fecha', [
            'datos' => json_decode(json_encode($this->datos),true),
            'header'=> $header
        ]);
    }
}