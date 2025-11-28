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
        Schema::table('modalidad_tipos', function (Blueprint $table) {
            $table->string('teoria_practica')->nullable()->after('clases_semana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidad_tipos', function (Blueprint $table) {
            $table->dropColumn('teoria_practica');
        });
    }
};
