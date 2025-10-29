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
        Schema::table('noticias', function (Blueprint $table) {
            // Eliminar el índice único incorrecto
            $table->dropUnique(['destacada']);
            // Agregar índice normal
            $table->index('destacada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('noticias', function (Blueprint $table) {
            // Revertir: eliminar índice normal y agregar único
            $table->dropIndex(['destacada']);
            $table->unique('destacada');
        });
    }
};