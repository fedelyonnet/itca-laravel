<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    protected $fillable = [
        'lead_id',
        'cursada_id',
        'token',
        'estado',
        'enviado_at',
    ];

    /**
     * Get the lead that owns the abandoned cart.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the cursada associated with the abandoned cart.
     */
    public function cursada()
    {
        return $this->belongsTo(Cursada::class, 'cursada_id');
    }
}
