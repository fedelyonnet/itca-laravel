<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiosContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_image',
        'club_itca_video',
        'club_itca_texto',
        'club_itca_button_url',
        'bolsa_work_image',
        'bolsa_work_text',
        'bolsa_work_button_url',
        'tienda_text',
        'tienda_button_url',
        'competencia_itca_video',
        'competencia_itca_texto',
        'competencia_itca_button_url',
        // Charlas y Visitas Técnicas
        'charla1_img', 'charla1_title', 'charla1_text', 'charla1_fecha',
        'charla2_img', 'charla2_title', 'charla2_text', 'charla2_fecha',
        'charla3_img', 'charla3_title', 'charla3_text', 'charla3_fecha',
        'charla4_img', 'charla4_title', 'charla4_text', 'charla4_fecha',
        // Material Didáctico
        'manuales_img1', 'manuales_img2', 'manuales_texto', 'manuales_button_url',
    ];

    public function productos()
    {
        return $this->hasMany(BeneficioProducto::class)->orderBy('orden');
    }
}
