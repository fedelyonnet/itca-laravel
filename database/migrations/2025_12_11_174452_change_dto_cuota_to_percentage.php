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
                // Si falla al modificar, ignorar el error
                \Log::warning('No se pudo modificar Dto_Cuota: ' . $e->getMessage());
            }
        } else {
            // Si no existe, crearla directamente con el tipo correcto
            // NO usar AFTER si Cta_Web no existe
            try {
                \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `Dto_Cuota` DECIMAL(5,2) NULL');
            } catch (\Exception $e) {
                // Si falla, la columna puede ya existir con otro nombre o hay otro problema
                \Log::warning('No se pudo crear Dto_Cuota: ' . $e->getMessage());
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
