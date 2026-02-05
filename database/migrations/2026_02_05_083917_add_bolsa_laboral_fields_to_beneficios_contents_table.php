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
            $table->string('bolsa_work_image')->nullable()->after('club_itca_button_url');
            $table->text('bolsa_work_text')->nullable()->after('bolsa_work_image');
            $table->string('bolsa_work_button_url')->nullable()->after('bolsa_work_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn(['bolsa_work_image', 'bolsa_work_text', 'bolsa_work_button_url']);
        });
    }
};
