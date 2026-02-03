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
        Schema::table('somos_itca_contents', function (Blueprint $table) {
            $table->text('formadores_texto')->nullable()->after('img_por_que');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('somos_itca_contents', function (Blueprint $table) {
            $table->dropColumn('formadores_texto');
        });
    }
};
