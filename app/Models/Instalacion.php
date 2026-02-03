<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instalacion extends Model
{
    use HasFactory;

    protected $table = 'instalaciones';

    protected $fillable = [
        'somos_itca_content_id',
        'image_path',
        'descripcion',
        'orden'
    ];

    public function content()
    {
        return $this->belongsTo(SomosItcaContent::class, 'somos_itca_content_id');
    }
}
