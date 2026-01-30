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
        // 'descripcion' eliminada
        // 'orden', 'active' eliminadas del schema estricto solicitado ("solo imagenes"), 
        // pero suelo dejar active/orden. Si usuario dijo "solo imagenes", las quito para ser literal 
        // o asumo implícitos? Dijo: "instalaciones solo imagenes (n)".
        // Voy a dejar solo image_path y FK.
    ];

    public function content()
    {
        return $this->belongsTo(SomosItcaContent::class, 'somos_itca_content_id');
    }
}
