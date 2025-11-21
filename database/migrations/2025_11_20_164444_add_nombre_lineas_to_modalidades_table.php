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
        Schema::table('modalidades', function (Blueprint $table) {
            $table->string('nombre_linea1')->nullable()->after('nombre');
            $table->string('nombre_linea2')->nullable()->after('nombre_linea1');
        });

        // Migrar datos existentes: si el nombre contiene '-', dividirlo en 2 líneas
        // Si no, poner todo en nombre_linea1
        $modalidades = \DB::table('modalidades')->get();
        foreach ($modalidades as $modalidad) {
            $nombre = $modalidad->nombre;
            if (strpos($nombre, '-') !== false) {
                $partes = explode('-', $nombre, 2);
                \DB::table('modalidades')
                    ->where('id', $modalidad->id)
                    ->update([
                        'nombre_linea1' => trim($partes[0]),
                        'nombre_linea2' => trim($partes[1] ?? ''),
                    ]);
            } else {
                \DB::table('modalidades')
                    ->where('id', $modalidad->id)
                    ->update([
                        'nombre_linea1' => $nombre,
                        'nombre_linea2' => null,
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidades', function (Blueprint $table) {
            $table->dropColumn(['nombre_linea1', 'nombre_linea2']);
        });
    }
};
