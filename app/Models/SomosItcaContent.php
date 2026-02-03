<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SomosItcaContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_image',
        'title_line_1',
        'title_line_2',
        'title_line_3',
        'video_url',
        'img_por_que',
        'que_es_itca',
        'formadores_texto',
        'm1_number', 'm1_title', 'm1_text',
        'm2_number', 'm2_title', 'm2_text',
        'm3_number', 'm3_title', 'm3_text',
        'm4_number', 'm4_title', 'm4_text',
        'cat1_img', 'cat1_title', 'cat1_text',
        'cat2_img', 'cat2_title', 'cat2_text',
        'cat3_img', 'cat3_title', 'cat3_text',
        'cat4_img', 'cat4_title', 'cat4_text',
    ];

    public function instalaciones()
    {
        return $this->hasMany(Instalacion::class);
    }

    public function formadores()
    {
        return $this->hasMany(Formador::class);
    }

    public function porQueItems()
    {
        return $this->hasMany(PorQueItem::class);
    }
    
    public function instalacionItems()
    {
        return $this->hasMany(InstalacionItem::class);
    }
}
