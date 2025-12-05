<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDesembolso extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_desembolso';

    protected $fillable = [
        'proyecto_id',
        'monto_solicitado',
        'hito',
        'descripcion',
        'proveedores',
        'fecha_estimada',
        'estado',
        'adjuntos',
    ];

    protected $casts = [
        'proveedores' => 'array',
        'adjuntos' => 'array',
        'fecha_estimada' => 'date',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
