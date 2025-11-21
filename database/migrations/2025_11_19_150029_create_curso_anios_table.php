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
        Schema::create('curso_anios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->integer('año');
            $table->string('titulo');
            $table->string('nivel');
            $table->integer('orden')->default(0);
            $table->timestamps();
            
            // Evitar duplicados del mismo año para un curso
            $table->unique(['curso_id', 'año']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_anios');
    }
};
