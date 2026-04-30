<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';
    protected $primaryKey = 'id_etiqueta';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    public function preguntas() {
        return $this->belongsToMany(Pregunta::class, 'etiqueta_pregunta', 'id_etiqueta', 'id_pregunta');
    }
}