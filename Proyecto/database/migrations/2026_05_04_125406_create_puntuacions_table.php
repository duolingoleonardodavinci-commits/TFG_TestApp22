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
        Schema::create('puntuaciones', function (Blueprint $table) {
            $table->id('id_puntuacion'); 
            
            $table->foreignId('id_test')->constrained('tests', 'id_test')->cascadeOnDelete(); 
            $table->foreignId('id_alumno')->constrained('alumnos', 'id_alumno')->cascadeOnDelete();
            
            $table->timestamp('fecha')->nullable();
            
            $table->decimal('puntuacion', 5, 2)->nullable();
            $table->string('tipo')->default('examen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntuaciones');
    }
};
