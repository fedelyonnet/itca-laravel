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
        Schema::table('beneficios', function (Blueprint $table) {
            $table->dropColumn('alineacion_bottom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios', function (Blueprint $table) {
            $table->enum('alineacion_bottom', ['left', 'right', 'center'])->default('center');
        });
    }
};