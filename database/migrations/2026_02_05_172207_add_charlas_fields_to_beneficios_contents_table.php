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
            // Charlas y Visitas Técnicas - 4 flip cards
            for ($i = 1; $i <= 4; $i++) {
                $table->string("charla{$i}_img")->nullable();
                $table->string("charla{$i}_title")->nullable();
                $table->text("charla{$i}_text")->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            // Drop charlas fields
            for ($i = 1; $i <= 4; $i++) {
                $table->dropColumn("charla{$i}_img");
                $table->dropColumn("charla{$i}_title");
                $table->dropColumn("charla{$i}_text");
            }
        });
    }
};
