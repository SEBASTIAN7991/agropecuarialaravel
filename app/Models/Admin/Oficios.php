<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficios extends Model
{
    use HasFactory;
    public function areas(){
        return $this->belongsTo('App\Models\Admin\areas','Id_Area');
    }
    public function personas(){
        return $this->belongsTo('App\Models\Admin\Personas','Enviado_Por');
    }
    protected $casts = [
        'created_at' => 'datetime:d/m/Y', // Change your format
        'updated_at' => 'datetime:d/m/Y',
    ];
}
