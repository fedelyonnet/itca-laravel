<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\EnAccion;
use App\Models\FotosCarrera;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('sedes')->ordered()->get();
        $sedes = \App\Models\Sede::ordered()->get();
        return view('admin.cursos', compact('cursos', 'sedes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'fecha_inicio' => 'required|date',
            'modalidad_online' => 'nullable',
            'modalidad_presencial' => 'nullable',
            'sedes' => 'nullable|array',
            'sedes.*' => 'exists:sedes,id',
            'ilustracion_desktop' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'ilustracion_mobile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_show_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_show_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'nombre.required' => 'El nombre de la carrera es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
            'descripcion.string' => 'La descripción debe ser texto.',
            'descripcion.max' => 'La descripción no debe exceder 1000 caracteres.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'ilustracion_desktop.required' => 'La ilustración desktop es obligatoria.',
            'ilustracion_desktop.image' => 'La ilustración desktop debe ser una imagen válida.',
            'ilustracion_desktop.mimes' => 'La ilustración desktop debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'ilustracion_desktop.max' => 'La ilustración desktop no debe ser mayor a 2048 kilobytes (2MB).',
            'ilustracion_mobile.required' => 'La ilustración mobile es obligatoria.',
            'ilustracion_mobile.image' => 'La ilustración mobile debe ser una imagen válida.',
            'ilustracion_mobile.mimes' => 'La ilustración mobile debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'ilustracion_mobile.max' => 'La ilustración mobile no debe ser mayor a 2048 kilobytes (2MB).',
            'imagen_show_desktop.max' => 'La imagen de carrera individual desktop no debe ser mayor a 2048 kilobytes (2MB).',
            'imagen_show_desktop.image' => 'La imagen de carrera individual desktop debe ser una imagen válida.',
            'imagen_show_desktop.mimes' => 'La imagen de carrera individual desktop debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'imagen_show_mobile.max' => 'La imagen de carrera individual mobile no debe ser mayor a 2048 kilobytes (2MB).',
            'imagen_show_mobile.image' => 'La imagen de carrera individual mobile debe ser una imagen válida.',
            'imagen_show_mobile.mimes' => 'La imagen de carrera individual mobile debe ser de tipo: jpeg, png, jpg, gif o webp.',
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
            return redirect()->back()->withErrors(['ilustracion_desktop' => 'Error al subir la imagen desktop: ' . $e->getMessage()])->withInput();
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
            return redirect()->back()->withErrors(['ilustracion_mobile' => 'Error al subir la imagen mobile: ' . $e->getMessage()])->withInput();
        }

        // Subir imagen show desktop (opcional)
        if ($request->hasFile('imagen_show_desktop')) {
            try {
                $showDesktopPath = $request->file('imagen_show_desktop')->store('cursos', 'public');
                $curso->imagen_show_desktop = $showDesktopPath;
            } catch (\Exception $e) {
                \Log::error('Error subiendo imagen show desktop', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['imagen_show_desktop' => 'Error al subir la imagen show desktop: ' . $e->getMessage()])->withInput();
            }
        }

        // Subir imagen show mobile (opcional)
        if ($request->hasFile('imagen_show_mobile')) {
            try {
                $showMobilePath = $request->file('imagen_show_mobile')->store('cursos', 'public');
                $curso->imagen_show_mobile = $showMobilePath;
            } catch (\Exception $e) {
                \Log::error('Error subiendo imagen show mobile', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors(['imagen_show_mobile' => 'Error al subir la imagen show mobile: ' . $e->getMessage()])->withInput();
            }
        }

        $curso->save();

        // Sincronizar sedes
        if ($request->has('sedes')) {
            $curso->sedes()->sync($request->sedes);
        } else {
            $curso->sedes()->sync([]);
        }

        return redirect()->route('admin.carreras')->with('success', 'Carrera agregada correctamente');
    }

    public function edit($id)
    {
        $curso = Curso::with('sedes')->findOrFail($id);
        $cursos = Curso::with('sedes')->ordered()->get();
        $sedes = \App\Models\Sede::ordered()->get();
        return view('admin.cursos', compact('cursos', 'curso', 'sedes'));
    }

    public function update(Request $request, $id)
    {
        try {
            $curso = Curso::findOrFail($id);
            
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'fecha_inicio' => 'required|date',
                'modalidad_online' => 'nullable',
                'modalidad_presencial' => 'nullable',
                'sedes' => 'nullable|array',
                'sedes.*' => 'exists:sedes,id',
                'ilustracion_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'ilustracion_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'imagen_show_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'imagen_show_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ], [
                'nombre.required' => 'El nombre de la carrera es obligatorio.',
                'nombre.string' => 'El nombre debe ser texto.',
                'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
                'descripcion.string' => 'La descripción debe ser texto.',
                'descripcion.max' => 'La descripción no debe exceder 1000 caracteres.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
                'ilustracion_desktop.image' => 'La ilustración desktop debe ser una imagen válida.',
                'ilustracion_desktop.mimes' => 'La ilustración desktop debe ser de tipo: jpeg, png, jpg, gif o webp.',
                'ilustracion_desktop.max' => 'La ilustración desktop no debe ser mayor a 2048 kilobytes (2MB).',
                'ilustracion_mobile.image' => 'La ilustración mobile debe ser una imagen válida.',
                'ilustracion_mobile.mimes' => 'La ilustración mobile debe ser de tipo: jpeg, png, jpg, gif o webp.',
                'ilustracion_mobile.max' => 'La ilustración mobile no debe ser mayor a 2048 kilobytes (2MB).',
                'imagen_show_desktop.max' => 'La imagen de carrera individual desktop no debe ser mayor a 2048 kilobytes (2MB).',
                'imagen_show_desktop.image' => 'La imagen de carrera individual desktop debe ser una imagen válida.',
                'imagen_show_desktop.mimes' => 'La imagen de carrera individual desktop debe ser de tipo: jpeg, png, jpg, gif o webp.',
                'imagen_show_mobile.max' => 'La imagen de carrera individual mobile no debe ser mayor a 2048 kilobytes (2MB).',
                'imagen_show_mobile.image' => 'La imagen de carrera individual mobile debe ser una imagen válida.',
                'imagen_show_mobile.mimes' => 'La imagen de carrera individual mobile debe ser de tipo: jpeg, png, jpg, gif o webp.',
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
            
            // Validar featured: máximo 2 carreras destacadas
            $wantsFeatured = $request->has('featured');
            if ($wantsFeatured && !$curso->featured) {
                // Está intentando marcar como destacada
                $featuredCount = Curso::where('featured', true)->where('id', '!=', $curso->id)->count();
                if ($featuredCount >= 2) {
                    $errorMessage = 'Solo pueden haber máximo 2 carreras destacadas. Ya hay ' . $featuredCount . ' carreras destacadas.';
                    if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage
                        ], 422, ['Content-Type' => 'application/json']);
                    }
                    return redirect()->back()->withErrors(['featured' => $errorMessage])->withInput();
                }
            }
            $curso->featured = $wantsFeatured;

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

            // Actualizar imagen show desktop si se proporciona
            if ($request->hasFile('imagen_show_desktop')) {
                // Eliminar imagen anterior
                if ($curso->imagen_show_desktop) {
                    Storage::disk('public')->delete($curso->imagen_show_desktop);
                }
                $curso->imagen_show_desktop = $request->file('imagen_show_desktop')->store('cursos', 'public');
            }

            // Actualizar imagen show mobile si se proporciona
            if ($request->hasFile('imagen_show_mobile')) {
                // Eliminar imagen anterior
                if ($curso->imagen_show_mobile) {
                    Storage::disk('public')->delete($curso->imagen_show_mobile);
                }
                $curso->imagen_show_mobile = $request->file('imagen_show_mobile')->store('cursos', 'public');
            }

                $curso->save();

            // Sincronizar sedes
            if ($request->has('sedes')) {
                $sedes = is_array($request->sedes) ? $request->sedes : [];
                $curso->sedes()->sync($sedes);
            } else {
                // Si viene de test y no tiene sedes, significa que se deseleccionaron todas
                if ($request->has('from_test')) {
                    $curso->sedes()->sync([]);
                }
            }

            // Si viene de la vista test, devolver respuesta JSON para AJAX
            if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Información básica guardada correctamente',
                    'curso_id' => $curso->id
                ], 200, ['Content-Type' => 'application/json']);
            }

            return redirect()->route('admin.carreras')->with('success', 'Carrera actualizada correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error al actualizar carrera', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la carrera: ' . $e->getMessage()])->withInput();
        }
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

    public function show(Curso $curso)
    {
        $partners = \App\Models\Partner::ordered()->get();
        $sedes = \App\Models\Sede::ordered()->get();
        // Cargar sedes relacionadas con el curso
        $curso->load('sedes');
        // Agrupar sedes del curso por zona
        $sedesPorZona = $curso->sedes->where('disponible', true)
            ->filter(function($sede) {
                return !empty($sede->zona);
            })
            ->groupBy('zona')
            ->map(function($sedes) {
                return $sedes->pluck('nombre');
            });
        // Cargar años del programa con sus unidades
        $anios = $curso->anios()->with(['unidades' => function($query) {
            // Ordenar por el número extraído del campo numero
            // Extrae el último número del string (ej: "Unidad 1" -> 1, "Unidad 10" -> 10)
            $query->orderByRaw('CAST(SUBSTRING_INDEX(numero, " ", -1) AS UNSIGNED), numero');
        }])->orderBy('año')->get();
        // Cargar modalidades con sus relaciones (tipos)
        $modalidades = $curso->modalidades()
            ->where('activo', true)
            ->with([
                'tipos' => function($query) {
                    $query->where('activo', true)->orderBy('orden');
                }
            ])
            ->orderBy('orden')
            ->get();
        // Obtener testimonios visibles ordenados (máximo 8)
        $testimonios = \App\Models\Testimonio::where('visible', true)
                                ->ordered()
                                ->limit(8)
                                ->get();
        // Obtener videos de En Acción mobile para el carousel
        $videosMobile = EnAccion::where('version', 'mob')->orderBy('created_at', 'desc')->get();
        // Obtener FAQs ordenadas desde la base de datos
        $dudas = \App\Models\Duda::ordered()->get();
        // Obtener fotos de carrera ordenadas
        $fotos = FotosCarrera::ordered()->get();
        // Obtener el video de testimonios (si existe)
        $videoTestimonios = \App\Models\VideoTestimonioCarreraIndividual::first();
        // Obtener los certificados (si existen) - manejar error si la tabla no existe
        try {
            $certificados = \App\Models\CertificadoCarrera::first();
        } catch (\Exception $e) {
            $certificados = null;
        }
        return view('carreras.show', compact('curso', 'partners', 'sedes', 'sedesPorZona', 'anios', 'modalidades', 'testimonios', 'videosMobile', 'dudas', 'fotos', 'videoTestimonios', 'certificados'));
    }

    public function test()
    {
        $carreras = Curso::ordered()->get();
        $sedes = \App\Models\Sede::ordered()->get();
        $carreraSeleccionada = null;
        
        // Contar carreras destacadas
        $featuredCount = Curso::where('featured', true)->count();
        
        // Si hay un curso_id en la request, cargar ese curso con todas sus relaciones
        if (request()->has('curso_id') && request('curso_id')) {
            $carreraSeleccionada = Curso::with([
                'sedes',
                'anios' => function($query) {
                    $query->orderBy('año');
                },
                'anios.unidades' => function($query) {
                    // Ordenar por el número extraído del campo numero
                    // Extrae el último número del string (ej: "Unidad 1" -> 1, "Unidad 10" -> 10)
                    $query->orderByRaw('CAST(SUBSTRING_INDEX(numero, " ", -1) AS UNSIGNED), numero');
                },
                'modalidades.tipos' => function($query) {
                    $query->orderBy('orden');
                }
            ])->find(request('curso_id'));
        }
        
        return view('admin.carreras.test', compact('carreras', 'sedes', 'carreraSeleccionada', 'featuredCount'));
    }

    public function multimedia()
    {
        $fotos = FotosCarrera::ordered()->get();
        $videoTestimonios = \App\Models\VideoTestimonioCarreraIndividual::first();
        
        // Intentar obtener certificados, si falla (tabla no existe) usar null
        try {
            $certificados = \App\Models\CertificadoCarrera::first();
        } catch (\Exception $e) {
            $certificados = null;
        }
        
        return view('admin.carreras.multimedia', compact('fotos', 'videoTestimonios', 'certificados'));
    }

    public function storeFoto(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if (!$request->hasFile('imagen')) {
            return back()->withErrors(['imagen' => 'La imagen es obligatoria']);
        }
        
        $imagenPath = $request->file('imagen')->store('fotos_carrera', 'public');

        FotosCarrera::create([
            'imagen' => $imagenPath,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('admin.carreras.multimedia')->with('success', 'Foto creada exitosamente.');
    }

    public function getFotoData($id)
    {
        $foto = FotosCarrera::findOrFail($id);
        return response()->json([
            'id' => $foto->id,
            'imagen' => $foto->imagen,
            'descripcion' => $foto->descripcion,
        ]);
    }

    public function updateFoto(Request $request, $id)
    {
        $foto = FotosCarrera::findOrFail($id);
        
        $request->validate([
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($foto->imagen && Storage::disk('public')->exists($foto->imagen)) {
                Storage::disk('public')->delete($foto->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('fotos_carrera', 'public');
            $foto->imagen = $imagenPath;
        }

        $foto->descripcion = $request->descripcion;
        $foto->save();

        return redirect()->route('admin.carreras.multimedia')->with('success', 'Foto actualizada exitosamente.');
    }

    public function destroyFoto($id)
    {
        $foto = FotosCarrera::findOrFail($id);
        
        // Eliminar imagen del storage
        if ($foto->imagen && Storage::disk('public')->exists($foto->imagen)) {
            Storage::disk('public')->delete($foto->imagen);
        }
        
        $foto->delete();

        return redirect()->route('admin.carreras.multimedia')->with('success', 'Foto eliminada exitosamente.');
    }

    public function moverFoto(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:fotos_carrera,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $foto = FotosCarrera::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $foto->orden;
        
        if ($direccion === 'up') {
            // Buscar la foto con orden inmediatamente menor
            $fotoAnterior = FotosCarrera::where('orden', '<', $ordenActual)
                                        ->orderBy('orden', 'desc')
                                        ->first();
            
            if (!$fotoAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $foto->update(['orden' => $fotoAnterior->orden]);
            $fotoAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar la foto con orden inmediatamente mayor
            $fotoSiguiente = FotosCarrera::where('orden', '>', $ordenActual)
                                          ->orderBy('orden', 'asc')
                                          ->first();
            
            if (!$fotoSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $foto->update(['orden' => $fotoSiguiente->orden]);
            $fotoSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Foto movida correctamente'
        ]);
    }

    public function updateVideo(Request $request)
    {
        try {
            $request->validate([
                'video' => 'nullable|file|mimes:mp4,webm|max:102400', // 100MB en kilobytes
                'url' => 'nullable|string|max:255',
            ], [
                'video.max' => 'El video no debe ser mayor a 100MB.',
                'video.mimes' => 'El video debe ser un archivo MP4 o WEBM.',
            ]);

            // Obtener o crear el único registro (patrón similar a StickyBar)
            $videoTestimonio = \App\Models\VideoTestimonioCarreraIndividual::first();
            
            if (!$videoTestimonio) {
                // Crear el primer registro solo si hay video o URL
                if (!$request->hasFile('video') && (!$request->input('url') || $request->input('url') === '#')) {
                    throw new \Exception('Debes proporcionar un video o una URL');
                }
                
                $videoTestimonio = new \App\Models\VideoTestimonioCarreraIndividual();
                $videoTestimonio->url = $request->input('url', '#');
            }

            // Actualizar video si se proporciona
            if ($request->hasFile('video')) {
                // Guardar la ruta del video anterior antes de eliminarlo
                $oldVideoPath = $videoTestimonio->video;
                
                // Guardar el nuevo video
                $videoPath = $request->file('video')->store('video_testimonio_carrera_individual', 'public');
                $videoTestimonio->video = $videoPath;
                
                // Eliminar video anterior si existe (después de guardar el nuevo)
                if ($oldVideoPath && Storage::disk('public')->exists($oldVideoPath)) {
                    Storage::disk('public')->delete($oldVideoPath);
                }
            }

            // Actualizar URL siempre (incluso si es solo para actualizar la URL)
            $url = $request->input('url');
            if ($url !== null) {
                $videoTestimonio->url = $url ?: '#';
            }
            
            $videoTestimonio->save();

            // Si viene de AJAX (modal), devolver JSON
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Video actualizado exitosamente.'
                ]);
            }

            return redirect()->route('admin.carreras.multimedia')->with('success', 'Video actualizado exitosamente.');
        } catch (\Exception $e) {
            // Si viene de AJAX, devolver JSON con error
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el video: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->route('admin.carreras.multimedia')->with('error', 'Error al actualizar el video. Por favor, intenta nuevamente.');
        }
    }

    public function updateCertificados(Request $request)
    {
        $request->validate([
            'certificado_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'certificado_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Obtener o crear el único registro - verificar que la tabla existe
        try {
            $certificados = \App\Models\CertificadoCarrera::first();
            
            if (!$certificados) {
                $certificados = new \App\Models\CertificadoCarrera();
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.carreras.multimedia')->with('error', 'Error: La tabla de certificados no existe. Por favor ejecuta las migraciones: php artisan migrate');
        }

        // Actualizar certificado_1 si se proporciona
        if ($request->hasFile('certificado_1')) {
            // Eliminar imagen anterior si existe
            if ($certificados->certificado_1 && Storage::disk('public')->exists($certificados->certificado_1)) {
                Storage::disk('public')->delete($certificados->certificado_1);
            }
            
            $imagenPath = $request->file('certificado_1')->store('certificados_carrera', 'public');
            $certificados->certificado_1 = $imagenPath;
        }

        // Actualizar certificado_2 si se proporciona
        if ($request->hasFile('certificado_2')) {
            // Eliminar imagen anterior si existe
            if ($certificados->certificado_2 && Storage::disk('public')->exists($certificados->certificado_2)) {
                Storage::disk('public')->delete($certificados->certificado_2);
            }
            
            $imagenPath = $request->file('certificado_2')->store('certificados_carrera', 'public');
            $certificados->certificado_2 = $imagenPath;
        }

        $certificados->save();

        return redirect()->route('admin.carreras.multimedia')->with('success', 'Certificados actualizados exitosamente.');
    }

    public function deleteCertificado($numero)
    {
        try {
            $certificados = \App\Models\CertificadoCarrera::first();
        } catch (\Exception $e) {
            return redirect()->route('admin.carreras.multimedia')->with('error', 'Error: La tabla de certificados no existe. Por favor ejecuta las migraciones: php artisan migrate');
        }
        
        if (!$certificados) {
            return redirect()->route('admin.carreras.multimedia')->with('error', 'No hay certificados para eliminar.');
        }

        $campo = 'certificado_' . $numero;
        
        // Eliminar imagen del storage
        if ($certificados->$campo && Storage::disk('public')->exists($certificados->$campo)) {
            Storage::disk('public')->delete($certificados->$campo);
        }
        
        $certificados->$campo = null;
        $certificados->save();

        return redirect()->route('admin.carreras.multimedia')->with('success', 'Certificado eliminado exitosamente.');
    }
}
