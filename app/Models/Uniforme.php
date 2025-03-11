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
        'foto_path', // Solo foto_path
        'tipo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}