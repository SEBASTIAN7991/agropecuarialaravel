<?php

namespace App\Exports\Admin;

use App\Models\Admin\Validaciones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ValidacionesExport2 implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle,WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'No',
            'Validador',
            'Coordinador General',
            'Tipo_Asignacion',
            'Fecha Validacion',
            'Estatus',
            'Total Validado',
            'Hombre',
            'Mujer',
            'Costo Unitario',
            'Suma Total',
            //'Total Solicitado',
            //'Hombre',
            //'Mujer',
            'Representante',
            'Organizacion',
            'Localidad',
            'Observaciones',
            'Proyecto Solicitado',
            'Region'
        ];
    }
    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:R1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }
    public function title(): string
    {
        return 'Valid. Sin Ben. Registrados';
    }
    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'K' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
        ];
    }
    public function collection()
    {
        return DB::table("validaciones")
        ->join('solicitudes', 'validaciones.Id_Sol', '=', 'solicitudes.id')
        ->join('regiones', 'solicitudes.Id_Reg', '=', 'regiones.id')
        ->join('organizaciones', 'solicitudes.Id_Org', '=', 'organizaciones.id')
        ->join('localidades', 'solicitudes.Id_Loc', '=', 'localidades.id')
        ->join('proyectos', 'validaciones.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("validaciones.Id,validaciones.Resp_Valid, solicitudes.Tipo_Convenio, validaciones.Tipo_Asignacion, validaciones.Fecha_Val_Inicio,validaciones.Verificado,validaciones.Cant_Validado,validaciones.Ben_H_Validado,validaciones.Ben_M_Validado,
            proyectos.Monto_Pro, validaciones.Cant_Validado * proyectos.Monto_Pro,
            solicitudes.Subrepresentante, organizaciones.Nom_Org, localidades.Nom_Loc ,validaciones.Comentario,proyectos.Nom_Pro, regiones.Nom_Reg"))
        ->groupBy(DB::raw("validaciones.id"))
        ->orderBy(DB::raw("validaciones.Tipo_Asignacion, solicitudes.Tipo_Convenio"))
        ->get();
    }
}
