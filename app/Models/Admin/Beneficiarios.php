<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiarios extends Model
{
    use HasFactory;
    public function solicitudes(){
        return $this->belongsTo('App\Models\Admin\Solicitudes','Id_Sol');
    }
    public function localidades(){
        return $this->belongsTo('App\Models\Admin\Localidades','Id_Loc');
    }
    public function proyectos(){
        return $this->belongsTo('App\Models\Admin\Proyectos','Id_Pro');
    }
    public function validaciones(){
        return $this->belongsTo('App\Models\Admin\Validaciones','Id_Val');
    }
    public function regiones(){
        return $this->belongsTo('App\Models\Admin\Regiones','Id_Reg');
    }
    public function users(){
        return $this->belongsTo('App\Models\User','Id_Usuario');
    }
    protected $casts = [
        'created_at' => 'datetime:d/m/Y', // Change your format
        'updated_at' => 'datetime:d/m/Y',
    ];
}
