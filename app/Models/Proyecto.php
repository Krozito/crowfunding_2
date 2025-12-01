<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $table = 'proyectos';

    protected $fillable = [
        'creador_id',
        'titulo',
        'descripcion_proyecto',
        'meta_financiacion',
        'monto_recaudado',
        'fecha_limite',
        'cronograma',
        'presupuesto',
        'estado',
        'modelo_financiamiento',
        'categoria',
        'ubicacion_geografica',
        'imagen_portada',
    ];

    protected $casts = [
        'fecha_limite' => 'datetime',
        'cronograma' => 'array',
        'presupuesto' => 'array',
    ];

    public function creador()
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function aportaciones()
    {
        return $this->hasMany(Aportacion::class, 'proyecto_id');
    }
}
