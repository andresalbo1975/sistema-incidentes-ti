<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    // Esto le dice a Laravel qué campos puede llenar desde el formulario
    protected $fillable = [
        'ticket_nro',
        'descripcion',
        'es_incidente_mayor',
        'estado',
    ];
}