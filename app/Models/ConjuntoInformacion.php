<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConjuntoInformacion extends Model
{
    use HasFactory;

    protected $table = 'conjuntos_informaciones';

    protected $fillable = [
        'valor_administracion', 'limite_pago', 'interes_mora',
        'numero_parqueaderos', 'horas_gratis', 'valor_hora_adicional',
        'conjunto_id'
    ];
}
