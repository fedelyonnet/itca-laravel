<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\EnAccion;
use App\Models\FotosCarrera;
use App\Models\Cursada;
use App\Models\PromoBadge;
use App\Models\Descuento;
use App\Models\StickyBar;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    public function index()
    {
        // Redirigir a la vista unificada de gestión de carreras
        return redirect()->route('admin.carreras.test');
    }

    public function store(Request $request)
    {
        try {
            // Si viene de test.blade.php, las imágenes son opcionales
            $vieneDeTest = $request->has('from_test');
            
            $validationRules = [
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'fecha_inicio' => 'required|date',
                'modalidad_online' => 'nullable',
                'modalidad_presencial' => 'nullable',
                'sedes' => 'nullable|array',
                'sedes.*' => 'exists:sedes,id',
                'imagen_show_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'imagen_show_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];
            
            // Si NO viene de test, las imágenes son obligatorias
            if (!$vieneDeTest) {
                $validationRules['ilustracion_desktop'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $validationRules['ilustracion_mobile'] = 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            } else {
                // Si viene de test, las imágenes son opcionales
                $validationRules['ilustracion_desktop'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
                $validationRules['ilustracion_mobile'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
            }
            
            $request->validate($validationRules, [
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
            if ($vieneDeTest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes seleccionar al menos una modalidad.',
                    'errors' => ['modalidades' => ['Debes seleccionar al menos una modalidad.']]
                ], 422);
            }
            return redirect()->back()->withErrors(['modalidades' => 'Debes seleccionar al menos una modalidad.'])->withInput();
        }

        $curso = new Curso();
        $curso->nombre = $request->nombre;
        $curso->descripcion = $request->descripcion;
        $curso->modalidad_online = $request->has('modalidad_online');
        $curso->modalidad_presencial = $request->has('modalidad_presencial');
        $curso->fecha_inicio = $request->fecha_inicio;
        $curso->featured = $request->has('featured') ? ($request->featured == '1' || $request->featured === true) : false;

        // Asignar orden secuencial (máximo orden + 1)
        $maxOrden = Curso::max('orden') ?? 0;
        $curso->orden = $maxOrden + 1;

        // Subir ilustración desktop (si se proporciona)
        if ($request->hasFile('ilustracion_desktop')) {
            try {
                $desktopPath = $request->file('ilustracion_desktop')->store('cursos', 'public');
                $curso->ilustracion_desktop = $desktopPath;
            } catch (\Exception $e) {
                if ($vieneDeTest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la imagen desktop: ' . $e->getMessage(),
                        'errors' => ['ilustracion_desktop' => ['Error al subir la imagen desktop: ' . $e->getMessage()]]
                    ], 422);
                }
                return redirect()->back()->withErrors(['ilustracion_desktop' => 'Error al subir la imagen desktop: ' . $e->getMessage()])->withInput();
            }
        }

        // Subir ilustración mobile (si se proporciona)
        if ($request->hasFile('ilustracion_mobile')) {
            try {
                $mobilePath = $request->file('ilustracion_mobile')->store('cursos', 'public');
                $curso->ilustracion_mobile = $mobilePath;
            } catch (\Exception $e) {
                if ($vieneDeTest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la imagen mobile: ' . $e->getMessage(),
                        'errors' => ['ilustracion_mobile' => ['Error al subir la imagen mobile: ' . $e->getMessage()]]
                    ], 422);
                }
                return redirect()->back()->withErrors(['ilustracion_mobile' => 'Error al subir la imagen mobile: ' . $e->getMessage()])->withInput();
            }
        }

        // Subir imagen show desktop (opcional)
        if ($request->hasFile('imagen_show_desktop')) {
            try {
                $showDesktopPath = $request->file('imagen_show_desktop')->store('cursos', 'public');
                $curso->imagen_show_desktop = $showDesktopPath;
            } catch (\Exception $e) {
                if ($vieneDeTest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la imagen show desktop: ' . $e->getMessage(),
                        'errors' => ['imagen_show_desktop' => ['Error al subir la imagen show desktop: ' . $e->getMessage()]]
                    ], 422);
                }
                return redirect()->back()->withErrors(['imagen_show_desktop' => 'Error al subir la imagen show desktop: ' . $e->getMessage()])->withInput();
            }
        }

        // Subir imagen show mobile (opcional)
        if ($request->hasFile('imagen_show_mobile')) {
            try {
                $showMobilePath = $request->file('imagen_show_mobile')->store('cursos', 'public');
                $curso->imagen_show_mobile = $showMobilePath;
            } catch (\Exception $e) {
                if ($vieneDeTest) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al subir la imagen show mobile: ' . $e->getMessage(),
                        'errors' => ['imagen_show_mobile' => ['Error al subir la imagen show mobile: ' . $e->getMessage()]]
                    ], 422);
                }
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

            // Si viene de test, devolver JSON
            if ($vieneDeTest) {
                return response()->json([
                    'success' => true,
                    'message' => 'Carrera creada exitosamente',
                    'curso_id' => $curso->id
                ]);
            }

            return redirect()->route('admin.carreras.test')->with('success', 'Carrera agregada correctamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si viene de test, devolver JSON con errores
            if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            logger()->error('Error al crear carrera', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Si viene de test, devolver JSON con error
            if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la carrera: ' . $e->getMessage()
                ], 500, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->withErrors(['error' => 'Error al crear la carrera: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        // Redirigir a la vista unificada de gestión de carreras con el curso seleccionado
        return redirect()->route('admin.carreras.test', ['curso_id' => $id]);
    }

    public function update(Request $request, $id)
    {
        try {
            $curso = Curso::findOrFail($id);
            
            // Si solo se están actualizando imágenes (viene from_test y solo hay archivos), no validar campos obligatorios
            $soloImagenes = $request->has('from_test') && 
                           ($request->hasFile('ilustracion_desktop') || $request->hasFile('ilustracion_mobile') || 
                            $request->hasFile('imagen_show_desktop') || $request->hasFile('imagen_show_mobile')) &&
                           !$request->has('nombre') && !$request->has('fecha_inicio');
            
            $request->validate([
                'nombre' => $soloImagenes ? 'nullable' : 'required|string|max:255',
                'descripcion' => 'nullable|string|max:1000',
                'fecha_inicio' => $soloImagenes ? 'nullable' : 'required|date',
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

            // Si solo se están actualizando imágenes, saltar validación de modalidades y actualización de campos básicos
            $soloImagenes = $request->has('from_test') && 
                           ($request->hasFile('ilustracion_desktop') || $request->hasFile('ilustracion_mobile') || 
                            $request->hasFile('imagen_show_desktop') || $request->hasFile('imagen_show_mobile')) &&
                           !$request->has('nombre') && !$request->has('fecha_inicio');
            
            if (!$soloImagenes) {
                // Validar que al menos una modalidad sea seleccionada
                if (!$request->has('modalidad_online') && !$request->has('modalidad_presencial')) {
                    if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Debes seleccionar al menos una modalidad.'
                        ], 422, ['Content-Type' => 'application/json']);
                    }
                    return redirect()->back()->withErrors(['modalidades' => 'Debes seleccionar al menos una modalidad.'])->withInput();
                }

                $curso->nombre = $request->nombre;
                $curso->descripcion = $request->descripcion;
                $curso->modalidad_online = $request->has('modalidad_online');
                $curso->modalidad_presencial = $request->has('modalidad_presencial');
                $curso->fecha_inicio = $request->fecha_inicio;
            }
            
            // Validar featured: máximo 2 carreras destacadas
            // Solo actualizar featured si se envía explícitamente en la request
            if ($request->has('featured')) {
                $wantsFeatured = $request->input('featured') == '1' || $request->input('featured') === true || $request->input('featured') === 'true';
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
            }
            // Si no se envía featured, mantener el valor actual (no hacer nada)

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
            // Solo sincronizar sedes si se envía explícitamente
            // Si solo se están actualizando imágenes, no tocar las sedes
            if ($request->has('sedes')) {
                $sedes = is_array($request->sedes) ? array_filter($request->sedes, function($sede) {
                    return !empty($sede) && $sede !== '';
                }) : [];
                $curso->sedes()->sync($sedes);
            } elseif ($request->has('actualizando_sedes')) {
                // Si viene el flag actualizando_sedes, significa que se está actualizando desde la pestaña de sedes
                // Sincronizar con array vacío si no hay sedes seleccionadas
                $curso->sedes()->sync([]);
            } else {
                // Si no viene de test o es solo actualización de imágenes, mantener las sedes actuales (no hacer nada)
                // Solo sincronizar con array vacío si explícitamente se envía sedes vacío o el flag actualizando_sedes
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
            // Si viene de test, devolver JSON con errores
            if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            logger()->error('Error al actualizar carrera', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Si viene de test, devolver JSON con error
            if ($request->has('from_test') || str_contains($request->header('Referer') ?? '', 'carreras/test')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la carrera: ' . $e->getMessage()
                ], 500, ['Content-Type' => 'application/json']);
            }
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la carrera: ' . $e->getMessage()])->withInput();
        }
    }

    public function toggleFeatured($id)
    {
        $curso = Curso::findOrFail($id);
        $featuredCount = Curso::where('featured', true)->count();
        
        logger()->info('Toggle featured', [
            'curso_id' => $curso->id,
            'curso_nombre' => $curso->nombre,
            'current_featured' => $curso->featured,
            'featured_count' => $featuredCount
        ]);
        
        // Si está intentando seleccionar y ya hay 2 featured, no permitir
        if (!$curso->featured && $featuredCount >= 2) {
            logger()->info('No se puede seleccionar - ya hay 2 destacadas');
            return redirect()->route('admin.carreras')->with('error', 'Solo pueden haber máximo 2 carreras destacadas');
        }
        
        $curso->featured = !$curso->featured;
        $curso->save();

        logger()->info('Featured toggled', [
            'new_featured' => $curso->featured
        ]);

        $message = $curso->featured ? 'Carrera marcada como destacada' : 'Carrera removida de destacados';
        return redirect()->route('admin.carreras')->with('success', $message);
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        
        logger()->info('Intentando eliminar carrera', [
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

        // Reordenar las carreras restantes para que no queden números vacíos ni salteados
        // Mantener el orden relativo que tenían antes del borrado
        $carrerasRestantes = Curso::orderBy('orden', 'asc')->get();
        
        $nuevoOrden = 1;
        foreach ($carrerasRestantes as $carrera) {
            if ($carrera->orden != $nuevoOrden) {
                $carrera->orden = $nuevoOrden;
                $carrera->save();
            }
            $nuevoOrden++;
        }

        logger()->info('Carrera eliminada exitosamente y carreras reordenadas', [
            'curso_id' => $id,
            'carreras_restantes' => $carrerasRestantes->count()
        ]);

        return redirect()->route('admin.carreras.test')->with('success', 'Carrera eliminada correctamente');
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
        
        logger()->info('Moviendo curso', [
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
                logger()->info('No hay curso anterior');
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            logger()->info('Curso anterior encontrado', [
                'curso_anterior_id' => $cursoAnterior->id,
                'curso_anterior_nombre' => $cursoAnterior->nombre,
                'curso_anterior_orden' => $cursoAnterior->orden
            ]);
            
            // Intercambiar órdenes
            $curso->update(['orden' => $cursoAnterior->orden]);
            $cursoAnterior->update(['orden' => $ordenActual]);
            
            logger()->info('Intercambio completado', [
                'curso_nuevo_orden' => $curso->fresh()->orden,
                'curso_anterior_nuevo_orden' => $cursoAnterior->fresh()->orden
            ]);
            
        } else { // down
            // Buscar el curso con orden inmediatamente mayor
            $cursoSiguiente = Curso::where('orden', '>', $ordenActual)
                              ->orderBy('orden', 'asc')
                              ->first();
            
            if (!$cursoSiguiente) {
                logger()->info('No hay curso siguiente');
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            logger()->info('Curso siguiente encontrado', [
                'curso_siguiente_id' => $cursoSiguiente->id,
                'curso_siguiente_nombre' => $cursoSiguiente->nombre,
                'curso_siguiente_orden' => $cursoSiguiente->orden
            ]);
            
            // Intercambiar órdenes
            $curso->update(['orden' => $cursoSiguiente->orden]);
            $cursoSiguiente->update(['orden' => $ordenActual]);
            
            logger()->info('Intercambio completado', [
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

        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();

        return view('carreras.show', compact('curso', 'partners', 'sedes', 'sedesPorZona', 'anios', 'modalidades', 'testimonios', 'videosMobile', 'dudas', 'fotos', 'videoTestimonios', 'certificados', 'stickyBar'));
    }

    public function test()
    {
        try {
            $carreras = Curso::ordered()->get();
            $sedes = \App\Models\Sede::ordered()->get();
            $carreraSeleccionada = null;
            
            // Si hay un curso_id en la request, cargar ese curso con todas sus relaciones
            if (request()->has('curso_id') && request('curso_id')) {
                try {
                    $carreraSeleccionada = Curso::with([
                        'sedes',
                        'anios' => function($query) {
                            $query->orderBy('año');
                        },
                        'anios.unidades' => function($query) {
                            $query->orderByRaw('CAST(SUBSTRING_INDEX(numero, " ", -1) AS UNSIGNED), numero');
                        },
                        'modalidades.tipos' => function($query) {
                            $query->orderBy('orden');
                        }
                    ])->find(request('curso_id'));
                } catch (\Exception $e) {
                    $carreraSeleccionada = null;
                }
            }
            
            // Contar cuántas otras carreras están destacadas (excluyendo la carrera seleccionada)
            // Esto representa cuántas "plazas" quedan disponibles para destacar
            if ($carreraSeleccionada) {
                // Si hay una carrera seleccionada, contar las destacadas excluyendo esa
                $featuredCount = Curso::where('featured', true)
                    ->where('id', '!=', $carreraSeleccionada->id)
                    ->count();
            } else {
                // Si no hay carrera seleccionada, contar todas las destacadas
                $featuredCount = Curso::where('featured', true)->count();
            }
            
            return view('admin.carreras.test', compact('carreras', 'sedes', 'carreraSeleccionada', 'featuredCount'));
        } catch (\Exception $e) {
            return view('admin.carreras.test', [
                'carreras' => collect([]),
                'sedes' => collect([]),
                'carreraSeleccionada' => null,
                'featuredCount' => 0
            ])->with('error', 'Error al cargar la página');
        }
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

    public function importacionCursos()
    {
        // En el admin se muestran TODAS las cursadas (sin filtrar por ver_curso)
        $cursadas = Cursada::orderBy('id', 'desc')->get();
        
        // Calcular estadísticas
        $totalRegistros = Cursada::count();
        $registrosVisibles = Cursada::where('ver_curso', 'ver')->count();
        
        // Obtener badge activo (si existe)
        $promoBadge = PromoBadge::getActive();
        
        return view('admin.carreras.importacion', compact('cursadas', 'totalRegistros', 'registrosVisibles', 'promoBadge'));
    }

    public function ordenarFiltros()
    {
        return view('admin.carreras.ordenar-filtros');
    }

    public function importarPromociones()
    {
        $descuentos = \App\Models\Descuento::orderBy('id', 'desc')->get();
        return view('admin.carreras.importar-promociones', compact('descuentos'));
    }

    public function getFiltrosPorCategoria(Request $request)
    {
        $categoria = $request->input('categoria');
        
        if (!in_array($categoria, ['carrera', 'sede', 'modalidad', 'turno', 'dia'])) {
            return response()->json(['error' => 'Categoría inválida'], 400);
        }

        $filtros = [];
        
        switch ($categoria) {
            case 'carrera':
                $filtros = Cursada::select('carrera')
                    ->where('ver_curso', 'ver')
                    ->whereNotNull('carrera')
                    ->where('carrera', '!=', '')
                    ->distinct()
                    ->orderBy('carrera')
                    ->pluck('carrera')
                    ->unique()
                    ->values()
                    ->map(function($valor) {
                        return [
                            'valor' => $valor,
                            'display' => corregirNombreCarrera($valor)
                        ];
                    });
                break;
                
            case 'sede':
                $filtros = Cursada::select('sede')
                    ->where('ver_curso', 'ver')
                    ->whereNotNull('sede')
                    ->where('sede', '!=', '')
                    ->distinct()
                    ->orderBy('sede')
                    ->pluck('sede')
                    ->unique()
                    ->values()
                    ->map(function($valor) {
                        return [
                            'valor' => $valor,
                            'display' => corregirNombreSede($valor)
                        ];
                    });
                break;
                
            case 'modalidad':
                $filtros = Cursada::select('xModalidad', 'Régimen')
                    ->where('ver_curso', 'ver')
                    ->whereNotNull('xModalidad')
                    ->where('xModalidad', '!=', '')
                    ->whereNotNull('Régimen')
                    ->where('Régimen', '!=', '')
                    ->distinct()
                    ->orderBy('xModalidad')
                    ->orderBy('Régimen')
                    ->get()
                    ->map(function($item) {
                        $modalidad = $item->xModalidad;
                        if (stripos($modalidad, 'Sempresencial') !== false) {
                            $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                        }
                        $combinacion = $modalidad . ' - ' . $item->Régimen;
                        return [
                            'valor' => $item->xModalidad . '|' . $item->Régimen,
                            'display' => $combinacion
                        ];
                    })
                    ->unique(function($item) {
                        return $item['valor'];
                    })
                    ->values();
                break;
                
            case 'turno':
                $filtros = Cursada::select('xTurno')
                    ->where('ver_curso', 'ver')
                    ->whereNotNull('xTurno')
                    ->where('xTurno', '!=', '')
                    ->distinct()
                    ->orderBy('xTurno')
                    ->pluck('xTurno')
                    ->unique()
                    ->values()
                    ->map(function($valor) {
                        return [
                            'valor' => $valor,
                            'display' => $valor
                        ];
                    });
                break;
                
            case 'dia':
                $filtros = Cursada::select('xDias')
                    ->where('ver_curso', 'ver')
                    ->whereNotNull('xDias')
                    ->where('xDias', '!=', '')
                    ->distinct()
                    ->orderBy('xDias')
                    ->pluck('xDias')
                    ->unique()
                    ->values()
                    ->map(function($valor) {
                        return [
                            'valor' => $valor,
                            'display' => convertirDiasCompletos($valor)
                        ];
                    });
                break;
        }

        // Obtener el orden guardado desde la base de datos
        $ordenGuardado = DB::table('filtro_orden')
            ->where('categoria', $categoria)
            ->orderBy('orden')
            ->pluck('valor')
            ->toArray();

        // Aplicar el orden guardado si existe
        if (!empty($ordenGuardado)) {
            // Separar filtros ordenados y nuevos
            $filtrosOrdenados = collect();
            $filtrosNuevos = collect();
            
            foreach ($filtros as $filtro) {
                $posicion = array_search($filtro['valor'], $ordenGuardado);
                if ($posicion !== false) {
                    // Filtro con orden guardado
                    $filtrosOrdenados->push([
                        'filtro' => $filtro,
                        'orden' => $posicion
                    ]);
                } else {
                    // Filtro nuevo (no está en el orden guardado)
                    $filtrosNuevos->push($filtro);
                }
            }
            
            // Ordenar los filtros con orden guardado
            $filtrosOrdenados = $filtrosOrdenados->sortBy('orden')->pluck('filtro');
            
            // Combinar: primero los ordenados, luego los nuevos (ordenados por display)
            $filtros = $filtrosOrdenados->merge(
                $filtrosNuevos->sortBy('display')->values()
            )->values();
        }

        return response()->json([
            'filtros' => $filtros,
            'categoria' => $categoria
        ]);
    }

    public function guardarOrdenFiltros(Request $request)
    {
        $categoria = $request->input('categoria');
        $orden = $request->input('orden'); // Array de valores en el orden deseado

        if (!in_array($categoria, ['carrera', 'sede', 'modalidad', 'turno', 'dia'])) {
            return response()->json(['error' => 'Categoría inválida'], 400);
        }

        if (!is_array($orden)) {
            return response()->json(['error' => 'El orden debe ser un array'], 400);
        }

        // Eliminar el orden anterior de esta categoría
        DB::table('filtro_orden')->where('categoria', $categoria)->delete();

        // Insertar el nuevo orden
        foreach ($orden as $index => $valor) {
            DB::table('filtro_orden')->insert([
                'categoria' => $categoria,
                'valor' => $valor,
                'orden' => $index,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Orden guardado correctamente']);
    }


    public function storeImportacion(Request $request)
    {
        try {
            logger()->info('storeImportacion llamado', [
                'has_file' => $request->hasFile('archivo_excel'),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson()
            ]);

            $request->validate([
                'archivo_excel' => 'required|mimes:xlsx,xls|max:10240', // 10MB máximo
            ]);

            $file = $request->file('archivo_excel');
            
            logger()->info('Archivo recibido', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo Excel debe tener al menos una fila de datos además del encabezado.'
                ], 400, ['Content-Type' => 'application/json']);
            }

            // Obtener los headers de la primera fila
            $headers = array_map('trim', $rows[0]);
            
            // Log para debug: mostrar todos los headers encontrados
            logger()->info('Headers encontrados en Excel: ' . json_encode($headers));
            
            // Mapeo de headers del Excel a campos de la base de datos
            // Headers del Excel (con espacios/puntos) → Nombres de BD (con guiones bajos)
            // Basado en BASE CARRERAS AL 17-12.xlsx
            $headerMap = [
                'ID_Curso' => 'ID_Curso',
                'carrera' => 'carrera',
                'Cod1' => 'Cod1',
                'Fecha Inicio' => 'Fecha_Inicio',
                'xDias' => 'xDias',
                'xModalidad' => 'xModalidad',
                'Régimen' => 'Régimen',
                'xTurno' => 'xTurno',
                'Horario' => 'Horario',
                'Vacantes' => 'Vacantes',
                'Matric Base' => 'Matric_Base',
                'Sin iva Mat' => 'Sin_iva_Mat', // Nueva columna
                'Cta Web' => 'Cta_Web',
                'Sin IVA cta' => 'Sin_IVA_cta', // Nueva columna
                'Dto Cuota' => 'Dto_Cuota',
                'cuotas' => 'cuotas', // Nombre principal
                'cant. cuotas' => 'cuotas', // Variante alternativa
                'sede' => 'sede',
                'Promo Mat-logo-' => 'Promo_Mat_logo',
                'ver curso' => 'ver_curso', // Nueva columna
            ];

            // Crear un índice de columnas basado en los headers
            $columnIndex = [];
            foreach ($headerMap as $excelHeader => $dbField) {
                // Buscar el header de forma case-insensitive y sin espacios extra
                $index = false;
                foreach ($headers as $idx => $header) {
                    $headerNormalized = trim($header);
                    $excelHeaderNormalized = trim($excelHeader);
                    if (strcasecmp($headerNormalized, $excelHeaderNormalized) === 0) {
                        $index = $idx;
                        break;
                    }
                }
                if ($index !== false) {
                    $columnIndex[$dbField] = $index;
                } else {
                    // Log para debug si no se encuentra el header
                    logger()->warning("Header no encontrado en Excel: '{$excelHeader}' (buscando en: " . implode(', ', $headers) . ")");
                }
            }

            // Verificar que se encontraron los headers esenciales
            // Columnas requeridas para el correcto funcionamiento
            $requiredFields = [
                'ID_Curso',
                'carrera',
                'Cod1',
                'Fecha_Inicio',
                'xDias',
                'xModalidad',
                'Régimen',
                'xTurno',
                'Horario',
                'Vacantes',
                'Matric_Base',
                'Sin_iva_Mat',
                'Cta_Web',
                'Sin_IVA_cta',
                'Dto_Cuota',
                'cuotas',
                'sede',
                'Promo_Mat_logo',
                'ver_curso'
            ];
            
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (!isset($columnIndex[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                // Mapear nombres de campos a nombres exactos del Excel
                // Para cada campo faltante, verificar cuál nombre del Excel realmente falta
                $missingFieldsNames = [];
                foreach ($missingFields as $field) {
                    // Buscar en headerMap todos los nombres de Excel que mapean a este campo
                    $excelNames = [];
                    foreach ($headerMap as $excelHeader => $dbField) {
                        if ($dbField === $field) {
                            $excelNames[] = $excelHeader;
                        }
                    }
                    
                    // Verificar cuál de los nombres posibles realmente falta en los headers
                    $foundName = null;
                    foreach ($excelNames as $excelName) {
                        foreach ($headers as $header) {
                            if (strcasecmp(trim($header), trim($excelName)) === 0) {
                                $foundName = $excelName;
                                break 2; // Se encontró, salir de ambos loops
                            }
                        }
                    }
                    
                    // Si no se encontró ninguno, usar el primero de la lista (más común)
                    // Si se encontró alguno, significa que el campo no debería estar en missingFields
                    // pero por seguridad, si está aquí, usamos el primero
                    if (empty($excelNames)) {
                        $missingFieldsNames[] = $field; // Fallback
                    } else {
                        // Usar el primero de la lista (el más común/esperado)
                        $missingFieldsNames[] = $excelNames[0];
                    }
                }
                
                logger()->error('Columnas faltantes detectadas: ' . implode(', ', $missingFieldsNames));
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error: La estructura del archivo Excel no es válida. Faltan las siguientes columnas requeridas: ' . implode(', ', $missingFieldsNames) . '. Por favor, verifica que el archivo tenga todas las columnas necesarias.'
                ], 400, ['Content-Type' => 'application/json']);
            }

            // Borrar todos los registros existentes antes de importar
            $deletedCount = Cursada::count();
            Cursada::truncate();
            
            logger()->info("Se eliminaron {$deletedCount} registros existentes antes de la importación");

            $imported = 0;
            $errors = [];

            // Procesar cada fila de datos (empezando desde la fila 2, índice 1)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Saltar filas vacías
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $data = [];
                    
                    // Mapear cada campo
                    foreach ($columnIndex as $dbField => $colIndex) {
                        // Para columnas que pueden tener fórmulas, obtener el valor calculado
                        $cellValue = null;
                        if (in_array($dbField, ['Sin_iva_Mat', 'Sin_IVA_cta'])) {
                            try {
                                // Convertir índice de columna a letra (A=0, B=1, etc.)
                                $columnLetter = Coordinate::stringFromColumnIndex($colIndex + 1);
                                $rowNumber = $i + 1; // +1 porque las filas empiezan en 1, no en 0
                                $cellCoordinate = $columnLetter . $rowNumber;
                                $cell = $worksheet->getCell($cellCoordinate);
                                $cellValue = $cell->getCalculatedValue();
                            } catch (\Exception $e) {
                                // Si falla, usar el valor del array
                                $cellValue = isset($row[$colIndex]) ? $row[$colIndex] : '';
                            }
                        } else {
                            $cellValue = isset($row[$colIndex]) ? $row[$colIndex] : '';
                        }
                        
                        $value = is_null($cellValue) ? '' : (is_numeric($cellValue) ? $cellValue : trim($cellValue));
                        
                        // Conversiones específicas por tipo de campo
                        switch ($dbField) {
                            case 'Vacantes':
                                $data[$dbField] = !empty($value) ? (int)$value : 0;
                                break;
                                
                            case 'Matric_Base':
                            case 'Cta_Web':
                            case 'Sin_iva_Mat': // Nueva columna (puede tener fórmula)
                            case 'Sin_IVA_cta': // Nueva columna (puede tener fórmula)
                                // Para campos monetarios: convertir a decimal
                                if (!empty($value) || $value === '0' || $value === 0) {
                                    // Si ya es numérico (viene de fórmula calculada o es número directo)
                                    if (is_numeric($value)) {
                                        $data[$dbField] = (float)$value;
                                    } else {
                                        // Es string, procesar formato
                                        $value = trim($value);
                                        
                                        // Si tiene coma, puede ser separador de miles o decimal
                                        if (strpos($value, ',') !== false && strpos($value, '.') === false) {
                                            $parts = explode(',', $value);
                                            if (strlen($parts[0]) > 3) {
                                                // Separador de miles: eliminar coma
                                                $value = str_replace(',', '', $value);
                                            } else {
                                                // Decimal: cambiar coma por punto
                                                $value = str_replace(',', '.', $value);
                                            }
                                        } elseif (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                                            // Tiene ambos: coma es decimal, punto es miles
                                            $value = str_replace('.', '', $value);
                                            $value = str_replace(',', '.', $value);
                                        }
                                        
                                        // Eliminar cualquier punto restante si no hay coma (separador de miles)
                                        if (strpos($value, '.') !== false && strpos($value, ',') === false) {
                                            $parts = explode('.', $value);
                                            if (count($parts) == 2 && strlen($parts[1]) <= 2) {
                                                // Es decimal, mantener
                                            } else {
                                                // Es separador de miles, eliminar
                                                $value = str_replace('.', '', $value);
                                            }
                                        }
                                        
                                        $data[$dbField] = is_numeric($value) ? (float)$value : null;
                                    }
                                } else {
                                    $data[$dbField] = null;
                                }
                                break;
                                
                            case 'Dto_Cuota':
                                // Para porcentaje: convertir a decimal (ej: 10.5 para 10.5%)
                                if (!empty($value)) {
                                    $value = trim($value);
                                    
                                    // Si tiene el símbolo %, eliminarlo
                                    $value = str_replace('%', '', $value);
                                    
                                    // Si tiene coma, cambiar por punto (formato decimal)
                                    $value = str_replace(',', '.', $value);
                                    
                                    $data[$dbField] = (float)$value;
                                } else {
                                    $data[$dbField] = 0;
                                }
                                break;
                                
                            case 'Fecha_Inicio':
                                if (!empty($value)) {
                                    try {
                                        if (is_numeric($value)) {
                                            // Si es un número de Excel (días desde 1900)
                                            $data[$dbField] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                                        } else {
                                            $value = trim($value);
                                            
                                            // Intentar formato dd/mm/yyyy primero (formato español)
                                            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
                                                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                                                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                                $year = $matches[3];
                                                $data[$dbField] = "{$year}-{$month}-{$day}";
                                            } else {
                                                // Intentar otros formatos con Carbon
                                                $data[$dbField] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        try {
                                            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
                                                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                                                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                                $year = $matches[3];
                                                $data[$dbField] = "{$year}-{$month}-{$day}";
                                            } else {
                                                throw $e;
                                            }
                                        } catch (\Exception $e2) {
                                            $data[$dbField] = null;
                                        }
                                    }
                                } else {
                                    $data[$dbField] = null;
                                }
                                break;
                                
                            case 'Régimen':
                                // Régimen es texto
                                $data[$dbField] = !empty($value) ? trim($value) : null;
                                break;
                                
                            case 'cuotas':
                                // Cuotas es un número entero
                                if (!empty($value)) {
                                    $data[$dbField] = is_numeric($value) ? (int)$value : null;
                                } else {
                                    $data[$dbField] = null;
                                }
                                break;
                                
                            case 'ver_curso':
                                // Normalizar ver_curso: trim + lowercase para usar índices eficientemente
                                $data[$dbField] = !empty($value) ? mb_strtolower(trim($value), 'UTF-8') : null;
                                break;
                                
                            default:
                                $data[$dbField] = $value;
                                break;
                        }
                    }

                    // Validar que los campos requeridos no estén vacíos
                    if (empty($data['ID_Curso'])) {
                        $errors[] = "Fila " . ($i + 1) . ": ID_Curso es requerido";
                        continue;
                    }

                    // Crear el registro (ya no usamos updateOrCreate porque borramos todo antes)
                    Cursada::create($data);
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Fila " . ($i + 1) . ": " . $e->getMessage();
                }
            }

            // Invalidar caché de filtros de inscripción (todos los cursos)
            cache()->flush(); // Flush completo porque no sabemos qué cursos están afectados
            
            $message = "Se reemplazó el contenido de la base de datos. Se importaron {$imported} cursadas correctamente.";
            if ($deletedCount > 0) {
                $message .= " Se eliminaron {$deletedCount} registros anteriores.";
            }
            if (!empty($errors)) {
                $message .= " Errores: " . count($errors) . " filas con problemas.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ], 200, ['Content-Type' => 'application/json']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: La estructura del archivo Excel no es válida. ' . implode(', ', $e->errors()['archivo_excel'] ?? ['Archivo inválido']) . '. Por favor, verifica que el archivo sea un Excel válido (.xlsx o .xls) y que no exceda 10MB.'
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            logger()->error('Error en storeImportacion: ' . $e->getMessage());
            logger()->error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: La estructura del archivo Excel no es válida. ' . ($e->getMessage() ?: 'Error al procesar el archivo') . '. Por favor, verifica que el archivo tenga el formato correcto.'
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    public function storeFoto(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'descripcion' => 'nullable|string|max:500',
        ]);

        if (!$request->hasFile('imagen')) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'La imagen es obligatoria'
                ], 422, ['Content-Type' => 'application/json']);
            }
            return back()->withErrors(['imagen' => 'La imagen es obligatoria']);
        }
        
        $imagenPath = $request->file('imagen')->store('fotos_carrera', 'public');

        $foto = FotosCarrera::create([
            'imagen' => $imagenPath,
            'descripcion' => $request->descripcion,
        ]);

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Foto creada exitosamente.',
                'foto' => [
                    'id' => $foto->id,
                    'imagen' => $foto->imagen,
                    'descripcion' => $foto->descripcion,
                ]
            ], 200, ['Content-Type' => 'application/json']);
        }

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
        
        try {
            $request->validate([
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'descripcion' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors()
                ], 422, ['Content-Type' => 'application/json']);
            }
            throw $e;
        }

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior
            if ($foto->imagen && Storage::disk('public')->exists($foto->imagen)) {
                Storage::disk('public')->delete($foto->imagen);
            }
            
            $imagenPath = $request->file('imagen')->store('fotos_carrera', 'public');
            $foto->imagen = $imagenPath;
        }

        if ($request->has('descripcion')) {
            $foto->descripcion = $request->descripcion;
        }
        
        $foto->save();

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Foto actualizada exitosamente.',
                'foto' => [
                    'id' => $foto->id,
                    'imagen' => $foto->imagen,
                    'descripcion' => $foto->descripcion,
                ]
            ], 200, ['Content-Type' => 'application/json']);
        }

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

    public function storeImportacionPromociones(Request $request)
    {
        try {
            logger()->info('storeImportacionPromociones llamado', [
                'has_file' => $request->hasFile('archivo_excel'),
                'is_ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson()
            ]);

            $request->validate([
                'archivo_excel' => 'required|mimes:xlsx,xls|max:10240', // 10MB máximo
            ]);

            $file = $request->file('archivo_excel');
            
            logger()->info('Archivo recibido', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo Excel debe tener al menos una fila de datos además del encabezado.'
                ], 400, ['Content-Type' => 'application/json']);
            }

            // Obtener los headers de la primera fila
            $headers = array_map('trim', $rows[0]);
            
            logger()->info('Headers encontrados en Excel: ' . json_encode($headers));
            
            // Mapeo de headers del Excel a campos de la base de datos
            $headerMap = [
                'Codigo_Promocion' => 'Codigo_Promocion',
                'Promocion_Descripcion' => 'Promocion_Descripcion',
                'Porcentaje' => 'Porcentaje',
                'codigo web' => 'codigo_web',
                'codigo_web' => 'codigo_web', // Mantener por compatibilidad
            ];

            // Crear un índice de columnas basado en los headers
            $columnIndex = [];
            foreach ($headerMap as $excelHeader => $dbField) {
                $index = false;
                foreach ($headers as $idx => $header) {
                    $headerNormalized = trim($header);
                    $excelHeaderNormalized = trim($excelHeader);
                    if (strcasecmp($headerNormalized, $excelHeaderNormalized) === 0) {
                        $index = $idx;
                        break;
                    }
                }
                if ($index !== false) {
                    $columnIndex[$dbField] = $index;
                } else {
                    logger()->warning("Header no encontrado en Excel: '{$excelHeader}' (buscando en: " . implode(', ', $headers) . ")");
                }
            }

            // Borrar todos los registros existentes antes de importar
            $deletedCount = Descuento::count();
            Descuento::truncate();
            
            logger()->info("Se eliminaron {$deletedCount} registros existentes antes de la importación");

            $imported = 0;
            $errors = [];

            // Procesar cada fila de datos (empezando desde la fila 2, índice 1)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                
                // Saltar filas vacías
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $data = [];
                    
                    // Mapear cada campo
                    foreach ($columnIndex as $dbField => $colIndex) {
                        $cellValue = isset($row[$colIndex]) ? $row[$colIndex] : '';
                        
                        // Conversiones específicas por tipo de campo
                        switch ($dbField) {
                            case 'Porcentaje':
                                // Porcentaje puede venir como "20%" (texto) o como número
                                if (!empty($cellValue)) {
                                    // Convertir a string y limpiar
                                    $porcentajeStr = trim((string)$cellValue);
                                    // Remover el símbolo % y espacios
                                    $porcentajeStr = str_replace(['%', ' '], '', $porcentajeStr);
                                    // Convertir coma decimal a punto si existe
                                    $porcentajeStr = str_replace(',', '.', $porcentajeStr);
                                    // Intentar convertir a float
                                    $porcentajeFloat = is_numeric($porcentajeStr) ? (float)$porcentajeStr : null;
                                    $data[$dbField] = $porcentajeFloat;
                                } else {
                                    $data[$dbField] = null;
                                }
                                break;
                                
                            default:
                                // Los demás campos son texto
                                $value = is_null($cellValue) ? '' : (is_numeric($cellValue) ? $cellValue : trim((string)$cellValue));
                                $data[$dbField] = !empty($value) ? trim((string)$value) : null;
                                break;
                        }
                    }
                    
                    // Crear el registro
                    Descuento::create($data);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = [
                        'fila' => $i + 1,
                        'mensaje' => $e->getMessage()
                    ];
                    logger()->warning("Error en fila " . ($i + 1) . ": " . $e->getMessage());
                }
            }

            $message = "Se importaron {$imported} promociones correctamente.";
            if (count($errors) > 0) {
                $message .= " Se encontraron " . count($errors) . " errores.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'errors' => $errors
            ], 200, ['Content-Type' => 'application/json']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', $e->errors()['archivo_excel'] ?? ['Archivo inválido'])
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            logger()->error('Error en storeImportacionPromociones: ' . $e->getMessage());
            logger()->error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo: ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }

    public function buscarDescuento(Request $request)
    {
        try {
            $request->validate([
                'codigo' => 'required|string',
            ]);

            $codigo = trim($request->input('codigo'));
            
            // Búsqueda case-sensitive exacta usando BINARY para MySQL
            $descuento = Descuento::whereRaw('BINARY codigo_web = ?', [$codigo])
                ->orWhereRaw('BINARY Codigo_Promocion = ?', [$codigo])
                ->first();

            if (!$descuento) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código de descuento no encontrado'
                ], 200); // Usar 200 en lugar de 404 para evitar error en consola
            }

            return response()->json([
                'success' => true,
                'descuento' => [
                    'codigo' => $descuento->codigo_web ?? $descuento->Codigo_Promocion,
                    'descripcion' => $descuento->Promocion_Descripcion,
                    'porcentaje' => $descuento->Porcentaje
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'El código es requerido'
            ], 200);
        } catch (\Exception $e) {
            logger()->error('Error en buscarDescuento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar el código de descuento. Por favor, intentá nuevamente.'
            ], 200);
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar el código de descuento'
            ], 500);
        }
    }
}
