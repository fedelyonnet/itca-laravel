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
        'Sin_iva_Mat', // Nueva columna del Excel
        'Cta_Web',
        'Sin_IVA_cta', // Nueva columna del Excel
        'Dto_Cuota',
        'cuotas',
        'sede',
        'Promo_Mat_logo',
        'ver_curso', // Nueva columna del Excel
    ];
    
    protected $casts = [
        'Fecha_Inicio' => 'date',
        'Matric_Base' => 'decimal:2',
        'Sin_iva_Mat' => 'decimal:2',
        'Cta_Web' => 'decimal:2',
        'Sin_IVA_cta' => 'decimal:2',
        'Dto_Cuota' => 'decimal:2', // Porcentaje
        'Vacantes' => 'integer',
        'cuotas' => 'integer', // Asegurar que cuotas sea un entero
    ];
}
