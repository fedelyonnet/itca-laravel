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
            $table->string('club_itca_video')->nullable();
            $table->text('club_itca_texto')->nullable();
            $table->string('club_itca_button_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn(['club_itca_video', 'club_itca_texto', 'club_itca_button_url']);
        });
    }
};
