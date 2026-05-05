<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    use HasFactory;

    protected $table = 'puntuaciones';

    protected $fillable = [
        'id_test',
        'id_alumno',
        'fecha',
        'puntuacion',
        'tipo'
    ];
}
