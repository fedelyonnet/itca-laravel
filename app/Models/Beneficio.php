<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    protected $fillable = [
        // Campos originales
        'descripcion', 
        'link',
        'url',
        'imagen_desktop',
        'imagen_mobile',
        
        // Nuevos campos parametrizables
        'orden',                // Orden de visualización
        'titulo_linea1',        // Primera línea del título
        'titulo_linea2',        // Segunda línea del título
        'tipo_accion',          // Tipo de acción: none, button, link
        'texto_boton',          // Texto del enlace (solo para tipo 'link')
        'mostrar_bottom',       // Mostrar elementos bottom
        'tipo_titulo'           // Tamaño de la línea 2: normal, small
    ];

    /**
     * Obtiene la alineación automática según el tipo de acción
     */
    public function getAlineacionBottomAttribute(): string
    {
        return match($this->tipo_accion) {
            'button' => 'right',
            'link' => 'left',
            'none' => 'center',
            default => 'center'
        };
    }

    /**
     * Scope para ordenar beneficios por el campo orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }

    /**
     * Boot del modelo para auto-asignar orden
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($beneficio) {
            if (empty($beneficio->orden)) {
                $beneficio->orden = static::getNextOrden();
            }
        });
    }

    /**
     * Obtiene el siguiente número de orden disponible
     */
    public static function getNextOrden()
    {
        $maxOrden = static::max('orden');
        return ($maxOrden ?? 0) + 1;
    }
}
