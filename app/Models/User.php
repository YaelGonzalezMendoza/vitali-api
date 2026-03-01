<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo_electronico',
        'contrasenia',
        'curp',
        'fecha_nacimiento',
        'id_rol',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    public function recordatorios()
{
    return $this->hasMany(Recordatorio::class);
}
}