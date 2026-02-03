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
        Schema::create('instalacion_items', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->integer('orden')->default(0);
            $table->foreignId('somos_itca_content_id')->constrained('somos_itca_contents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalacion_items');
    }
};
