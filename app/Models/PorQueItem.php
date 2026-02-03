<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PorQueItem extends Model
{
    protected $fillable = ['descripcion', 'somos_itca_content_id', 'orden'];

    public function somosItcaContent()
    {
        return $this->belongsTo(SomosItcaContent::class);
    }
    //
}
