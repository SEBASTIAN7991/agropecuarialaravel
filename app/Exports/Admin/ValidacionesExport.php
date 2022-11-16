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

class ValidacionesExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithTitle
{
    
    public function headings(): array
    {
        return [
            'No',
            'Validador',
            'Coordinador General',
            'Tipo Asignacion',
            'Fecha Validacion',
            'Ben. Registrados',
            'Total Validado',
            'Hombre',
            'Mujer',
            'Total Solicitado',
            'Hombre',
            'Mujer',
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
        return 'Valid. con Ben. Registrados';
    }
    public function collection()
    {
        return DB::table("validaciones")
        ->join('beneficiarios','validaciones.id','=','beneficiarios.Id_Val')
        ->join('solicitudes', 'validaciones.Id_Sol', '=', 'solicitudes.id')
        ->join('organizaciones', 'solicitudes.Id_Org', '=', 'organizaciones.id')
        ->join('localidades', 'solicitudes.Id_Loc', '=', 'localidades.id')
        ->join('regiones', 'solicitudes.Id_Reg', '=', 'regiones.id')
        ->join('proyectos', 'validaciones.Id_Pro', '=', 'proyectos.id')
        ->select(DB::raw("validaciones.Id, validaciones.Resp_Valid, solicitudes.Tipo_Convenio, validaciones.Tipo_Asignacion, validaciones.Fecha_Val_Inicio,COUNT(beneficiarios.Curp) as count_row,validaciones.Cant_Validado,validaciones.Ben_H_Validado,validaciones.Ben_M_Validado,solicitudes.Cant_Sol,solicitudes.Ben_H,solicitudes.Ben_M,solicitudes.Subrepresentante,  organizaciones.Nom_Org,localidades.Nom_Loc,validaciones.Comentario,proyectos.Nom_Pro, regiones.Nom_Reg"))
        ->groupBy(DB::raw("validaciones.id"))
        ->get();

/*
         return Validaciones::query()
            ->join('solicitudes', 'validaciones.Id_Sol', '=', 'solicitudes.id')->count()
            ->join('regiones', 'solicitudes.Id_Reg', '=', 'regiones.id')
            ->join('proyectos', 'validaciones.Id_Pro', '=', 'proyectos.id')
            ->join('beneficiarios','validaciones.id','=','beneficiarios.Id_Val')
            ->select(
                'validaciones.Id',
                'validaciones.Resp_Valid',
                'validaciones.Fecha_Val_Inicio',
                'validaciones.Cant_Validado',
                'validaciones.Ben_H_Validado',
                'validaciones.Ben_M_Validado',
                'solicitudes.Cant_Sol',
                'solicitudes.Ben_H',
                'solicitudes.Ben_M',
                'solicitudes.Subrepresentante',
                'solicitudes.Tipo_Convenio',
                'proyectos.Nom_Pro',
                'regiones.Nom_Reg')
            ->get();*/
    }
}
