<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Modalidad;
use App\Models\ModalidadTipo;
use Illuminate\Support\Facades\Storage;

class ModalidadController extends Controller
{
    public function index()
    {
        // Redirigir a la vista unificada de gestión de carreras
        // Las modalidades ahora se gestionan desde test.blade.php
        return redirect()->route('admin.carreras.test');
    }

    public function store(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'nombre_linea1' => 'required|string|max:255',
            'nombre_linea2' => 'nullable|string|max:255',
            'texto_info' => 'nullable|string',
        ]);

        // Limpiar solo espacios al inicio/final de las líneas
        $nombre_linea1 = trim($request->nombre_linea1);
        $nombre_linea2 = $request->nombre_linea2 ? trim($request->nombre_linea2) : null;
        
        // Construir el nombre
        $nombre = $nombre_linea1;
        if ($nombre_linea2) {
            $nombre .= '-' . $nombre_linea2;
        }

        // Obtener el máximo orden para este curso
        $maxOrden = Modalidad::where('curso_id', $request->curso_id)->max('orden') ?? 0;

        $modalidad = Modalidad::create([
            'curso_id' => $request->curso_id,
            'nombre' => $nombre,
            'nombre_linea1' => $nombre_linea1,
            'nombre_linea2' => $nombre_linea2,
            'texto_info' => $request->texto_info,
            'orden' => $maxOrden + 1,
            'activo' => true,
        ]);

        // Si viene de la vista test, redirigir a test con curso_id y tab
        if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
            return redirect()->route('admin.carreras.test', [
                'curso_id' => $request->curso_id,
                'tab' => 'modalidades'
            ])->with('success', 'Modalidad creada correctamente.');
        }

        return redirect()->route('admin.modalidades', ['curso_id' => $request->curso_id])
            ->with('success', 'Modalidad creada correctamente.');
    }

    public function getData($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        
        return response()->json([
            'id' => $modalidad->id,
            'curso_id' => $modalidad->curso_id,
            'nombre' => $modalidad->nombre,
            'nombre_linea1' => $modalidad->nombre_linea1,
            'nombre_linea2' => $modalidad->nombre_linea2,
            'texto_info' => $modalidad->texto_info,
            'columnas_visibles' => $modalidad->columnas_visibles,
            'horarios_visibles' => $modalidad->horarios_visibles,
            'activo' => $modalidad->activo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $modalidad = Modalidad::findOrFail($id);
        
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'nombre_linea1' => 'nullable|string|max:255',
            'nombre_linea2' => 'nullable|string|max:255',
            'texto_info' => 'nullable|string',
            'columnas_visibles' => 'nullable|array',
        ]);

        $updateData = [];

        // Si viene nombre_linea1, actualizar nombre y líneas
        if ($request->has('nombre_linea1')) {
            $nombre_linea1 = trim($request->nombre_linea1);
            $nombre_linea2 = $request->nombre_linea2 ? trim($request->nombre_linea2) : null;
            
            $nombre = $nombre_linea1;
            if ($nombre_linea2) {
                $nombre .= '-' . $nombre_linea2;
            }

            $updateData['nombre'] = $request->nombre ?? $nombre;
            $updateData['nombre_linea1'] = $nombre_linea1;
            $updateData['nombre_linea2'] = $nombre_linea2;
        }

        // Si viene texto_info, actualizarlo
        if ($request->has('texto_info')) {
            $updateData['texto_info'] = $request->texto_info;
        }

        // Si viene columnas_visibles, actualizarlo
        if ($request->has('columnas_visibles')) {
            // Asegurar que sea un array
            $columnasVisibles = $request->columnas_visibles;
            if (is_string($columnasVisibles)) {
                $columnasVisibles = json_decode($columnasVisibles, true);
            }
            if (is_array($columnasVisibles)) {
                $updateData['columnas_visibles'] = $columnasVisibles;
            }
        }

        // Si viene horarios_visibles, actualizarlo
        if ($request->has('horarios_visibles')) {
            $horariosVisibles = $request->horarios_visibles;
            // Si es un string JSON, decodificarlo
            if (is_string($horariosVisibles)) {
                $horariosVisibles = json_decode($horariosVisibles, true);
            }
            // Asegurar que sea un array
            if (is_array($horariosVisibles)) {
                $updateData['horarios_visibles'] = $horariosVisibles;
            }
        }

        $modalidad->update($updateData);

        // Si viene de la vista test, devolver JSON
        if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
            return response()->json([
                'success' => true,
                'message' => 'Modalidad actualizada correctamente'
            ], 200, ['Content-Type' => 'application/json']);
        }

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Modalidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $cursoId = $modalidad->curso_id;
        
        // Eliminar también las relaciones (tipos)
        $modalidad->tipos()->delete();
        
        $modalidad->delete();

        // Si viene de AJAX (vista test), devolver JSON
        if (request()->ajax() || request()->wantsJson() || request()->has('from_test')) {
            return response()->json([
                'success' => true,
                'message' => 'Modalidad eliminada correctamente'
            ]);
        }

        return redirect()->route('admin.modalidades', ['curso_id' => $cursoId])
            ->with('success', 'Modalidad eliminada correctamente.');
    }

    public function toggleActivo(Request $request, $id)
    {
        $modalidad = Modalidad::findOrFail($id);
        
        $modalidad->update([
            'activo' => !$modalidad->activo,
        ]);

        return response()->json([
            'success' => true,
            'activo' => $modalidad->activo,
            'message' => $modalidad->activo ? 'Modalidad activada' : 'Modalidad desactivada'
        ]);
    }


    // ========== GESTIÓN DE TIPOS ==========

    public function storeTipo(Request $request)
    {
        $request->validate([
            'modalidad_id' => 'required|exists:modalidades,id',
            'nombre' => 'required|string|max:255',
            'duracion' => 'nullable|string|max:255',
            'dedicacion' => 'nullable|string|max:255',
            'clases_semana' => 'nullable|string|max:255',
            'horas_virtuales' => 'nullable|string|max:255',
            'teoria_practica' => 'nullable|string|max:255',
            'horas_teoria' => 'nullable|string|max:255',
            'horas_practica' => 'nullable|string|max:255',
            'mes_inicio' => 'nullable|string|max:255',
        ]);

        $maxOrden = ModalidadTipo::where('modalidad_id', $request->modalidad_id)->max('orden') ?? 0;

        $tipo = ModalidadTipo::create([
            'modalidad_id' => $request->modalidad_id,
            'nombre' => $request->nombre,
            'duracion' => $request->duracion ?? '',
            'dedicacion' => $request->dedicacion ?? '',
            'clases_semana' => $request->clases_semana ?? '',
            'teoria_practica' => $request->teoria_practica ?? '',
            'horas_teoria' => $request->horas_teoria ?? '',
            'horas_practica' => $request->horas_practica ?? '',
            'horas_virtuales' => $request->horas_virtuales ?? '',
            'mes_inicio' => $request->mes_inicio ?? '',
            'orden' => $maxOrden + 1,
        ]);

        $modalidad = Modalidad::findOrFail($request->modalidad_id);

        // Si viene de la vista test, devolver JSON
        if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
            return response()->json([
                'success' => true,
                'id' => $tipo->id,
                'message' => 'Tipo creado correctamente'
            ], 200, ['Content-Type' => 'application/json']);
        }

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Tipo agregado correctamente.');
    }

    public function getTipoData($id)
    {
        $tipo = ModalidadTipo::findOrFail($id);
        
        return response()->json([
            'id' => $tipo->id,
            'modalidad_id' => $tipo->modalidad_id,
            'nombre' => $tipo->nombre,
            'duracion' => $tipo->duracion,
            'dedicacion' => $tipo->dedicacion,
            'clases_semana' => $tipo->clases_semana,
            'teoria_practica' => $tipo->teoria_practica,
            'teoria_practica' => $tipo->teoria_practica,
            'horas_teoria' => $tipo->horas_teoria,
            'horas_practica' => $tipo->horas_practica,
            'horas_virtuales' => $tipo->horas_virtuales,
            'mes_inicio' => $tipo->mes_inicio,
        ]);
    }

    public function updateTipo(Request $request, $id)
    {
        $tipo = ModalidadTipo::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'duracion' => 'nullable|string|max:255',
            'dedicacion' => 'nullable|string|max:255',
            'clases_semana' => 'nullable|string|max:255',
            'teoria_practica' => 'nullable|string|max:255',
            'horas_teoria' => 'nullable|string|max:255',
            'horas_practica' => 'nullable|string|max:255',
            'horas_virtuales' => 'nullable|string|max:255',
            'mes_inicio' => 'nullable|string|max:255',
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'duracion' => $request->duracion ?? '',
            'dedicacion' => $request->dedicacion ?? '',
            'clases_semana' => $request->clases_semana ?? '',
            'teoria_practica' => $request->teoria_practica ?? '',
            'horas_teoria' => $request->horas_teoria ?? '',
            'horas_practica' => $request->horas_practica ?? '',
            'horas_virtuales' => $request->horas_virtuales ?? '',
            'mes_inicio' => $request->mes_inicio ?? '',
        ]);

        $modalidad = $tipo->modalidad;

        // Si viene de la vista test, devolver JSON
        if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
            return response()->json([
                'success' => true,
                'id' => $tipo->id,
                'message' => 'Tipo actualizado correctamente'
            ], 200, ['Content-Type' => 'application/json']);
        }

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Tipo actualizado correctamente.');
    }

    public function destroyTipo($id)
    {
        $tipo = ModalidadTipo::findOrFail($id);
        $modalidad = $tipo->modalidad;
        $cursoId = $modalidad->curso_id;
        
        $tipo->delete();

        return redirect()->route('admin.modalidades', ['curso_id' => $cursoId])
            ->with('success', 'Tipo eliminado correctamente.');
    }

    public function toggleActivoTipo(Request $request, $id)
    {
        $tipo = ModalidadTipo::findOrFail($id);
        
        $tipo->update([
            'activo' => !($tipo->activo ?? true),
        ]);

        return response()->json([
            'success' => true,
            'activo' => $tipo->activo,
            'message' => $tipo->activo ? 'Tipo activado' : 'Tipo desactivado'
        ]);
    }

    public function moverTipo(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modalidad_tipos,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $tipo = ModalidadTipo::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $tipo->orden;
        
        if ($direccion === 'up') {
            $tipoAnterior = ModalidadTipo::where('modalidad_id', $tipo->modalidad_id)
                                ->where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$tipoAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $tipo->update(['orden' => $tipoAnterior->orden]);
            $tipoAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $tipoSiguiente = ModalidadTipo::where('modalidad_id', $tipo->modalidad_id)
                                  ->where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$tipoSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $tipo->update(['orden' => $tipoSiguiente->orden]);
            $tipoSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Tipo movido correctamente'
        ]);
    }

}
