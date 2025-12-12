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
        
        // Verificar si la columna existe antes de modificarla
        if (Schema::hasColumn('cursadas', 'Dto_Cuota')) {
            // Cambiar Dto_Cuota de DECIMAL(10,2) a DECIMAL(5,2) para porcentajes
            try {
                \DB::statement('ALTER TABLE `cursadas` MODIFY `Dto_Cuota` DECIMAL(5,2) NULL');
            } catch (\Exception $e) {
                // Si falla al modificar, la columna puede no existir realmente
                // Intentar crearla
                if (Schema::hasColumn('cursadas', 'Cta_Web')) {
                    \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL AFTER `Cta_Web`');
                } else {
                    \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL');
                }
            }
        } else {
            // Si no existe, crearla directamente con el tipo correcto
            // Intentar después de Cta_Web si existe, sino al final
            try {
                if (Schema::hasColumn('cursadas', 'Cta_Web')) {
                    \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL AFTER `Cta_Web`');
                } else {
                    \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL');
                }
            } catch (\Exception $e) {
                // Si falla, intentar sin especificar posición
                \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL');
            }
        }
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
