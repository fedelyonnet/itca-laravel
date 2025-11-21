<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTestimonioCarreraIndividual extends Model
{
    protected $table = 'video_testimonio_carrera_individual';

    protected $fillable = [
        'video',
        'url',
    ];
}
