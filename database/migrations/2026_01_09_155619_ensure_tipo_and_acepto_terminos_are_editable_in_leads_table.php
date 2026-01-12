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
        Schema::table('leads', function (Blueprint $table) {
            // Modificar los campos para asegurar que sean editables
            // Esto fuerza a MySQL a recrear los campos sin restricciones
            $table->string('tipo')->nullable()->change();
            $table->boolean('acepto_terminos')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay nada que revertir, solo estamos asegurando que los campos sean editables
    }
};
