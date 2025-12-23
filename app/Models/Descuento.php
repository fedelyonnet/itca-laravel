<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $table = 'descuentos';
    
    protected $fillable = [
        'Codigo_Promocion',
        'Promocion_Descripcion',
        'Porcentaje',
        'codigo_web',
    ];
    
    protected $casts = [
        'Porcentaje' => 'decimal:2',
    ];
}
