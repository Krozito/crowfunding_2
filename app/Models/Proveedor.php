<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'creador_id',
        'proyecto_id',
        'nombre_proveedor',
        'info_contacto',
        'especialidad',
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function historiales()
    {
        return $this->hasMany(ProveedorHistorial::class);
    }
}
