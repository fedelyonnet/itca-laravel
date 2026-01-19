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
            $columnsToDrop = [
                'Sin_IVA',
                'casilla_Promo',
                'Promo_ctas',
                'Proximamente',
                'avisar',
                'Avisar_link'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('cursadas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursadas', function (Blueprint $table) {
            $table->decimal('Sin_IVA', 10, 2)->nullable()->after('Dto_Cuota');
            $table->boolean('casilla_Promo')->default(false)->after('sede');
            $table->string('Promo_ctas', 255)->nullable()->after('casilla_Promo');
            $table->string('Proximamente', 255)->nullable()->after('Promo_Mat_logo');
            $table->string('avisar', 255)->nullable()->after('Proximamente');
            $table->string('Avisar_link', 500)->nullable()->after('avisar');
        });
    }
};
