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
            $table->string('manuales_img1')->nullable();
            $table->string('manuales_img2')->nullable();
            $table->text('manuales_texto')->nullable();
            $table->string('manuales_button_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn(['manuales_img1', 'manuales_img2', 'manuales_texto', 'manuales_button_url']);
        });
    }
};
