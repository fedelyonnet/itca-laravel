<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaNoticia extends Model
{
    protected $table = 'categorias_noticias';

    protected $fillable = [
        'nombre',
        'slug'
    ];

    /**
     * Relación con Noticias
     */
    public function noticias()
    {
        return $this->belongsToMany(Noticia::class, 'noticia_categoria', 'categoria_noticia_id', 'noticia_id');
    }
}
