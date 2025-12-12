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
        // Orden: ID_Curso, carrera, Cod1, Fecha Inicio, xDias, xModalidad, Régimen, xTurno, Horario, Vacantes, Matric Base, Cta.Web, Dto.Cuota, Sin IVA, sede
        \DB::statement("ALTER TABLE `cursadas` 
            ADD COLUMN `ID_Curso` VARCHAR(50) NULL AFTER `id`,
            ADD COLUMN `carrera` VARCHAR(255) NULL AFTER `ID_Curso`,
            ADD COLUMN `Cod1` VARCHAR(100) NULL AFTER `carrera`,
            ADD COLUMN `Fecha_Inicio` DATE NULL AFTER `Cod1`,
            ADD COLUMN `xDias` VARCHAR(255) NULL AFTER `Fecha_Inicio`,
            ADD COLUMN `xModalidad` VARCHAR(255) NULL AFTER `xDias`,
            ADD COLUMN `Régimen` VARCHAR(255) NULL AFTER `xModalidad`,
            ADD COLUMN `xTurno` VARCHAR(255) NULL AFTER `Régimen`,
            ADD COLUMN `Horario` VARCHAR(255) NULL AFTER `xTurno`,
            ADD COLUMN `Vacantes` INT NULL AFTER `Horario`,
            ADD COLUMN `Matric_Base` DECIMAL(10,2) NULL AFTER `Vacantes`,
            ADD COLUMN `Cta_Web` DECIMAL(10,2) NULL AFTER `Matric_Base`,
            ADD COLUMN `Dto_Cuota` DECIMAL(10,2) NULL AFTER `Cta_Web`,
            ADD COLUMN `Sin_IVA` DECIMAL(10,2) NULL AFTER `Dto_Cuota`,
            ADD COLUMN `sede` VARCHAR(255) NULL AFTER `Sin_IVA`
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursadas');
    }
};
