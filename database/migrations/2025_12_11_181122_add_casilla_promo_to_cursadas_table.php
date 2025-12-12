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
        if (!Schema::hasColumn('cursadas', 'casilla_Promo')) {
            \DB::statement('ALTER TABLE `cursadas` ADD COLUMN `casilla_Promo` BOOLEAN DEFAULT FALSE AFTER `sede`');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            if (Schema::hasColumn('cursadas', 'casilla_Promo')) {
                $table->dropColumn('casilla_Promo');
            }
        });
    }
};
