<?php

namespace App\Exports\Admin;

use App\Models\Admin\Solicitudes;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteSolicitudes implements WithMultipleSheets
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];
            $sheets[] = new SolicitudesExcel();
            $sheets[] = new SolicitudesCorte();
        return $sheets;
    }
}
