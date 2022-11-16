<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comisiones extends Model
{
    use HasFactory;
    public function personas(){
        return $this->belongsTo('App\Models\Admin\Personas','Id_Comisionado');
    }
}
