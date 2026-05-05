<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model {

    protected $table = 'alumnos';
    protected $primaryKey = 'id_alumno';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
    ];

    // Relaciones
    public function usuario() {
        return $this->belongsTo(Usuario::class,'id_alumno', 'id_usuario');
    }

    public function modulos() {
        return $this->belongsToMany(Modulo::class, 'modulos_alumnos', 'id_alumno', 'id_modulo');
    }

    public function puntuaciones() {
        return $this->hasMany(Puntuacion::class, 'id_alumno');
    }
}
