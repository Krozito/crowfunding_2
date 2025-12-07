<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aportacion extends Model
{
    use HasFactory;

    protected $table = 'aportaciones';

    protected $fillable = [
        'colaborador_id',
        'proyecto_id',
        'monto',
        'fecha_aportacion',
        'estado_pago',
        'id_transaccion_pago',
    ];

    protected $casts = [
        'fecha_aportacion' => 'datetime',
    ];

    // Colaborador que hizo la aportación
    public function colaborador()
    {
        return $this->belongsTo(User::class, 'colaborador_id');
    }

    // Proyecto al que se aportó
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
