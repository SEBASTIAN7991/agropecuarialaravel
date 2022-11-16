<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fierro extends Model
{
    use HasFactory;
    public function localidades(){
        return $this->belongsTo('App\Models\Admin\Localidades','Id_Loc');
    }
    public function regiones(){
        return $this->belongsTo('App\Models\Admin\Regiones','Id_Reg');
    }
}
