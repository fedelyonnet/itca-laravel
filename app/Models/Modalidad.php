<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidades';

    protected $fillable = [
        'curso_id',
        'nombre',
        'nombre_linea1',
        'nombre_linea2',
        'texto_info',
        'columnas_visibles',
        'horarios_visibles',
        'orden',
        'activo',
    ];

    protected $casts = [
        'columnas_visibles' => 'array',
        'horarios_visibles' => 'array',
        'activo' => 'boolean',
    ];

    /**
     * Relación con Curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación con Tipos
     */
    public function tipos()
    {
        return $this->hasMany(ModalidadTipo::class)->orderBy('orden');
    }



    /**
     * Scope para ordenar por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }

    /**
     * Scope para obtener solo modalidades activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }
}
