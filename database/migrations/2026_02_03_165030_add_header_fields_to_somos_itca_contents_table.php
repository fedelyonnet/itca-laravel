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
            $table->string('hero_image')->nullable()->after('id');
            $table->string('title_line_1')->nullable()->after('hero_image');
            $table->string('title_line_2')->nullable()->after('title_line_1');
            $table->string('title_line_3')->nullable()->after('title_line_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('somos_itca_contents', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'title_line_1', 'title_line_2', 'title_line_3']);
        });
    }
};
