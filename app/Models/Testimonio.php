<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonio extends Model
{
    protected $fillable = [
        'visible',
        'texto',
        'avatar',
        'tiempo_testimonio',
        'sede',
        'nombre',
        'carrera',
        'icono',
        'orden'
    ];

    protected $casts = [
        'visible' => 'boolean',
        'tiempo_testimonio' => 'integer',
        'orden' => 'integer'
    ];

    public function scopeVisibles($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($testimonio) {
            if (empty($testimonio->orden)) {
                $testimonio->orden = static::getNextOrden();
            }
        });
    }

    public static function getNextOrden()
    {
        $maxOrden = static::max('orden');
        return $maxOrden ? $maxOrden + 1 : 1;
    }
}
