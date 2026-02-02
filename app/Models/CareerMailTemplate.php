<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareerMailTemplate extends Model
{
    protected $fillable = [
        'curso_id',
        'header_image',
        'main_illustration',
        'certificate_image',
        'benefit_1_image',
        'benefit_1_url',
        'benefit_2_image',
        'benefit_2_url',
        'benefit_3_image',
        'benefit_3_url',
        'benefit_4_image',
        'benefit_4_url',
        'utn_banner_image',
        'partners_image',
        'illustration_2',
        'illustration_3',
        'illustration_4',
        'illustration_5',
        'bottom_image',
        'syllabus_year_1',
        'syllabus_year_2',
        'syllabus_year_3',
    ];

    /**
     * Get the course that owns the mail template.
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
