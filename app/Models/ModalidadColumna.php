<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadColumna extends Model
{
    protected $table = 'modalidad_columnas';

    protected $fillable = [
        'modalidad_id',
        'nombre',
        'icono',
        'campo_dato',
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
