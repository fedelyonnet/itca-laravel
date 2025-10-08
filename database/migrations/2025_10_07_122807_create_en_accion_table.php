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
        Schema::create('en_accion', function (Blueprint $table) {
            $table->id();
            $table->enum('version', ['mob', 'desktop']);
            $table->enum('destino', ['ig', 'tiktok', 'youtube']);
            $table->string('video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('en_accion');
    }
};
