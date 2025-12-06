<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoModeloFinanciamiento extends Model
{
    protected $table = 'proyecto_modelos_financiamiento';

    protected $fillable = [
        'nombre',
    ];
}
