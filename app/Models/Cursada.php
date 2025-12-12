<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursada extends Model
{
    protected $table = 'cursadas';
    
    protected $fillable = [
        'ID_Curso',
        'carrera',
        'Cod1',
        'Fecha_Inicio',
        'xDias',
        'xModalidad',
        'Régimen',
        'xTurno',
        'Horario',
        'Vacantes',
        'Matric_Base',
        'Cta_Web',
        'Dto_Cuota',
        'Sin_IVA',
        'sede',
        'casilla_Promo',
    ];
    
    protected $casts = [
        'Fecha_Inicio' => 'date',
        'Matric_Base' => 'decimal:2',
        'Cta_Web' => 'decimal:2',
        'Dto_Cuota' => 'decimal:2', // Porcentaje
        'Sin_IVA' => 'decimal:2',
        'Vacantes' => 'integer',
        'casilla_Promo' => 'boolean',
    ];
}
