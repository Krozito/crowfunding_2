<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectoCategoria extends Model
{
    protected $table = 'proyecto_categorias';

    protected $fillable = [
        'nombre',
    ];
}
