<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';
    protected $primaryKey = 'id_pregunta';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'contenido',
        'id_modulo'
    ];

    protected $casts = [
        'contenido' => AsArrayObject::class,
    ];

    public function modulo() {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }
}
