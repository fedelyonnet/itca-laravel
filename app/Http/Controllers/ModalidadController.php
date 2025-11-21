<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Modalidad;
use App\Models\ModalidadTipo;
use App\Models\ModalidadHorario;
use App\Models\ModalidadColumna;
use Illuminate\Support\Facades\Storage;

class ModalidadController extends Controller
{
    public function index()
    {
        $cursos = Curso::ordered()->get();
        $cursoSeleccionado = null;
        $modalidades = collect();
        
        // Si hay un curso_id en la request, cargar ese curso y sus modalidades
        if (request()->has('curso_id')) {
            $cursoSeleccionado = Curso::with([
                'modalidades.columnas' => function($query) {
                    $query->orderBy('orden');
                },
                'modalidades.tipos' => function($query) {
                    $query->orderBy('orden');
                },
                'modalidades.horarios' => function($query) {
                    $query->orderBy('orden');
                }
            ])->find(request('curso_id'));
            
            if ($cursoSeleccionado) {
                $modalidades = $cursoSeleccionado->modalidades()->with(['columnas', 'tipos', 'horarios'])->orderBy('orden')->get();
            }
        }
        
        return view('admin.modalidades', compact('cursos', 'cursoSeleccionado', 'modalidades'));
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

        return redirect()->route('admin.modalidades', ['curso_id' => $request->curso_id])
            ->with('success', 'Modalidad creada correctamente.');
    }

    public function getData($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        
        return response()->json([
            'id' => $modalidad->id,
            'nombre' => $modalidad->nombre,
            'nombre_linea1' => $modalidad->nombre_linea1,
            'nombre_linea2' => $modalidad->nombre_linea2,
            'texto_info' => $modalidad->texto_info,
            'activo' => $modalidad->activo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $modalidad = Modalidad::findOrFail($id);
        
        $request->validate([
            'nombre' => 'nullable|string|max:255',
            'nombre_linea1' => 'required|string|max:255',
            'nombre_linea2' => 'nullable|string|max:255',
            'texto_info' => 'nullable|string',
        ]);

        // Limpiar solo espacios al inicio/final de las líneas (no guiones, pueden ser parte del texto)
        $nombre_linea1 = trim($request->nombre_linea1);
        $nombre_linea2 = $request->nombre_linea2 ? trim($request->nombre_linea2) : null;
        
        // Construir el nombre: si hay línea 2, unir con guion; si no, solo línea 1
        $nombre = $nombre_linea1;
        if ($nombre_linea2) {
            $nombre .= '-' . $nombre_linea2;
        }

        $modalidad->update([
            'nombre' => $request->nombre ?? $nombre,
            'nombre_linea1' => $nombre_linea1,
            'nombre_linea2' => $nombre_linea2,
            'texto_info' => $request->texto_info,
        ]);

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Modalidad actualizada correctamente.');
    }

    public function destroy($id)
    {
        $modalidad = Modalidad::findOrFail($id);
        $cursoId = $modalidad->curso_id;
        
        // Eliminar también las relaciones (columnas, tipos, horarios)
        $modalidad->columnas()->delete();
        $modalidad->tipos()->delete();
        $modalidad->horarios()->delete();
        
        $modalidad->delete();

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

    // ========== GESTIÓN DE COLUMNAS ==========

    public function storeColumna(Request $request)
    {
        $request->validate([
            'modalidad_id' => 'required|exists:modalidades,id',
            'nombre' => 'required|string|max:255',
            'icono_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'campo_dato' => 'required|string|max:100',
        ]);

        // Verificar que no se excedan las 6 columnas máximas
        $cantidadColumnas = ModalidadColumna::where('modalidad_id', $request->modalidad_id)->count();
        if ($cantidadColumnas >= 6) {
            return redirect()->back()->withErrors(['icono_file' => 'No se pueden agregar más de 6 columnas por modalidad.'])->withInput();
        }

        // Guardar el archivo subido
        $file = $request->file('icono_file');
        $path = $file->store('modalidades/iconos', 'public');
        $iconoPath = '/storage/' . $path;

        $maxOrden = ModalidadColumna::where('modalidad_id', $request->modalidad_id)->max('orden') ?? 0;

        ModalidadColumna::create([
            'modalidad_id' => $request->modalidad_id,
            'nombre' => $request->nombre,
            'icono' => $iconoPath,
            'campo_dato' => $request->campo_dato,
            'orden' => $maxOrden + 1,
        ]);

        $modalidad = Modalidad::findOrFail($request->modalidad_id);

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Columna agregada correctamente.');
    }

    public function getColumnaData($id)
    {
        $columna = ModalidadColumna::findOrFail($id);
        
        return response()->json([
            'id' => $columna->id,
            'modalidad_id' => $columna->modalidad_id,
            'nombre' => $columna->nombre,
            'icono' => $columna->icono,
            'campo_dato' => $columna->campo_dato,
        ]);
    }

    public function updateColumna(Request $request, $id)
    {
        $columna = ModalidadColumna::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'icono_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'campo_dato' => 'required|string|max:100',
        ]);

        // Si se subió un archivo, guardarlo y usar su ruta
        $iconoPath = $columna->icono;
        if ($request->hasFile('icono_file')) {
            $file = $request->file('icono_file');
            // Eliminar icono anterior si existe y está en storage
            if ($columna->icono && strpos($columna->icono, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $columna->icono);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            // Guardar nuevo icono en storage/app/public/modalidades/iconos
            $path = $file->store('modalidades/iconos', 'public');
            $iconoPath = '/storage/' . $path;
        }

        $columna->update([
            'nombre' => $request->nombre,
            'icono' => $iconoPath,
            'campo_dato' => $request->campo_dato,
        ]);

        $modalidad = $columna->modalidad;

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Columna actualizada correctamente.');
    }

    public function destroyColumna($id)
    {
        $columna = ModalidadColumna::findOrFail($id);
        $modalidad = $columna->modalidad;
        $cursoId = $modalidad->curso_id;
        
        $columna->delete();

        return redirect()->route('admin.modalidades', ['curso_id' => $cursoId])
            ->with('success', 'Columna eliminada correctamente.');
    }

    public function moverColumna(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modalidad_columnas,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $columna = ModalidadColumna::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $columna->orden;
        
        if ($direccion === 'up') {
            $columnaAnterior = ModalidadColumna::where('modalidad_id', $columna->modalidad_id)
                                ->where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$columnaAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $columna->update(['orden' => $columnaAnterior->orden]);
            $columnaAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $columnaSiguiente = ModalidadColumna::where('modalidad_id', $columna->modalidad_id)
                                  ->where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$columnaSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $columna->update(['orden' => $columnaSiguiente->orden]);
            $columnaSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Columna movida correctamente'
        ]);
    }

    public function reordenarColumnas(Request $request)
    {
        $request->validate([
            'modalidad_id' => 'required|exists:modalidades,id',
            'columnas_ids' => 'required|array',
            'columnas_ids.*' => 'exists:modalidad_columnas,id'
        ]);

        $modalidadId = $request->modalidad_id;
        $columnasIds = $request->columnas_ids;

        // Verificar que todas las columnas pertenecen a la modalidad
        $columnas = ModalidadColumna::where('modalidad_id', $modalidadId)
            ->whereIn('id', $columnasIds)
            ->get();

        if ($columnas->count() !== count($columnasIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Algunas columnas no pertenecen a esta modalidad'
            ], 422);
        }

        // Actualizar el orden de cada columna
        foreach ($columnasIds as $orden => $columnaId) {
            ModalidadColumna::where('id', $columnaId)
                ->update(['orden' => $orden + 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }

    // ========== GESTIÓN DE TIPOS ==========

    public function storeTipo(Request $request)
    {
        $request->validate([
            'modalidad_id' => 'required|exists:modalidades,id',
            'nombre' => 'required|string|max:255',
            'duracion' => 'required|string|max:100',
            'dedicacion' => 'required|string|max:200',
            'clases_semana' => 'required|string|max:100',
            'horas_teoria' => 'nullable|string|max:100',
            'horas_practica' => 'nullable|string|max:100',
            'horas_virtuales' => 'nullable|string|max:100',
            'horas_presenciales' => 'nullable|string|max:100',
            'mes_inicio' => 'required|string|max:100',
        ]);

        $maxOrden = ModalidadTipo::where('modalidad_id', $request->modalidad_id)->max('orden') ?? 0;

        ModalidadTipo::create([
            'modalidad_id' => $request->modalidad_id,
            'nombre' => $request->nombre,
            'duracion' => $request->duracion,
            'dedicacion' => $request->dedicacion,
            'clases_semana' => $request->clases_semana,
            'horas_teoria' => $request->horas_teoria,
            'horas_practica' => $request->horas_practica,
            'horas_virtuales' => $request->horas_virtuales,
            'horas_presenciales' => $request->horas_presenciales,
            'mes_inicio' => $request->mes_inicio,
            'orden' => $maxOrden + 1,
        ]);

        $modalidad = Modalidad::findOrFail($request->modalidad_id);

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
            'horas_teoria' => $tipo->horas_teoria,
            'horas_practica' => $tipo->horas_practica,
            'horas_virtuales' => $tipo->horas_virtuales,
            'horas_presenciales' => $tipo->horas_presenciales,
            'mes_inicio' => $tipo->mes_inicio,
        ]);
    }

    public function updateTipo(Request $request, $id)
    {
        $tipo = ModalidadTipo::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'duracion' => 'required|string|max:100',
            'dedicacion' => 'required|string|max:200',
            'clases_semana' => 'required|string|max:100',
            'horas_teoria' => 'nullable|string|max:100',
            'horas_practica' => 'nullable|string|max:100',
            'horas_virtuales' => 'nullable|string|max:100',
            'horas_presenciales' => 'nullable|string|max:100',
            'mes_inicio' => 'required|string|max:100',
        ]);

        $tipo->update([
            'nombre' => $request->nombre,
            'duracion' => $request->duracion,
            'dedicacion' => $request->dedicacion,
            'clases_semana' => $request->clases_semana,
            'horas_teoria' => $request->horas_teoria,
            'horas_practica' => $request->horas_practica,
            'horas_virtuales' => $request->horas_virtuales,
            'horas_presenciales' => $request->horas_presenciales,
            'mes_inicio' => $request->mes_inicio,
        ]);

        $modalidad = $tipo->modalidad;

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

    // ========== GESTIÓN DE HORARIOS ==========

    public function storeHorario(Request $request)
    {
        $request->validate([
            'modalidad_id' => 'required|exists:modalidades,id',
            'nombre' => 'required|string|max:255',
            'hora_inicio' => 'required|string|max:20',
            'hora_fin' => 'required|string|max:20',
            'icono_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        // Si se subió un archivo, guardarlo y usar su ruta
        $iconoPath = null;
        if ($request->hasFile('icono_file')) {
            $file = $request->file('icono_file');
            $path = $file->store('modalidades/iconos', 'public');
            $iconoPath = '/storage/' . $path;
        }

        $maxOrden = ModalidadHorario::where('modalidad_id', $request->modalidad_id)->max('orden') ?? 0;

        ModalidadHorario::create([
            'modalidad_id' => $request->modalidad_id,
            'nombre' => $request->nombre,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'icono' => $iconoPath,
            'orden' => $maxOrden + 1,
        ]);

        $modalidad = Modalidad::findOrFail($request->modalidad_id);

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Horario agregado correctamente.');
    }

    public function getHorarioData($id)
    {
        $horario = ModalidadHorario::findOrFail($id);
        
        return response()->json([
            'id' => $horario->id,
            'modalidad_id' => $horario->modalidad_id,
            'nombre' => $horario->nombre,
            'hora_inicio' => $horario->hora_inicio,
            'hora_fin' => $horario->hora_fin,
            'icono' => $horario->icono,
        ]);
    }

    public function updateHorario(Request $request, $id)
    {
        $horario = ModalidadHorario::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'hora_inicio' => 'required|string|max:20',
            'hora_fin' => 'required|string|max:20',
            'icono_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        // Si se subió un archivo, guardarlo y usar su ruta
        $iconoPath = $horario->icono;
        if ($request->hasFile('icono_file')) {
            $file = $request->file('icono_file');
            // Eliminar icono anterior si existe y está en storage
            if ($horario->icono && strpos($horario->icono, '/storage/') === 0) {
                $oldPath = str_replace('/storage/', '', $horario->icono);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            // Guardar nuevo icono en storage/app/public/modalidades/iconos
            $path = $file->store('modalidades/iconos', 'public');
            $iconoPath = '/storage/' . $path;
        }

        $horario->update([
            'nombre' => $request->nombre,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'icono' => $iconoPath,
        ]);

        $modalidad = $horario->modalidad;

        return redirect()->route('admin.modalidades', ['curso_id' => $modalidad->curso_id])
            ->with('success', 'Horario actualizado correctamente.');
    }

    public function destroyHorario($id)
    {
        $horario = ModalidadHorario::findOrFail($id);
        $modalidad = $horario->modalidad;
        $cursoId = $modalidad->curso_id;
        
        $horario->delete();

        return redirect()->route('admin.modalidades', ['curso_id' => $cursoId])
            ->with('success', 'Horario eliminado correctamente.');
    }

    public function moverHorario(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:modalidad_horarios,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $horario = ModalidadHorario::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $horario->orden;
        
        if ($direccion === 'up') {
            $horarioAnterior = ModalidadHorario::where('modalidad_id', $horario->modalidad_id)
                                ->where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$horarioAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $horario->update(['orden' => $horarioAnterior->orden]);
            $horarioAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $horarioSiguiente = ModalidadHorario::where('modalidad_id', $horario->modalidad_id)
                                  ->where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$horarioSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $horario->update(['orden' => $horarioSiguiente->orden]);
            $horarioSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Horario movido correctamente'
        ]);
    }
}
