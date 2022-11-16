<?php

namespace App\Exports\Admin;

use App\Models\Admin\Beneficiarios;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBeneficiarios implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'Validador',
            'Fecha Validacion',
            'Representante',
            'Representante General',
            'Proyecto Asignado',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Sexo',
            'Clave Elector',
            'Curp',
            'Localidad',
            'Region',
            'Estatus',
            'Registrado Por',
            'Fecha de Registro'
        ];
    }

    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:q1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }

    public function collection()
    { 
        return Beneficiarios::query()
            ->join('solicitudes', 'beneficiarios.Id_Sol', '=', 'solicitudes.id')
            ->join('validaciones', 'beneficiarios.Id_Val', '=', 'validaciones.id')
            ->join('localidades', 'beneficiarios.Id_Loc', '=', 'localidades.id')
            ->join('proyectos', 'beneficiarios.Id_Pro', '=', 'proyectos.id')
            ->join('regiones', 'beneficiarios.Id_Reg', '=', 'regiones.id')
            ->join('users', 'beneficiarios.Id_Usuario', '=', 'users.id')
            ->select(
                'validaciones.Resp_Valid',
                'validaciones.Fecha_Val_Inicio',
                'solicitudes.Subrepresentante',
                'solicitudes.Tipo_Convenio',
                'proyectos.Nom_Pro',
                'beneficiarios.Nom_Ben',
                'beneficiarios.Pat_Ben',
                'beneficiarios.Mat_Ben',
                'beneficiarios.Sexo',
                'beneficiarios.Clave_El',
                'beneficiarios.Curp',
                'localidades.Nom_Loc',
                'regiones.Nom_Reg',
                'beneficiarios.Estatus',
                'users.name',
                'beneficiarios.created_at')
            ->get();
    }
}
