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
        Schema::create('cursadas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        
        // Crear columnas exactamente como aparecen en carreras.xlsx
        // Verificar y crear cada columna solo si no existe
        $columnas = [
            ['ID_Curso', 'VARCHAR(50)', 'id'],
            ['carrera', 'VARCHAR(255)', 'ID_Curso'],
            ['Cod1', 'VARCHAR(100)', 'carrera'],
            ['Fecha_Inicio', 'DATE', 'Cod1'],
            ['xDias', 'VARCHAR(255)', 'Fecha_Inicio'],
            ['xModalidad', 'VARCHAR(255)', 'xDias'],
            ['Régimen', 'VARCHAR(255)', 'xModalidad'],
            ['xTurno', 'VARCHAR(255)', 'Régimen'],
            ['Horario', 'VARCHAR(255)', 'xTurno'],
            ['Vacantes', 'INT', 'Horario'],
            ['Matric_Base', 'DECIMAL(10,2)', 'Vacantes'],
            ['Cta_Web', 'DECIMAL(10,2)', 'Matric_Base'],
            ['Dto_Cuota', 'DECIMAL(5,2)', 'Cta_Web'],
            ['Sin_IVA', 'DECIMAL(10,2)', 'Dto_Cuota'],
            ['sede', 'VARCHAR(255)', 'Sin_IVA'],
            ['casilla_Promo', 'TINYINT(1) DEFAULT 0', 'sede'],
        ];
        
        foreach ($columnas as $columna) {
            if (!Schema::hasColumn('cursadas', $columna[0])) {
                $after = $columna[2] === 'id' ? 'AFTER `id`' : "AFTER `{$columna[2]}`";
                \DB::statement("ALTER TABLE `cursadas` ADD COLUMN `{$columna[0]}` {$columna[1]} NULL {$after}");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursadas');
    }
};
