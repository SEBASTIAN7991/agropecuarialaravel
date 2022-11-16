<?php

namespace App\Exports\Admin;

use App\Models\Solicitudes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;

class SolicitudesCorte implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Id',
            'Tipo de Convenio',
            'Tipo de Proyecto',
            'Solicitud Total',
            'Costo Unitario Proyecto',
            'Costo Total Proyecto'
        ];
    }

    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:F1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }

    public function title(): string
    {
        return 'Solicitudescorte2';
    }

    public function collection()
    { 
        return DB::table("solicitudes")
        ->join('proyectos', 'solicitudes.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("solicitudes.id, solicitudes.Tipo_Convenio, proyectos.Nom_Pro, SUM(solicitudes.Cant_Sol), proyectos.Monto_Pro, SUM(solicitudes.Cant_Sol) * proyectos.Monto_Pro"))
        ->where('solicitudes.validado','=','0')
        ->groupBy(DB::raw("solicitudes.Tipo_Convenio,proyectos.Nom_Pro"))
        ->get();
    }
}
