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
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->integer('orden')->default(0);
            $table->boolean('visible')->default(true);
            $table->string('titulo', 255);
            $table->string('slug', 255)->unique();
            $table->text('contenido');
            $table->timestamp('fecha_publicacion');
            $table->string('imagen', 255)->nullable();
            $table->boolean('destacada')->default(false);
            $table->timestamps();
            
            // Índices
            $table->index(['visible', 'fecha_publicacion']);
            $table->index('orden');
            $table->index('destacada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticias');
    }
};
