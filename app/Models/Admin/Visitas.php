<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitas extends Model
{
    use HasFactory;
     public function cargos(){
        return $this->belongsTo('App\Models\Admin\Cargos','Id_Cargo');
    }
    public function localidades(){
        return $this->belongsTo('App\Models\Admin\Localidades','Id_Loc');
    }
    public function regiones(){
        return $this->belongsTo('App\Models\Admin\Regiones','Id_Reg');
    }
    protected $casts = [
        'created_at' => 'datetime:d/m/Y', // Change your format
        'updated_at' => 'datetime:d/m/Y',
    ];
}
