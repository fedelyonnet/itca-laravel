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
            $table->text('tienda_text')->nullable()->after('bolsa_work_button_url');
            $table->string('tienda_button_url')->nullable()->after('tienda_text');
        });

        Schema::create('beneficio_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficios_content_id')->constrained('beneficios_contents')->onDelete('cascade');
            $table->string('image_path');
            $table->string('descripcion')->nullable(); // Alt text or similar
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficio_productos');

        Schema::table('beneficios_contents', function (Blueprint $table) {
            $table->dropColumn(['tienda_text', 'tienda_button_url']);
        });
    }
};
