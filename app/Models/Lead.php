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
        'cursada_id',
    ];

    /**
     * Relación con Cursada
     */
    public function cursada()
    {
        return $this->belongsTo(Cursada::class, 'cursada_id');
    }
}
