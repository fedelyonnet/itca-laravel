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
        Schema::dropIfExists('modalidad_columnas');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('modalidad_columnas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nombre');
            $table->string('icono');
            $table->string('campo_dato');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }
};
