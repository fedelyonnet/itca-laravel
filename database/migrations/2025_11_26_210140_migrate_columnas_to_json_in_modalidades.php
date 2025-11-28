<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar datos de modalidad_columnas a columnas_visibles (JSON) en modalidades
        $modalidades = DB::table('modalidades')->get();
        
        foreach ($modalidades as $modalidad) {
            // Obtener todas las columnas de esta modalidad ordenadas por orden
            $columnas = DB::table('modalidad_columnas')
                ->where('modalidad_id', $modalidad->id)
                ->orderBy('orden')
                ->get();
            
            // Convertir a array JSON
            $columnasArray = $columnas->map(function ($columna) {
                return [
                    'campo' => $columna->campo_dato,
                    'nombre' => $columna->nombre,
                    'icono' => $columna->icono,
                    'orden' => $columna->orden,
                ];
            })->toArray();
            
            // Actualizar la modalidad con el JSON
            DB::table('modalidades')
                ->where('id', $modalidad->id)
                ->update([
                    'columnas_visibles' => json_encode($columnasArray)
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: crear registros en modalidad_columnas desde el JSON
        $modalidades = DB::table('modalidades')
            ->whereNotNull('columnas_visibles')
            ->get();
        
        foreach ($modalidades as $modalidad) {
            $columnas = json_decode($modalidad->columnas_visibles, true);
            
            if (is_array($columnas)) {
                foreach ($columnas as $columna) {
                    DB::table('modalidad_columnas')->insert([
                        'modalidad_id' => $modalidad->id,
                        'nombre' => $columna['nombre'] ?? '',
                        'icono' => $columna['icono'] ?? '',
                        'campo_dato' => $columna['campo'] ?? '',
                        'orden' => $columna['orden'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
};
