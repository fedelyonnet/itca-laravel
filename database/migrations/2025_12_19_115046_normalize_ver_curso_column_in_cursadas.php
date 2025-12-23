<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Normaliza la columna ver_curso para que pueda usar índices eficientemente
     */
    public function up(): void
    {
        // Normalizar todos los valores existentes: trim + lowercase
        DB::table('cursadas')
            ->whereNotNull('ver_curso')
            ->update([
                'ver_curso' => DB::raw("LOWER(TRIM(ver_curso))")
            ]);
    }

    /**
     * Reverse the migrations.
     * No podemos revertir la normalización, pero no es crítico
     */
    public function down(): void
    {
        // No hay forma de revertir la normalización sin datos originales
        // Esto es aceptable ya que la normalización mejora el rendimiento
    }
};
