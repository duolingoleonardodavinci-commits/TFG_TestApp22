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
        'id_pregunta',
        'tipo',
        'contenido',
        'id_modulo',
        'etiquetas'
    ];

    protected $casts = [
        'contenido' => AsArrayObject::class,
        'etiquetas' => AsArrayObject::class,
    ];

    public function modulo() {
        return $this->belongsTo(Modulo::class, 'id_modulo', 'id_modulo');
    }

    public function listaEtiquetas() {
        return $this->belongsToMany(Etiqueta::class, 'etiqueta_pregunta', 'id_pregunta', 'id_etiqueta');
    }
}
