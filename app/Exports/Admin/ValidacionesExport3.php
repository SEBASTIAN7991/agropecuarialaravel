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

class ValidacionesExport3 implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle,WithColumnFormatting
{
   public function headings(): array
    {
        return [
            'No.',
            'Tipo Asignacion',
            'Proyecto',
            'Costo Unitario',
            'Beneficiarios Registrados',
            'Costo Total'
        ];
    }
    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:F1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE
        ];
    }

    public function title(): string
    {
        return 'Corte de gran Total';
    }
    public function collection()
    {
        return DB::table("validaciones")
        ->join('solicitudes','validaciones.Id_Sol','=','solicitudes.id')
        ->join('proyectos', 'validaciones.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("validaciones.Id, validaciones.Tipo_Asignacion, proyectos.Nom_Pro,proyectos.Monto_Pro,
        sum(validaciones.Cant_Validado),
        sum(validaciones.Cant_Validado)*proyectos.Monto_Pro"))
        ->where('validaciones.Verificado','=','1')
        ->orWhere('validaciones.Verificado','=','2')
        ->groupBy(DB::raw("validaciones.Tipo_Asignacion, proyectos.Nom_Pro"))
        ->get();
    }
}
