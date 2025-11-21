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
        Schema::create('modalidad_columnas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nombre'); // "Duración", "Dedicación", "Clases", "Teoría y Práctica", "Teoría", "Práctica", "Mes de Inicio"
            $table->string('icono'); // "/images/desktop/clock.png", "/images/desktop/gear.png", etc.
            $table->string('campo_dato'); // "duracion", "dedicacion", "clases_semana", "horas_presenciales", "horas_virtuales", "mes_inicio"
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidad_columnas');
    }
};
