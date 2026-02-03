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
        // Check if columns exist to avoid errors if re-running
        if (Schema::hasTable('instalaciones')) {
            Schema::table('instalaciones', function (Blueprint $table) {
                if (!Schema::hasColumn('instalaciones', 'orden')) {
                    $table->integer('orden')->default(0);
                }
                if (!Schema::hasColumn('instalaciones', 'descripcion')) {
                    $table->text('descripcion')->nullable();
                }
            });
        }
        
        // Drop incorrectly named table if it exists and is empty/unused
        // if (Schema::hasTable('instalacions')) { Schema::drop('instalacions'); } 
    }

    public function down(): void
    {
        Schema::table('instalaciones', function (Blueprint $table) {
            $table->dropColumn(['orden', 'descripcion']);
        });
    }
};
