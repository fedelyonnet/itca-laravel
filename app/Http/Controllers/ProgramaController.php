<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\CursoAnio;
use App\Models\AnioUnidad;

class ProgramaController extends Controller
{
    public function index()
    {
        $cursos = Curso::ordered()->get();
        $cursoSeleccionado = null;
        $anios = collect();
        
        // Si hay un curso_id en la request, cargar ese curso y sus años
        if (request()->has('curso_id')) {
            $cursoSeleccionado = Curso::with(['anios.unidades' => function($query) {
                $query->orderBy('orden');
            }])->find(request('curso_id'));
            
            if ($cursoSeleccionado) {
                $anios = $cursoSeleccionado->anios()->with('unidades')->orderBy('orden')->get();
            }
        }
        
        return view('admin.programas', compact('cursos', 'cursoSeleccionado', 'anios'));
    }

    // ========== GESTIÓN DE AÑOS ==========

    public function storeAnio(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'año' => 'required|integer|min:1|max:10',
            'titulo' => 'required|string|max:255',
            'nivel' => 'required|string|max:100',
        ]);

        // Verificar que no exista ya ese año para ese curso
        $existe = CursoAnio::where('curso_id', $request->curso_id)
            ->where('año', $request->año)
            ->exists();

        if ($existe) {
            return redirect()->back()->withErrors(['año' => 'Ya existe un año ' . $request->año . ' para este curso.'])->withInput();
        }

        // Obtener el siguiente orden
        $maxOrden = CursoAnio::where('curso_id', $request->curso_id)->max('orden') ?? 0;

        CursoAnio::create([
            'curso_id' => $request->curso_id,
            'año' => $request->año,
            'titulo' => $request->titulo,
            'nivel' => $request->nivel,
            'orden' => $maxOrden + 1,
        ]);

        return redirect()->route('admin.programas', ['curso_id' => $request->curso_id])
            ->with('success', 'Año agregado correctamente.');
    }

    public function getAnioData($id)
    {
        $anio = CursoAnio::findOrFail($id);
        
        return response()->json([
            'id' => $anio->id,
            'curso_id' => $anio->curso_id,
            'año' => $anio->año,
            'titulo' => $anio->titulo,
            'nivel' => $anio->nivel,
        ]);
    }

    public function updateAnio(Request $request, $id)
    {
        $anio = CursoAnio::findOrFail($id);
        
        $request->validate([
            'año' => 'required|integer|min:1|max:10',
            'titulo' => 'required|string|max:255',
            'nivel' => 'required|string|max:100',
        ]);

        // Verificar que no exista otro año con el mismo número para ese curso
        $existe = CursoAnio::where('curso_id', $anio->curso_id)
            ->where('año', $request->año)
            ->where('id', '!=', $id)
            ->exists();

        if ($existe) {
            return redirect()->back()->withErrors(['año' => 'Ya existe un año ' . $request->año . ' para este curso.'])->withInput();
        }

        $anio->update([
            'año' => $request->año,
            'titulo' => $request->titulo,
            'nivel' => $request->nivel,
        ]);

        return redirect()->route('admin.programas', ['curso_id' => $anio->curso_id])
            ->with('success', 'Año actualizado correctamente.');
    }

    public function destroyAnio($id)
    {
        $anio = CursoAnio::findOrFail($id);
        $cursoId = $anio->curso_id;
        
        // Eliminar también todas las unidades del año (cascade)
        $anio->delete();

        return redirect()->route('admin.programas', ['curso_id' => $cursoId])
            ->with('success', 'Año eliminado correctamente.');
    }

    public function moverAnio(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:curso_anios,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $anio = CursoAnio::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $anio->orden;
        
        if ($direccion === 'up') {
            $anioAnterior = CursoAnio::where('curso_id', $anio->curso_id)
                                ->where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$anioAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $anio->update(['orden' => $anioAnterior->orden]);
            $anioAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $anioSiguiente = CursoAnio::where('curso_id', $anio->curso_id)
                                  ->where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$anioSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $anio->update(['orden' => $anioSiguiente->orden]);
            $anioSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Año movido correctamente'
        ]);
    }

    // ========== GESTIÓN DE UNIDADES ==========

    public function storeUnidad(Request $request)
    {
        $request->validate([
            'curso_anio_id' => 'required|exists:curso_anios,id',
            'numero' => 'required|string|max:50',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string',
        ]);

        // Obtener el siguiente orden
        $maxOrden = AnioUnidad::where('curso_anio_id', $request->curso_anio_id)->max('orden') ?? 0;

        $unidad = AnioUnidad::create([
            'curso_anio_id' => $request->curso_anio_id,
            'numero' => $request->numero,
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
            'orden' => $maxOrden + 1,
        ]);

        $anio = CursoAnio::find($request->curso_anio_id);

        return redirect()->route('admin.programas', ['curso_id' => $anio->curso_id])
            ->with('success', 'Unidad agregada correctamente.');
    }

    public function getUnidadData($id)
    {
        $unidad = AnioUnidad::findOrFail($id);
        
        return response()->json([
            'id' => $unidad->id,
            'curso_anio_id' => $unidad->curso_anio_id,
            'numero' => $unidad->numero,
            'titulo' => $unidad->titulo,
            'subtitulo' => $unidad->subtitulo,
        ]);
    }

    public function updateUnidad(Request $request, $id)
    {
        $unidad = AnioUnidad::findOrFail($id);
        
        $request->validate([
            'numero' => 'required|string|max:50',
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string',
        ]);

        $unidad->update([
            'numero' => $request->numero,
            'titulo' => $request->titulo,
            'subtitulo' => $request->subtitulo,
        ]);

        $anio = $unidad->cursoAnio;

        return redirect()->route('admin.programas', ['curso_id' => $anio->curso_id])
            ->with('success', 'Unidad actualizada correctamente.');
    }

    public function destroyUnidad($id)
    {
        $unidad = AnioUnidad::findOrFail($id);
        $anio = $unidad->cursoAnio;
        $cursoId = $anio->curso_id;
        
        $unidad->delete();

        return redirect()->route('admin.programas', ['curso_id' => $cursoId])
            ->with('success', 'Unidad eliminada correctamente.');
    }

    public function moverUnidad(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:anio_unidades,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $unidad = AnioUnidad::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $unidad->orden;
        
        if ($direccion === 'up') {
            $unidadAnterior = AnioUnidad::where('curso_anio_id', $unidad->curso_anio_id)
                                ->where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$unidadAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $unidad->update(['orden' => $unidadAnterior->orden]);
            $unidadAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $unidadSiguiente = AnioUnidad::where('curso_anio_id', $unidad->curso_anio_id)
                                  ->where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$unidadSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $unidad->update(['orden' => $unidadSiguiente->orden]);
            $unidadSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Unidad movida correctamente'
        ]);
    }
}
