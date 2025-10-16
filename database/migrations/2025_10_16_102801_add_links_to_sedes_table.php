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
        Schema::table('sedes', function (Blueprint $table) {
            $table->string('link_google_maps')->nullable()->after('telefono');
            $table->string('link_whatsapp')->nullable()->after('link_google_maps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn(['link_google_maps', 'link_whatsapp']);
        });
    }
};
