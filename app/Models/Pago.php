<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'solicitud_id',
        'proveedor_id',
        'monto',
        'fecha_pago',
        'concepto',
        'adjuntos',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'adjuntos' => 'array',
    ];

    public function solicitud()
    {
        return $this->belongsTo(SolicitudDesembolso::class, 'solicitud_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
