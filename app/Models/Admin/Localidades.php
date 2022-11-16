<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidades extends Model
{
    use HasFactory;
    public function regiones(){
        return $this->belongsTo('App\Models\Admin\Regiones','Id_Reg');
    }
}
