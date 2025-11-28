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
        // Eliminar todos los registros existentes
        DB::table('certificado_carreras')->truncate();
        
        Schema::table('certificado_carreras', function (Blueprint $table) {
            // Eliminar la columna imagen antigua
            $table->dropColumn('imagen');
        });
        
        Schema::table('certificado_carreras', function (Blueprint $table) {
            // Agregar los dos campos de certificados
            $table->string('certificado_1')->nullable()->after('id');
            $table->string('certificado_2')->nullable()->after('certificado_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificado_carreras', function (Blueprint $table) {
            $table->dropColumn(['certificado_1', 'certificado_2']);
        });
        
        Schema::table('certificado_carreras', function (Blueprint $table) {
            $table->string('imagen')->after('id');
        });
    }
};
