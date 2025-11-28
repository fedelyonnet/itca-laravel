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
            $table->dropColumn('horas_presenciales');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidad_tipos', function (Blueprint $table) {
            $table->string('horas_presenciales')->nullable()->after('horas_virtuales');
        });
    }
};
