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
        Schema::table('leads', function (Blueprint $table) {
            // Eliminar la foreign key primero
            $table->dropForeign(['cursada_id']);
            
            // Cambiar el tipo de columna de unsignedBigInteger a string para almacenar ID_Curso
            $table->string('cursada_id', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Revertir a unsignedBigInteger
            $table->unsignedBigInteger('cursada_id')->nullable()->change();
            
            // Restaurar la foreign key
            $table->foreign('cursada_id')->references('id')->on('cursadas')->onDelete('set null');
        });
    }
};
