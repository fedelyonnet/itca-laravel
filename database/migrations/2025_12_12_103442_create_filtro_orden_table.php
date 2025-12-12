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
        Schema::create('filtro_orden', function (Blueprint $table) {
            $table->id();
            $table->string('categoria'); // carrera, sede, modalidad, turno, dia
            $table->string('valor'); // El valor del filtro
            $table->integer('orden')->default(0); // Orden de visualización
            $table->timestamps();
            
            $table->unique(['categoria', 'valor']);
            $table->index(['categoria', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filtro_orden');
    }
};
