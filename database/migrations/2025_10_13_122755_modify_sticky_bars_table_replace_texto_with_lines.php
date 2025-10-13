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
        Schema::table('sticky_bars', function (Blueprint $table) {
            // Eliminar el campo texto
            $table->dropColumn('texto');
            
            // Agregar los nuevos campos
            $table->string('linea_1')->nullable();
            $table->string('linea_2')->nullable();
            $table->string('url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sticky_bars', function (Blueprint $table) {
            // Eliminar los nuevos campos
            $table->dropColumn(['linea_1', 'linea_2', 'url']);
            
            // Restaurar el campo texto
            $table->text('texto');
        });
    }
};
