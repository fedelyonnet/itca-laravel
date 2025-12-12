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
        // Cambiar Dto_Cuota de DECIMAL(10,2) a DECIMAL(5,2) para porcentajes
        \DB::statement('ALTER TABLE `cursadas` MODIFY `Dto_Cuota` DECIMAL(5,2) NULL');
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
