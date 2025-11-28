<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CursoAnio extends Model
{
    protected $fillable = [
        'curso_id',
        'año',
        'titulo',
        'nivel',
        'orden',
    ];

    /**
     * Relación con Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación con Unidades
     */
    public function unidades()
    {
        // Ordenar por el número extraído del campo numero
        // Extrae el último número del string (ej: "Unidad 1" -> 1, "Unidad 10" -> 10)
        return $this->hasMany(AnioUnidad::class)->orderByRaw('CAST(SUBSTRING_INDEX(numero, " ", -1) AS UNSIGNED), numero');
    }

    /**
     * Scope para ordenar por año
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('año', 'asc');
    }
}
