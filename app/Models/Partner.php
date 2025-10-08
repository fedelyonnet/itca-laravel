<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'url',
        'orden'
    ];

    /**
     * Scope para ordenar por el campo orden
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden');
    }
}
