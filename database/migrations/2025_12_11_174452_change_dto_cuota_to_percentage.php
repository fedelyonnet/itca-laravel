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
        // Verificar si la tabla existe
        if (!Schema::hasTable('cursadas')) {
            return;
        }
        
        // Solo modificar si la columna existe y es DECIMAL(10,2)
        if (Schema::hasColumn('cursadas', 'Dto_Cuota')) {
            // Verificar el tipo actual de la columna
            $columnInfo = \DB::select("SHOW COLUMNS FROM `cursadas` WHERE Field = 'Dto_Cuota'");
            if (!empty($columnInfo) && strpos($columnInfo[0]->Type, 'decimal(10,2)') !== false) {
                // Solo modificar si es DECIMAL(10,2), si ya es DECIMAL(5,2) no hacer nada
                try {
                    \DB::statement('ALTER TABLE `cursadas` MODIFY `Dto_Cuota` DECIMAL(5,2) NULL');
                } catch (\Exception $e) {
                    // Si falla, ignorar (puede que ya esté en el formato correcto)
                }
            }
        }
        // Si no existe, la migración inicial la creará con el tipo correcto
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a DECIMAL(10,2)
        \DB::statement('ALTER TABLE `cursadas` MODIFY `Dto_Cuota` DECIMAL(10,2) NULL');
    }
};
