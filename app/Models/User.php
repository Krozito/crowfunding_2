<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Proyecto;
use App\Models\Aportacion;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    protected $fillable = [
        'name',
        'nombre_completo',
        'email',
        'password',
        'estado_verificacion',
        'info_personal',
        'redes_sociales',
        'indice_confianza',
    ];

    /**
     * Campos ocultos
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'estado_verificacion' => 'boolean',
            'redes_sociales'      => 'array',
            'indice_confianza'    => 'integer',
        ];
    }

    /**
     * Relación muchos-a-muchos con roles (pivot: usuario_rol)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'usuario_rol', 'user_id', 'rol_id');
    }

    public function proyectosCreados()
    {
        return $this->hasMany(Proyecto::class, 'creador_id');
    }

    public function aportaciones()
    {
        return $this->hasMany(Aportacion::class, 'colaborador_id');
    }

    /**
     * Helper para chequear rol
     */
    public function hasRole(string $nombre): bool
    {
        // carga perezosa si no está cargado
        $this->loadMissing('roles');

        return $this->roles->contains(
            fn ($r) => strcasecmp($r->nombre_rol, $nombre) === 0
        );
    }
}
