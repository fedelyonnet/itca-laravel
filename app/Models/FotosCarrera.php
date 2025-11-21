<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotosCarrera extends Model
{
    protected $table = 'fotos_carrera';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'imagen',
        'descripcion',
        'orden',
    ];

    /**
     * Scope para obtener fotos ordenadas por orden
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

        static::creating(function ($foto) {
            if (empty($foto->orden)) {
                $foto->orden = static::getNextOrden();
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
