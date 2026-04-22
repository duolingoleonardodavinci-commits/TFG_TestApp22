<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profesor extends Model {

    protected $table = 'profesores';
    protected $primaryKey = 'id_profesor';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_profesor',
        'id_ultimo_modulo_visitado',
    ];

    // Relaciones

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_profesor', 'id_usuario');
    }

    public function modulos() {
        return $this->hasMany(Modulo::class, 'id_profesor');
    }
}
