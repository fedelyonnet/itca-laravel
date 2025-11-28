<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificadoCarrera extends Model
{
    protected $table = 'certificado_carreras';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificado_1',
        'certificado_2',
    ];
}
