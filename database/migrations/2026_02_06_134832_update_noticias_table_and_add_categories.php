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
        // 1. Actualizar tabla noticias
        Schema::table('noticias', function (Blueprint $table) {
            $table->text('extracto')->nullable()->after('titulo');
            $table->string('autor_nombre')->nullable()->after('contenido');
            $table->string('autor_puesto')->nullable()->after('autor_nombre');
            $table->string('imagen_hero')->nullable()->after('fecha_publicacion');
            $table->string('imagen_thumb')->nullable()->after('imagen_hero');
            
            // Eliminar imagen genérica si existe
            if (Schema::hasColumn('noticias', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });

        // 2. Crear tabla categorias_noticias
        Schema::create('categorias_noticias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 3. Crear tabla pivot noticia_categoria
        Schema::create('noticia_categoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('noticia_id')->constrained('noticias')->onDelete('cascade');
            $table->foreignId('categoria_noticia_id')->constrained('categorias_noticias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noticia_categoria');
        Schema::dropIfExists('categorias_noticias');
        
        Schema::table('noticias', function (Blueprint $table) {
            if (!Schema::hasColumn('noticias', 'imagen')) {
                $table->string('imagen')->nullable();
            }
            $table->dropColumn(['extracto', 'autor_nombre', 'autor_puesto', 'imagen_hero', 'imagen_thumb']);
        });
    }
};
