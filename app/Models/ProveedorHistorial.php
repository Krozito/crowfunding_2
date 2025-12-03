<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorHistorial extends Model
{
    use HasFactory;

    protected $table = 'proveedor_historiales';

    protected $fillable = [
        'proveedor_id',
        'concepto',
        'monto',
        'fecha_entrega',
        'calificacion',
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
}
