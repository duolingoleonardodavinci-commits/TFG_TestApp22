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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id('id_modulo');
            $table->string('ciclo');
            $table->string('modulo');
            $table->string('color', 7);
            $table->string('idioma', 2);
            $table->string('clave_matriculacion');
            $table->foreignId('id_profesor')
                  ->constrained('profesores', 'id_profesor')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};
