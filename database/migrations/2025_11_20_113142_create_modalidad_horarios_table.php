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
        Schema::create('modalidad_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modalidad_id')->constrained('modalidades')->onDelete('cascade');
            $table->string('nombre'); // Mañana, Tarde, Noche
            $table->string('hora_inicio'); // "9:00", "14:00", "19:00"
            $table->string('hora_fin'); // "12:30", "17:30", "22:30"
            $table->string('icono')->nullable(); // "/images/desktop/morning.png", etc.
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidad_horarios');
    }
};
