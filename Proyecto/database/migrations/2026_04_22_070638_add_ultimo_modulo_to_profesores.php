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
        Schema::table('profesores', function (Blueprint $table) {
            $table->foreignId('id_ultimo_modulo_visitado')
                  ->nullable()
                  ->constrained('modulos', 'id_modulo')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesores', function (Blueprint $table) {
            $table->dropForeign(['id_ultimo_modulo_visitado']);
            $table->dropColumn('id_ultimo_modulo_visitado');
        });
    }
};
