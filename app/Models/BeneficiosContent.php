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
        'bolsa_work_button_url'
    ];
}
