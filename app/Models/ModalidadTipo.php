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
        'teoria_practica',
        'horas_teoria',
        'horas_practica',
        'horas_virtuales',
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
