<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstalacionItem extends Model
{
    protected $fillable = ['descripcion', 'orden', 'somos_itca_content_id'];

    public function somosItcaContent()
    {
        return $this->belongsTo(SomosItcaContent::class);
    }
}
