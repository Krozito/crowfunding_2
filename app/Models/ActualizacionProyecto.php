<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualizacionProyecto extends Model
{
    use HasFactory;

    protected $table = 'actualizaciones_proyecto';

    protected $fillable = [
        'proyecto_id',
        'titulo',
        'contenido',
        'fecha_publicacion',
        'es_hito',
        'adjuntos',
    ];

    protected $casts = [
        'fecha_publicacion' => 'datetime',
        'es_hito' => 'boolean',
        'adjuntos' => 'array',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
