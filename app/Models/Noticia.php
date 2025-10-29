<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = [
        'orden',
        'visible',
        'titulo',
        'slug',
        'contenido',
        'fecha_publicacion',
        'imagen',
        'destacada'
    ];

    protected $casts = [
        'visible' => 'boolean',
        'destacada' => 'boolean',
        'fecha_publicacion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope para noticias visibles
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    // Scope para noticia destacada
    public function scopeDestacada($query)
    {
        return $query->where('destacada', true);
    }

    // Scope para ordenar por fecha de publicación
    public function scopeOrderByFechaPublicacion($query, $direction = 'desc')
    {
        return $query->orderBy('fecha_publicacion', $direction);
    }

    // Scope para ordenar por orden
    public function scopeOrderByOrden($query, $direction = 'asc')
    {
        return $query->orderBy('orden', $direction);
    }
}
