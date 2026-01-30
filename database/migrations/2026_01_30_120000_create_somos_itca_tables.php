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
        // 1. Tabla Principal
        Schema::create('somos_itca_contents', function (Blueprint $table) {
            $table->id();
            $table->string('video_url')->nullable(); // Para Dropdown 1
            $table->string('img_por_que')->nullable(); // Para Dropdown 2
            $table->timestamps();
        });

        // 2. Tabla Instalaciones (N imágenes)
        Schema::create('instalaciones', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Solo imagen
            // FK hacia la tabla principal (1 SomosItcaContent -> N Instalaciones)
            $table->foreignId('somos_itca_content_id')->constrained('somos_itca_contents')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Tabla Formadores (N formadores: foto y nombre)
        Schema::create('formadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('image_path'); // Foto
            // FK hacia la tabla principal
            $table->foreignId('somos_itca_content_id')->constrained('somos_itca_contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formadores');
        Schema::dropIfExists('instalaciones');
        Schema::dropIfExists('somos_itca_contents');
    }
};
