<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadCursada extends Model
{
    protected $table = 'lead_cursadas';

    protected $fillable = [
        'lead_id',
        'cursada_id',
        'tipo',
        'acepto_terminos',
    ];

    protected $casts = [
        'acepto_terminos' => 'boolean',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
