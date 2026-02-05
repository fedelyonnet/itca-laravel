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
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->string('competencia_itca_video')->nullable()->after('tienda_button_url');
            $table->text('competencia_itca_texto')->nullable()->after('competencia_itca_video');
            $table->string('competencia_itca_button_url')->nullable()->after('competencia_itca_texto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn([
                'competencia_itca_video',
                'competencia_itca_texto',
                'competencia_itca_button_url'
            ]);
        });
    }
};
