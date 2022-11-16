<?php

namespace App\Exports\Admin;

use App\Models\validaciones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ValGeneral implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle,WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'No.',
            'Tipo Asignacion',
            'Coordinador General',
            'Cant. Validado',
            'Proyecto',
            'Costo Unitario',
            'Suma Total'
        ];
    }

    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:G1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }


    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'G' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
        ];
    }


    public function title(): string
    {
        return 'Costo Total Proyectos';
    }

    public function collection()
    {
       return DB::table("validaciones")
        ->join('solicitudes', 'validaciones.Id_Sol', '=', 'solicitudes.id')
        ->join('proyectos', 'validaciones.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("validaciones.Id, validaciones.Tipo_Asignacion, solicitudes.Tipo_Convenio,sum(validaciones.Cant_Validado),proyectos.Nom_Pro,proyectos.Monto_Pro,sum(validaciones.Cant_Validado)*proyectos.Monto_Pro"))
        ->groupBy(DB::raw("solicitudes.Tipo_Convenio,proyectos.Nom_Pro"))
        ->orderBy(DB::raw("validaciones.Tipo_Asignacion, solicitudes.Tipo_Convenio"))
        ->get();
    }
}
