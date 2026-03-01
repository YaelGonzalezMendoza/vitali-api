<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordatorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descripcion',
        'fecha_hora',
        'activo',
    ];

    // Relación: Recordatorio pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}