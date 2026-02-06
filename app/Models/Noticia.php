<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $table = 'noticias';

    protected $fillable = [
        'orden',
        'visible',
        'titulo',
        'slug',
        'extracto',
        'contenido',
        'autor_nombre',
        'autor_puesto',
        'fecha_publicacion',
        'imagen_hero',
        'imagen_thumb',
        'banner_publicitario',
        'destacada'
    ];

    protected $casts = [
        'visible' => 'boolean',
        'destacada' => 'boolean',
        'fecha_publicacion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con Categorías
     */
    public function categorias()
    {
        return $this->belongsToMany(CategoriaNoticia::class, 'noticia_categoria', 'noticia_id', 'categoria_noticia_id');
    }

    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeDestacada($query)
    {
        return $query->where('destacada', true);
    }

    public function scopeOrderByFechaPublicacion($query, $direction = 'desc')
    {
        return $query->orderBy('fecha_publicacion', $direction);
    }

    public function scopeOrderByOrden($query, $direction = 'asc')
    {
        return $query->orderBy('orden', $direction);
    }
}
