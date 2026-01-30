<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formador extends Model
{
    use HasFactory;

    protected $table = 'formadores';

    protected $fillable = [
        'somos_itca_content_id',
        'nombre',
        'image_path',
        // 'bio' eliminada
    ];

    public function content()
    {
        return $this->belongsTo(SomosItcaContent::class, 'somos_itca_content_id');
    }
}
