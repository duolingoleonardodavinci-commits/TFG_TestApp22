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
        Schema::create('preguntas_tests', function (Blueprint $table) {
            $table->foreignId('id_pregunta')->constrained('preguntas', 'id_pregunta')->cascadeOnDelete();
            $table->foreignId('id_test')->constrained('tests', 'id_test')->cascadeOnDelete();
            $table->primary(['id_pregunta', 'id_test']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas_tests');
    }
};
