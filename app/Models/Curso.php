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

    /**
     * Relación muchos a muchos con Sedes
     */
    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 'curso_sede', 'curso_id', 'sede_id')
                    ->withTimestamps();
    }

    /**
     * Relación con Años del programa
     */
    public function anios()
    {
        return $this->hasMany(CursoAnio::class)->orderBy('orden');
    }

    /**
     * Relación con Modalidades
     */
    public function modalidades()
    {
        return $this->hasMany(Modalidad::class)->orderBy('orden');
    }
}