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
        Schema::create('modalidad_tipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nombre'); // REGULAR, INTENSIVO, DESFASADO
            $table->string('duracion'); // "10 meses", "5 meses", "9 meses"
            $table->string('dedicacion'); // "3hs y media cada clase", "7hs cada clase"
            $table->string('clases_semana'); // "1 x semana"
            $table->string('horas_teoria')->nullable(); // Para semipresencial: "70hs virtuales"
            $table->string('horas_practica')->nullable(); // Para semipresencial: "110hs presenciales"
            $table->string('horas_virtuales')->nullable(); // Para semipresencial
            $table->string('horas_presenciales')->nullable(); // "110hs presenciales"
            $table->string('mes_inicio'); // "Marzo", "Agosto", "Mayo"
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidad_tipos');
    }
};
