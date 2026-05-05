<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    protected $table = 'puntuaciones';
    public $timestamps = false;

    protected $fillable = [
        'id_test',
        'id_alumno',
        'fecha',
        'puntuacion',
        'tipo'
    ];
}
