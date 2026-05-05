<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Puntuacion extends Model
{
    protected $table = 'puntuaciones';
    protected $primaryKey = 'id_puntuacion';
    const CREATED_AT = 'fecha';

    const UPDATED_AT = null;

    protected $fillable = [
        'id_puntuacion',
        'id_test',
        'id_alumno',
        'fecha',
        'puntuacion',
        'tipo'
    ];

    public function test() {
        return $this->belongsTo(Test::class, 'id_test', 'id_test');
    }

    public function alumno() {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }
}
