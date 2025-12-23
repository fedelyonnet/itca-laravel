<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            // Índices para columnas de filtrado más usadas
            // Usar índices simples para columnas que se filtran directamente
            if (!$this->indexExists('cursadas', 'idx_ver_curso')) {
                $table->index('ver_curso', 'idx_ver_curso');
            }
            
            if (!$this->indexExists('cursadas', 'idx_carrera')) {
                $table->index('carrera', 'idx_carrera');
            }
            
            if (!$this->indexExists('cursadas', 'idx_sede')) {
                $table->index('sede', 'idx_sede');
            }
            
            if (!$this->indexExists('cursadas', 'idx_xmodalidad')) {
                $table->index('xModalidad', 'idx_xmodalidad');
            }
            
            if (!$this->indexExists('cursadas', 'idx_regimen')) {
                $table->index('Régimen', 'idx_regimen');
            }
            
            if (!$this->indexExists('cursadas', 'idx_xturno')) {
                $table->index('xTurno', 'idx_xturno');
            }
            
            if (!$this->indexExists('cursadas', 'idx_xdias')) {
                $table->index('xDias', 'idx_xdias');
            }
            
            // Índice compuesto para la consulta más común: ver_curso + carrera
            if (!$this->indexExists('cursadas', 'idx_ver_curso_carrera')) {
                $table->index(['ver_curso', 'carrera'], 'idx_ver_curso_carrera');
            }
            
            // Índice para ordenamiento por fecha
            if (!$this->indexExists('cursadas', 'idx_fecha_inicio')) {
                $table->index('Fecha_Inicio', 'idx_fecha_inicio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            $table->dropIndex('idx_ver_curso');
            $table->dropIndex('idx_carrera');
            $table->dropIndex('idx_sede');
            $table->dropIndex('idx_xmodalidad');
            $table->dropIndex('idx_regimen');
            $table->dropIndex('idx_xturno');
            $table->dropIndex('idx_xdias');
            $table->dropIndex('idx_ver_curso_carrera');
            $table->dropIndex('idx_fecha_inicio');
        });
    }
    
    /**
     * Verificar si un índice existe
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = DB::connection();
        $database = $connection->getDatabaseName();
        
        $result = DB::select(
            "SELECT COUNT(*) as count 
             FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$database, $table, $index]
        );
        
        return $result[0]->count > 0;
    }
};
