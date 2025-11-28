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
        Schema::dropIfExists('modalidad_horarios');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('modalidad_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nombre');
            $table->string('hora_inicio');
            $table->string('hora_fin');
            $table->string('icono')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }
};
