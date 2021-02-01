<?php
namespace App\Exports;

// use App\Model\Personal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class ReporteTiemposExport implements FromView,ShouldAutoSize
{
    private $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function view(): View
    {
        return view('Excel.reporte-tiempos', [
            'datos' => $this->datos
        ]);
    }
}