<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficioProducto extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficios_content_id',
        'image_path',
        'orden'
    ];

    public function content()
    {
        return $this->belongsTo(BeneficiosContent::class, 'beneficios_content_id');
    }
}
