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
        return $this->hasMany(AnioUnidad::class)->orderBy('orden');
    }

    /**
     * Scope para ordenar por año
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('año', 'asc');
    }
}
