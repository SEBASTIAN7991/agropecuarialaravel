<?php

namespace App\Exports\Admin;

use App\Models\Admin\Comisiones;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ComisionesExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'id',
            'Comisionado',
            'Cargo',
            'Comentario',
            'Destino',
            'Fecha de Comision',
            'Tipo Transporte',
            'Placas',
            'Viaticable',
            'Dias'

        ];
    }

    public function styles(Worksheet $sheet)
    {
    $sheet->getStyle('A1:J1')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],]);
    }

    public function collection()
    { 
        return Comisiones::query()
            ->join('personas', 'Comisiones.Id_Comisionado', '=', 'personas.id')
            ->select(
                'comisiones.id',
                'personas.Nombre',
                'personas.Cargo',
                'comisiones.Comentario',
                'comisiones.Loc_Destino',
                'comisiones.Fecha_Comision',
                'comisiones.Tipo_Trans',
                'comisiones.Placas',
                'comisiones.Estatus',
                'comisiones.Dias')
            ->get();
    }
}
