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
        Schema::table('cursadas', function (Blueprint $table) {
            $table->integer('cuotas')->nullable()->after('Dto_Cuota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            $table->dropColumn('cuotas');
        });
    }
};
