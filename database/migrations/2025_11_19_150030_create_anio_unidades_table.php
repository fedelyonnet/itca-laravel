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
        Schema::create('anio_unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_anio_id')->constrained('curso_anios')->onDelete('cascade');
            $table->string('numero');
            $table->string('titulo');
            $table->text('subtitulo');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anio_unidades');
    }
};
