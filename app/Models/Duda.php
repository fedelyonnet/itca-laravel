<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Duda extends Model
{
    protected $fillable = [
        'pregunta',
        'respuesta',
        'orden',
    ];

    /**
     * Scope para ordenar dudas por el campo orden
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

        static::creating(function ($duda) {
            if (empty($duda->orden)) {
                $duda->orden = static::getNextOrden();
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
