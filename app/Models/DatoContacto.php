<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatoContacto extends Model
{
    protected $fillable = [
        'descripcion',
        'contenido',
        'tipo',
        'icono',
        'orden'
    ];

    /**
     * Scope a query to only include info contacts.
     */
    public function scopeInfo($query)
    {
        return $query->where('tipo', 'info')->orderBy('orden');
    }

    /**
     * Scope a query to only include social media contacts.
     */
    public function scopeSocial($query)
    {
        return $query->where('tipo', 'social')->orderBy('orden');
    }
}
