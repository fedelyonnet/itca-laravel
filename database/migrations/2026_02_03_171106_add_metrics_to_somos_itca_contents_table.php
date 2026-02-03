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
            $col->string('m1_number')->nullable();
            $col->string('m1_title')->nullable();
            $col->text('m1_text')->nullable();
            
            $col->string('m2_number')->nullable();
            $col->string('m2_title')->nullable();
            $col->text('m2_text')->nullable();
            
            $col->string('m3_number')->nullable();
            $col->string('m3_title')->nullable();
            $col->text('m3_text')->nullable();
            
            $col->string('m4_number')->nullable();
            $col->string('m4_title')->nullable();
            $col->text('m4_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('somos_itca_contents', function (Blueprint $col) {
            $col->dropColumn([
                'm1_number', 'm1_title', 'm1_text',
                'm2_number', 'm2_title', 'm2_text',
                'm3_number', 'm3_title', 'm3_text',
                'm4_number', 'm4_title', 'm4_text'
            ]);
        });
    }
};
