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
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->unsignedBigInteger('descuento_id')->nullable()->after('cursada_id');
            $table->string('codigo_descuento')->nullable()->after('descuento_id');
            $table->decimal('monto_descuento', 10, 2)->default(0)->after('monto_matricula');
            
            $table->foreign('descuento_id')->references('id')->on('descuentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropForeign(['descuento_id']);
            $table->dropColumn(['descuento_id', 'codigo_descuento', 'monto_descuento']);
        });
    }
};
