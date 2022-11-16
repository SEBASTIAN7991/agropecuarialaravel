<?php

namespace App\Exports\Admin;

use App\Models\Admin\Validaciones;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteValidaciones implements WithMultipleSheets
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];
            $sheets[] = new ValidacionesExport();
            $sheets[] = new ValidacionesExport2();
            $sheets[] = new ValGeneral();
            $sheets[] = new ValidacionesExport3();
        return $sheets;
    }
}
