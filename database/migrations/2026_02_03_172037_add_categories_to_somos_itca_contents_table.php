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
        Schema::table('somos_itca_contents', function (Blueprint $col) {
            for ($i = 1; $i <= 4; $i++) {
                $col->string("cat{$i}_img")->nullable();
                $col->string("cat{$i}_title")->nullable();
                $col->text("cat{$i}_text")->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('somos_itca_contents', function (Blueprint $col) {
            for ($i = 1; $i <= 4; $i++) {
                $col->dropColumn(["cat{$i}_img", "cat{$i}_title", "cat{$i}_text"]);
            }
        });
    }
};
