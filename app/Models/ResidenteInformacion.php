<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidenteInformacion extends Model
{
    use HasFactory;

    protected $table = 'residentes_informaciones';

    protected $fillable = [
        'bloque', 'apartamento', 'nombre_propietario',
        'telefono', 'numero_casa', 'residente_id'
    ];
}
