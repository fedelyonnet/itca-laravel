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
        Schema::table('beneficios', function (Blueprint $table) {
            $table->integer('orden')->default(0)->after('id');
        });
        
        // Asignar orden inicial basado en ID
        DB::statement('UPDATE beneficios SET orden = id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
};
