<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'tipo_titulo',
        'imagen_desktop',
        'imagen_mobile',
        'direccion',
        'telefono',
        'link_google_maps',
        'link_whatsapp',
        'disponible',
        'orden',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Scope para obtener solo sedes disponibles
     */
    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope para obtener sedes ordenadas por orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden', 'asc');
    }

    /**
     * Boot method para auto-asignar orden
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sede) {
            if (empty($sede->orden)) {
                $sede->orden = static::getNextOrden();
            }
        });
    }

    /**
     * Obtener el siguiente orden disponible
     */
    public static function getNextOrden()
    {
        $maxOrden = static::max('orden');
        return ($maxOrden ?? 0) + 1;
    }
}
