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
        // Add sorteos fields to beneficios_contents table
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->text('sorteos_text')->nullable();
            $table->string('sorteos_button_url')->nullable();
        });

        // Create beneficio_sorteos table for carousel images
        Schema::create('beneficio_sorteos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficios_content_id')->constrained('beneficios_contents')->onDelete('cascade');
            $table->string('image_path');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficio_sorteos');
        
        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn(['sorteos_text', 'sorteos_button_url']);
        });
    }
};
