<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migración consolidada basada en BASE CARRERAS AL 17-12.xlsx
     * Maneja tanto la creación de la tabla como la actualización si ya existe
     */
    public function up(): void
    {
        if (!Schema::hasTable('cursadas')) {
            // Si la tabla no existe, crearla con todas las columnas del Excel
            Schema::create('cursadas', function (Blueprint $table) {
                $table->id();
                $table->string('ID_Curso', 50)->nullable();
                $table->string('carrera', 255)->nullable();
                $table->string('Cod1', 100)->nullable();
                $table->date('Fecha_Inicio')->nullable();
                $table->string('xDias', 255)->nullable();
                $table->string('xModalidad', 255)->nullable();
                $table->string('Régimen', 255)->nullable();
                $table->string('xTurno', 255)->nullable();
                $table->string('Horario', 255)->nullable();
                $table->integer('Vacantes')->nullable();
                $table->decimal('Matric_Base', 10, 2)->nullable();
                $table->decimal('Sin_iva_Mat', 10, 2)->nullable();
                $table->decimal('Cta_Web', 10, 2)->nullable();
                $table->decimal('Sin_IVA_cta', 10, 2)->nullable();
                $table->decimal('Dto_Cuota', 5, 2)->nullable();
                $table->string('sede', 255)->nullable();
                $table->string('Promo_Mat_logo', 255)->nullable();
                $table->string('ver_curso', 255)->nullable();
                $table->timestamps();
            });
        } else {
            // Si la tabla existe (producción), agregar/modificar solo las columnas que faltan o cambiaron
            Schema::table('cursadas', function (Blueprint $table) {
                // Nuevas columnas del Excel
                if (!Schema::hasColumn('cursadas', 'Sin_iva_Mat')) {
                    $table->decimal('Sin_iva_Mat', 10, 2)->nullable()->after('Matric_Base');
                }
                if (!Schema::hasColumn('cursadas', 'Sin_IVA_cta')) {
                    $table->decimal('Sin_IVA_cta', 10, 2)->nullable()->after('Cta_Web');
                }
                if (!Schema::hasColumn('cursadas', 'ver_curso')) {
                    // Verificar si Promo_Mat_logo existe para usarlo como referencia
                    $afterColumn = Schema::hasColumn('cursadas', 'Promo_Mat_logo') ? 'Promo_Mat_logo' : 'sede';
                    $table->string('ver_curso', 255)->nullable()->after($afterColumn);
                }
                
                // Promo_Mat_logo debería existir de migraciones anteriores (2025_12_16_174245)
                // Si no existe, la agregamos
                if (!Schema::hasColumn('cursadas', 'Promo_Mat_logo')) {
                    $afterColumn = Schema::hasColumn('cursadas', 'casilla_Promo') ? 'casilla_Promo' : 'sede';
                    $table->string('Promo_Mat_logo', 255)->nullable()->after($afterColumn);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos la tabla en down() porque puede estar en producción
        // Solo eliminamos las columnas que agregamos
        if (Schema::hasTable('cursadas')) {
            Schema::table('cursadas', function (Blueprint $table) {
                if (Schema::hasColumn('cursadas', 'ver_curso')) {
                    $table->dropColumn('ver_curso');
                }
                if (Schema::hasColumn('cursadas', 'Sin_IVA_cta')) {
                    $table->dropColumn('Sin_IVA_cta');
                }
                if (Schema::hasColumn('cursadas', 'Sin_iva_Mat')) {
                    $table->dropColumn('Sin_iva_Mat');
                }
            });
        }
    }
};
