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
        // Orden: ID_Curso, carrera, Cod1, Fecha Inicio, xDias, xModalidad, Régimen, xTurno, Horario, Vacantes, Matric Base, Cta.Web, Dto.Cuota, Sin IVA, sede, casilla_Promo
        \DB::statement("ALTER TABLE `cursadas` 
            ADD COLUMN IF NOT EXISTS `ID_Curso` VARCHAR(50) NULL AFTER `id`,
            ADD COLUMN IF NOT EXISTS `carrera` VARCHAR(255) NULL AFTER `ID_Curso`,
            ADD COLUMN IF NOT EXISTS `Cod1` VARCHAR(100) NULL AFTER `carrera`,
            ADD COLUMN IF NOT EXISTS `Fecha_Inicio` DATE NULL AFTER `Cod1`,
            ADD COLUMN IF NOT EXISTS `xDias` VARCHAR(255) NULL AFTER `Fecha_Inicio`,
            ADD COLUMN IF NOT EXISTS `xModalidad` VARCHAR(255) NULL AFTER `xDias`,
            ADD COLUMN IF NOT EXISTS `Régimen` VARCHAR(255) NULL AFTER `xModalidad`,
            ADD COLUMN IF NOT EXISTS `xTurno` VARCHAR(255) NULL AFTER `Régimen`,
            ADD COLUMN IF NOT EXISTS `Horario` VARCHAR(255) NULL AFTER `xTurno`,
            ADD COLUMN IF NOT EXISTS `Vacantes` INT NULL AFTER `Horario`,
            ADD COLUMN IF NOT EXISTS `Matric_Base` DECIMAL(10,2) NULL AFTER `Vacantes`,
            ADD COLUMN IF NOT EXISTS `Cta_Web` DECIMAL(10,2) NULL AFTER `Matric_Base`,
            ADD COLUMN IF NOT EXISTS `Dto_Cuota` DECIMAL(5,2) NULL AFTER `Cta_Web`,
            ADD COLUMN IF NOT EXISTS `Sin_IVA` DECIMAL(10,2) NULL AFTER `Dto_Cuota`,
            ADD COLUMN IF NOT EXISTS `sede` VARCHAR(255) NULL AFTER `Sin_IVA`,
            ADD COLUMN IF NOT EXISTS `casilla_Promo` TINYINT(1) NULL DEFAULT 0 AFTER `sede`
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
