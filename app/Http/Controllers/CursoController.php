<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::ordered()->get();
        return view('admin.cursos', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'modalidad_online' => 'nullable',
            'modalidad_presencial' => 'nullable',
            'ilustracion_desktop' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ilustracion_mobile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Validar que al menos una modalidad sea seleccionada
        if (!$request->has('modalidad_online') && !$request->has('modalidad_presencial')) {
            return redirect()->back()->withErrors(['modalidades' => 'Debes seleccionar al menos una modalidad.'])->withInput();
        }

        $curso = new Curso();
        $curso->nombre = $request->nombre;
        $curso->descripcion = $request->descripcion;
        $curso->modalidad_online = $request->has('modalidad_online');
        $curso->modalidad_presencial = $request->has('modalidad_presencial');
        $curso->fecha_inicio = $request->fecha_inicio;

        // Asignar orden secuencial (máximo orden + 1)
        $maxOrden = Curso::max('orden') ?? 0;
        $curso->orden = $maxOrden + 1;

        // Subir ilustración desktop (obligatoria)
        \Log::info('Subiendo imagen desktop', [
            'file_name' => $request->file('ilustracion_desktop')->getClientOriginalName(),
            'file_size' => $request->file('ilustracion_desktop')->getSize()
        ]);
        
        try {
            $desktopPath = $request->file('ilustracion_desktop')->store('cursos', 'public');
            \Log::info('Imagen desktop guardada exitosamente', ['path' => $desktopPath]);
            $curso->ilustracion_desktop = $desktopPath;
        } catch (\Exception $e) {
            \Log::error('Error subiendo imagen desktop', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['ilustracion_desktop' => 'Error al subir la imagen desktop: ' . $e->getMessage()]);
        }

        // Subir ilustración mobile (obligatoria)
        \Log::info('Subiendo imagen mobile', [
            'file_name' => $request->file('ilustracion_mobile')->getClientOriginalName(),
            'file_size' => $request->file('ilustracion_mobile')->getSize()
        ]);
        
        try {
            $mobilePath = $request->file('ilustracion_mobile')->store('cursos', 'public');
            \Log::info('Imagen mobile guardada exitosamente', ['path' => $mobilePath]);
            $curso->ilustracion_mobile = $mobilePath;
        } catch (\Exception $e) {
            \Log::error('Error subiendo imagen mobile', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['ilustracion_mobile' => 'Error al subir la imagen mobile: ' . $e->getMessage()]);
        }

        $curso->save();

        return redirect()->route('admin.carreras')->with('success', 'Carrera agregada correctamente');
    }

    public function edit($id)
    {
        $curso = Curso::findOrFail($id);
        $cursos = Curso::ordered()->get();
        return view('admin.cursos', compact('cursos', 'curso'));
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'modalidad_online' => 'nullable',
            'modalidad_presencial' => 'nullable',
            'ilustracion_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ilustracion_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Validar que al menos una modalidad sea seleccionada
        if (!$request->has('modalidad_online') && !$request->has('modalidad_presencial')) {
            return redirect()->back()->withErrors(['modalidades' => 'Debes seleccionar al menos una modalidad.'])->withInput();
        }

        $curso->nombre = $request->nombre;
        $curso->descripcion = $request->descripcion;
        $curso->modalidad_online = $request->has('modalidad_online');
        $curso->modalidad_presencial = $request->has('modalidad_presencial');
        $curso->fecha_inicio = $request->fecha_inicio;

        // Actualizar ilustración desktop si se proporciona
        if ($request->hasFile('ilustracion_desktop')) {
            // Eliminar imagen anterior
            if ($curso->ilustracion_desktop) {
                Storage::disk('public')->delete($curso->ilustracion_desktop);
            }
            $curso->ilustracion_desktop = $request->file('ilustracion_desktop')->store('cursos', 'public');
        }

        // Actualizar ilustración mobile si se proporciona
        if ($request->hasFile('ilustracion_mobile')) {
            // Eliminar imagen anterior
            if ($curso->ilustracion_mobile) {
                Storage::disk('public')->delete($curso->ilustracion_mobile);
            }
            $curso->ilustracion_mobile = $request->file('ilustracion_mobile')->store('cursos', 'public');
        }

        $curso->save();

        return redirect()->route('admin.carreras')->with('success', 'Carrera actualizada correctamente');
    }

    public function toggleFeatured($id)
    {
        $curso = Curso::findOrFail($id);
        $featuredCount = Curso::where('featured', true)->count();
        
        \Log::info('Toggle featured', [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->nombre,
            'current_featured' => $curso->featured,
            'featured_count' => $featuredCount
        ]);
        
        // Si está intentando seleccionar y ya hay 2 featured, no permitir
        if (!$curso->featured && $featuredCount >= 2) {
            \Log::info('No se puede seleccionar - ya hay 2 destacadas');
            return redirect()->route('admin.carreras')->with('error', 'Solo pueden haber máximo 2 carreras destacadas');
        }
        
        $curso->featured = !$curso->featured;
        $curso->save();

        \Log::info('Featured toggled', [
            'new_featured' => $curso->featured
        ]);

        $message = $curso->featured ? 'Carrera marcada como destacada' : 'Carrera removida de destacados';
        return redirect()->route('admin.carreras')->with('success', $message);
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        
        \Log::info('Intentando eliminar carrera', [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->nombre
        ]);

        // Eliminar archivos de storage
        if ($curso->ilustracion_desktop) {
            Storage::disk('public')->delete($curso->ilustracion_desktop);
        }
        if ($curso->ilustracion_mobile) {
            Storage::disk('public')->delete($curso->ilustracion_mobile);
        }

        $curso->delete();

        \Log::info('Carrera eliminada exitosamente', [
            'curso_id' => $id
        ]);

        return redirect()->route('admin.carreras')->with('success', 'Carrera eliminada correctamente');
    }

    public function mover(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cursos,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $curso = Curso::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $curso->orden;
        
        \Log::info('Moviendo curso', [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->nombre,
            'orden_actual' => $ordenActual,
            'direccion' => $direccion
        ]);
        
        if ($direccion === 'up') {
            // Buscar el curso con orden inmediatamente menor
            $cursoAnterior = Curso::where('orden', '<', $ordenActual)
                            ->orderBy('orden', 'desc')
                            ->first();
            
            if (!$cursoAnterior) {
                \Log::info('No hay curso anterior');
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            \Log::info('Curso anterior encontrado', [
                'curso_anterior_id' => $cursoAnterior->id,
                'curso_anterior_nombre' => $cursoAnterior->nombre,
                'curso_anterior_orden' => $cursoAnterior->orden
            ]);
            
            // Intercambiar órdenes
            $curso->update(['orden' => $cursoAnterior->orden]);
            $cursoAnterior->update(['orden' => $ordenActual]);
            
            \Log::info('Intercambio completado', [
                'curso_nuevo_orden' => $curso->fresh()->orden,
                'curso_anterior_nuevo_orden' => $cursoAnterior->fresh()->orden
            ]);
            
        } else { // down
            // Buscar el curso con orden inmediatamente mayor
            $cursoSiguiente = Curso::where('orden', '>', $ordenActual)
                              ->orderBy('orden', 'asc')
                              ->first();
            
            if (!$cursoSiguiente) {
                \Log::info('No hay curso siguiente');
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            \Log::info('Curso siguiente encontrado', [
                'curso_siguiente_id' => $cursoSiguiente->id,
                'curso_siguiente_nombre' => $cursoSiguiente->nombre,
                'curso_siguiente_orden' => $cursoSiguiente->orden
            ]);
            
            // Intercambiar órdenes
            $curso->update(['orden' => $cursoSiguiente->orden]);
            $cursoSiguiente->update(['orden' => $ordenActual]);
            
            \Log::info('Intercambio completado', [
                'curso_nuevo_orden' => $curso->fresh()->orden,
                'curso_siguiente_nuevo_orden' => $cursoSiguiente->fresh()->orden
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Carrera movida correctamente'
        ]);
    }
}
