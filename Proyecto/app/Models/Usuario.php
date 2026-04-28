<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User;

class Usuario extends User {
    
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'rol'
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relaciones
    public function profesor() {
        return $this->hasOne(Profesor::class, 'id_profesor', 'id_usuario');
    }

    public function alumno() {
        return $this->hasOne(Alumno::class, 'id_alumno', 'id_usuario');
    }

    // Comprobar si es profesor
    public function esProfesor(): bool {
        return $this->rol === 'profesor';
    }

    // Comprobar si es alumno
    public function esAlumno(): bool {
        return $this->rol === 'alumno';
    }
}