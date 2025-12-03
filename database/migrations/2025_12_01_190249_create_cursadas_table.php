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
        Schema::create('cursadas', function (Blueprint $table) {
            $table->id();
            $table->string('id_curso', 50);
            $table->string('nombre_curso', 255);
            $table->integer('vacantes');
            $table->string('sede', 255);
            $table->string('x_modalidad', 255);
            $table->string('dias', 255);
            $table->string('x_turno', 255);
            $table->decimal('matricula_base', 10, 2);
            $table->decimal('matricula_con_50_dcto', 10, 2);
            $table->integer('cantidad_cuotas');
            $table->decimal('valor_cuota', 10, 2);
            $table->text('descr');
            $table->string('cod1', 100);
            $table->string('cod2', 100);
            $table->string('duracion', 255);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('mes_inicio', 50);
            $table->string('mes_fin', 50);
            $table->string('horario', 255);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('id_aula', 50);
            $table->string('x_tipo', 255);
            $table->string('x_nivel', 255);
            $table->string('x_cod1', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursadas');
    }
};
