<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'correo',
        'telefono',
    ];

    /**
     * Obtener el historial de cursadas de este lead.
     */
    public function cursadas(): HasMany
    {
        return $this->hasMany(LeadCursada::class);
    }
}
