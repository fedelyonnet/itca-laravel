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
        Schema::create('dato_contactos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion'); // Ej: "Tel:", "Instagram"
            $table->text('contenido'); // Ej: "0810-...", URL
            $table->string('tipo')->default('info'); // 'info' o 'social'
            $table->string('icono')->nullable(); // Ruta del icono para redes sociales
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dato_contactos');
    }
};
