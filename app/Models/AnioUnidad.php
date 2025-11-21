<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnioUnidad extends Model
{
    protected $table = 'anio_unidades';
    
    protected $fillable = [
        'curso_anio_id',
        'numero',
        'titulo',
        'subtitulo',
        'orden',
    ];

    /**
     * Relación con CursoAnio
     */
    public function cursoAnio()
    {
        return $this->belongsTo(CursoAnio::class);
    }

    /**
     * Scope para ordenar por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }
}
