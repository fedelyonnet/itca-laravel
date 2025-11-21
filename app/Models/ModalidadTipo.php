<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadTipo extends Model
{
    protected $fillable = [
        'modalidad_id',
        'nombre',
        'duracion',
        'dedicacion',
        'clases_semana',
        'horas_teoria',
        'horas_practica',
        'horas_virtuales',
        'horas_presenciales',
        'mes_inicio',
        'orden',
        'activo',
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
