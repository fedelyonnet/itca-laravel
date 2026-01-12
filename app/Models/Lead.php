<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'correo',
        'telefono',
        'cursada_id', // Ahora almacena ID_Curso (string) en lugar del id numérico
        'tipo',
        'acepto_terminos',
    ];
}
