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
        // Agregar nuevas columnas del Excel
        if (!Schema::hasColumn('cursadas', 'Promo_ctas')) {
            Schema::table('cursadas', function (Blueprint $table) {
                $table->string('Promo_ctas', 255)->nullable()->after('casilla_Promo');
            });
        }
        if (!Schema::hasColumn('cursadas', 'Promo_Mat_logo')) {
            Schema::table('cursadas', function (Blueprint $table) {
                $table->string('Promo_Mat_logo', 255)->nullable()->after('Promo_ctas');
            });
        }
        if (!Schema::hasColumn('cursadas', 'Proximamente')) {
            Schema::table('cursadas', function (Blueprint $table) {
                $table->string('Proximamente', 255)->nullable()->after('Promo_Mat_logo');
            });
        }
        if (!Schema::hasColumn('cursadas', 'avisar')) {
            Schema::table('cursadas', function (Blueprint $table) {
                $table->string('avisar', 255)->nullable()->after('Proximamente');
            });
        }
        if (!Schema::hasColumn('cursadas', 'Avisar_link')) {
            Schema::table('cursadas', function (Blueprint $table) {
                $table->string('Avisar_link', 500)->nullable()->after('avisar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            // Eliminar las columnas agregadas
            if (Schema::hasColumn('cursadas', 'Avisar_link')) {
                $table->dropColumn('Avisar_link');
            }
            if (Schema::hasColumn('cursadas', 'avisar')) {
                $table->dropColumn('avisar');
            }
            if (Schema::hasColumn('cursadas', 'Proximamente')) {
                $table->dropColumn('Proximamente');
            }
            if (Schema::hasColumn('cursadas', 'Promo_Mat_logo')) {
                $table->dropColumn('Promo_Mat_logo');
            }
            if (Schema::hasColumn('cursadas', 'Promo_ctas')) {
                $table->dropColumn('Promo_ctas');
            }
        });
    }
};
