<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('modulos_alumnos', function (Blueprint $table) {
            $table->foreignId('id_modulo')->constrained('modulos', 'id_modulo')->cascadeOnDelete();
            $table->foreignId('id_alumno')->constrained('alumnos', 'id_alumno')->cascadeOnDelete();
            $table->primary(['id_modulo', 'id_alumno']);

            $table->boolean('tiene_acceso')->default('1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos_alumnos');
    }
};
