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
        Schema::table('en_accion', function (Blueprint $table) {
            $table->dropColumn('destino');
            $table->string('url')->after('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('en_accion', function (Blueprint $table) {
            $table->dropColumn('url');
            $table->enum('destino', ['ig', 'tiktok', 'youtube'])->after('version');
        });
    }
};
