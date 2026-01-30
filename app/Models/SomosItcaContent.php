<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SomosItcaContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_url',
        'img_por_que',
    ];

    public function instalaciones()
    {
        return $this->hasMany(Instalacion::class);
    }

    public function formadores()
    {
        return $this->hasMany(Formador::class);
    }
}
