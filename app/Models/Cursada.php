<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cursada extends Model
{
    protected $table = 'cursadas';
    
    protected $fillable = [
        'id_curso',
        'nombre_curso',
        'vacantes',
        'sede',
        'x_modalidad',
        'dias',
        'x_turno',
        'matricula_base',
        'matricula_con_50_dcto',
        'cantidad_cuotas',
        'valor_cuota',
        'descr',
        'cod1',
        'cod2',
        'duracion',
        'fecha_inicio',
        'fecha_fin',
        'mes_inicio',
        'mes_fin',
        'horario',
        'hora_inicio',
        'hora_fin',
        'id_aula',
        'x_tipo',
        'x_nivel',
        'x_cod1',
    ];
    
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'hora_inicio' => 'string',
        'hora_fin' => 'string',
        'matricula_base' => 'integer',
        'matricula_con_50_dcto' => 'integer',
        'valor_cuota' => 'integer',
    ];
}
