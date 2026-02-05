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
            // Add fecha field for each charla card
            for ($i = 1; $i <= 4; $i++) {
                $table->string("charla{$i}_fecha")->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficios_contents', function (Blueprint $table) {
            // Drop fecha fields
            for ($i = 1; $i <= 4; $i++) {
                $table->dropColumn("charla{$i}_fecha");
            }
        });
    }
};
