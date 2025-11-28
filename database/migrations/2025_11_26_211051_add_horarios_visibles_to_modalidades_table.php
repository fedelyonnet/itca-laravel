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
        Schema::table('modalidades', function (Blueprint $table) {
            $table->json('horarios_visibles')->nullable()->after('columnas_visibles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->dropColumn('horarios_visibles');
        });
    }
};
