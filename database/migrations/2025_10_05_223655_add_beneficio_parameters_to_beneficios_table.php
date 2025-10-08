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
        Schema::table('beneficios', function (Blueprint $table) {
            // Separación del título en dos líneas
            $table->string('titulo_linea1')->nullable();
            $table->string('titulo_linea2')->nullable();
            
            // Sistema de acciones
            $table->enum('tipo_accion', ['none', 'button', 'link'])->default('none');
            $table->string('texto_boton')->nullable();
            
            // Control de layout
            $table->enum('alineacion_bottom', ['left', 'right', 'center'])->default('right');
            $table->boolean('mostrar_bottom')->default(true);
            
            // Control de tipografía
            $table->enum('tipo_titulo', ['normal', 'small'])->default('normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropColumn([
                'titulo_linea1',
                'titulo_linea2', 
                'tipo_accion',
                'texto_boton',
                'alineacion_bottom',
                'mostrar_bottom',
                'tipo_titulo'
            ]);
        });
    }
};
