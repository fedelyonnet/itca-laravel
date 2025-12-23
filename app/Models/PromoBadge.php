<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoBadge extends Model
{
    protected $fillable = [
        'archivo',
    ];
    
    /**
     * Obtener el badge activo (solo puede haber uno)
     */
    public static function getActive()
    {
        return self::first();
    }
    
    /**
     * Obtener la ruta completa de la imagen
     */
    public function getImagePathAttribute()
    {
        if ($this->archivo) {
            return asset('images/badges-promo/' . $this->archivo);
        }
        return null;
    }
}
