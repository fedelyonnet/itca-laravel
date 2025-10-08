<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'modalidad_online',
        'modalidad_presencial',
        'fecha_inicio',
        'featured',
        'ilustracion_desktop',
        'ilustracion_mobile',
        'orden'
    ];

    protected $casts = [
        'modalidad_online' => 'boolean',
        'modalidad_presencial' => 'boolean',
        'fecha_inicio' => 'date',
        'featured' => 'boolean'
    ];

    /**
     * Scope para ordenar por la columna orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }
}