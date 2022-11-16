<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validaciones extends Model
{
    use HasFactory;
    public function solicitudes(){
        return $this->belongsTo('App\Models\Admin\Solicitudes','Id_Sol');
    }
    public function cargos(){
        return $this->belongsTo('App\Models\Admin\Cargos','Id_Cargo');
    }
    public function localidades(){
        return $this->belongsTo('App\Models\Admin\Localidades','Id_Loc');
    }
    public function organizaciones(){
        return $this->belongsTo('App\Models\Admin\Organizaciones','Id_Org');
    }
    public function proyectos(){
        return $this->belongsTo('App\Models\Admin\Proyectos','Id_Pro');
    }
    protected $casts = [
        'created_at' => 'datetime:d/m/Y', // Change your format
        'updated_at' => 'datetime:d/m/Y',
    ];
}
