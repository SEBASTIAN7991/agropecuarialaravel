<?php

namespace App\Exports\Admin;

use App\Models\Admin\Solicitudes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;


class SolicitudesExcel implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function headings(): array
    {
        return [
            'Id',
            'Folio',
            'Organizacion',
            'Cargo del Representante',
            'Fecha Solicitud',
            'Localidad Solicitud',
            'Region Solicitud',
            'Proyecto Solicitado',
            'Entregado Por',
            'Telefono de Contacto',
            'Cant. Sol',
            'Hom.',
            'Muj.',
            'Tipo Convenio',
            'Validado',
            'Comentario',
            'Costo Total',
            'Fecha Creacion',
            'Actualizacion'
        ];
    }

    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:S1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }

    public function title(): string
    {
        return 'Solicitudes';
    }

    public function collection()
    { 
        return DB::table("solicitudes")
        ->join('organizaciones', 'solicitudes.Id_Org', '=', 'organizaciones.id')
        ->join('cargos', 'solicitudes.Id_Cargo', '=', 'cargos.id')
        ->join('localidades', 'solicitudes.Id_Loc', '=', 'localidades.id')
        ->join('regiones', 'solicitudes.Id_Reg', '=', 'regiones.id')
        ->join('proyectos', 'solicitudes.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("solicitudes.id, solicitudes.Folio, organizaciones.Nom_Org, cargos.Nombre_Cargo, solicitudes.Fecha_Sol, localidades.Nom_Loc, regiones.Nom_Reg, proyectos.Nom_Pro,
            solicitudes.Subrepresentante, solicitudes.Telefono, solicitudes.Cant_Sol, solicitudes.Ben_H, solicitudes.Ben_M, solicitudes.Tipo_Convenio, solicitudes.Validado,solicitudes.Comentario,
            solicitudes.Cant_Sol * proyectos.Monto_Pro, solicitudes.created_at, solicitudes.updated_at"))
        ->get();
    }
}
