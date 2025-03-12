<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uniforme extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria',
        'tipo',
        'foto_path' // si decides mantener el campo individual para compatibilidad
    ];

    // Relación de uno a muchos con UniformeFoto
    public function fotos()
    {
        return $this->hasMany(UniformeFoto::class);
    }
}
