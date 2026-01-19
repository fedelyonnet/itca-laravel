<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones'; // Importante porque Laravel busca 'inscripcions' por defecto

    protected $fillable = [
        'lead_id',
        'cursada_id',
        'estado',
        'monto_matricula',
        'preference_id',
        'collection_id',
        'payment_type',
        'merchant_order_id',
        'descuento_id',
        'codigo_descuento',
        'monto_descuento',
        'monto_sin_iva'
    ];

    // Relación con el alumno/lead
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // Relación con la cursada
    public function cursada()
    {
        return $this->belongsTo(Cursada::class, 'cursada_id', 'ID_Curso');
    }
}
