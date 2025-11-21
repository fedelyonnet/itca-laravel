<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadHorario extends Model
{
    protected $fillable = [
        'modalidad_id',
        'nombre',
        'hora_inicio',
        'hora_fin',
        'icono',
        'orden',
    ];

    /**
     * Relación con Modalidad
     */
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }

    /**
     * Scope para ordenar por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }
}
