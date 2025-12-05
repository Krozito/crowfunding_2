<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    use HasFactory;

    protected $table = 'recompensas';

    protected $fillable = [
        'proyecto_id',
        'titulo',
        'descripcion',
        'monto_minimo_aportacion',
        'disponibilidad',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
