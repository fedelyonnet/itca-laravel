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
        // Migrar datos de modalidad_horarios a horarios_visibles (JSON) en modalidades
        $modalidades = DB::table('modalidades')->get();
        
        foreach ($modalidades as $modalidad) {
            // Obtener todos los horarios de esta modalidad ordenados por orden
            $horarios = DB::table('modalidad_horarios')
                ->where('modalidad_id', $modalidad->id)
                ->orderBy('orden')
                ->get();
            
            // Convertir a array JSON
            $horariosArray = $horarios->map(function ($horario) {
                return [
                    'nombre' => $horario->nombre,
                    'hora_inicio' => $horario->hora_inicio,
                    'hora_fin' => $horario->hora_fin,
                    'icono' => $horario->icono,
                    'orden' => $horario->orden,
                ];
            })->toArray();
            
            // Actualizar la modalidad con el JSON
            DB::table('modalidades')
                ->where('id', $modalidad->id)
                ->update([
                    'horarios_visibles' => json_encode($horariosArray)
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir: crear registros en modalidad_horarios desde el JSON
        $modalidades = DB::table('modalidades')
            ->whereNotNull('horarios_visibles')
            ->get();
        
        foreach ($modalidades as $modalidad) {
            $horarios = json_decode($modalidad->horarios_visibles, true);
            
            if (is_array($horarios)) {
                foreach ($horarios as $horario) {
                    DB::table('modalidad_horarios')->insert([
                        'modalidad_id' => $modalidad->id,
                        'nombre' => $horario['nombre'] ?? '',
                        'hora_inicio' => $horario['hora_inicio'] ?? '',
                        'hora_fin' => $horario['hora_fin'] ?? '',
                        'icono' => $horario['icono'] ?? null,
                        'orden' => $horario['orden'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
};
