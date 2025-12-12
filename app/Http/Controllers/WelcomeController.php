<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Curso;
use App\Models\Beneficio;
use App\Models\Sede;
use App\Models\Testimonio;
use App\Models\Partner;
use App\Models\EnAccion;
use App\Models\StickyBar;
use App\Models\Noticia;
use App\Models\Cursada;
use App\Models\Lead;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener todos los registros del hero
        $heroes = Hero::all();
        
        // Organizar por versión y tipo
        $desktopImg1 = $heroes->where('version', 'desktop')->where('type', 'img1')->first();
        $desktopImg2 = $heroes->where('version', 'desktop')->where('type', 'img2')->first();
        $desktopVideo = $heroes->where('version', 'desktop')->where('type', 'video')->first();
        $mobileImg1 = $heroes->where('version', 'mobile')->where('type', 'img1')->first();
        $mobileImg2 = $heroes->where('version', 'mobile')->where('type', 'img2')->first();
        $mobileVideo = $heroes->where('version', 'mobile')->where('type', 'video')->first();
        
        // Obtener cursos destacados
        $cursosFeatured = Curso::where('featured', true)->get();
        
        // Obtener beneficios ordenados
        $beneficios = Beneficio::ordered()->get();
        
        // Obtener todas las sedes ordenadas (disponibles y no disponibles)
        $sedes = Sede::ordered()->get();
        
        // Obtener testimonios visibles ordenados (máximo 8)
        $testimonios = Testimonio::where('visible', true)
                                ->ordered()
                                ->limit(8)
                                ->get();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener videos de En Acción desktop para el grid fijo
        $video1 = EnAccion::where('version', 'desktop')->where('url', 'like', '%instagram.com%')->first(); // Lugar 1 - Instagram
        $video3 = EnAccion::where('version', 'desktop')->where('url', 'like', '%tiktok.com%')->first(); // Lugar 3 - TikTok  
        $video5 = EnAccion::where('version', 'desktop')->where(function($query) {
            $query->where('url', 'like', '%youtube.com%')->orWhere('url', 'like', '%youtu.be%');
        })->first(); // Lugar 5 - YouTube
        
        // Obtener videos de En Acción tablet para el carousel
        $videosTablet = EnAccion::where('version', 'mob')->orderBy('created_at', 'desc')->get();
        
        // Obtener videos de En Acción mobile para el carousel
        $videosMobile = EnAccion::where('version', 'mob')->orderBy('created_at', 'desc')->get();
        
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();
        
        // Obtener noticia destacada
        $noticiaDestacada = Noticia::where('destacada', true)
                                  ->where('visible', true)
                                  ->first();
        
        return view('welcome', compact(
            'desktopImg1', 'desktopImg2', 'desktopVideo',
            'mobileImg1', 'mobileImg2', 'mobileVideo',
            'cursosFeatured',
            'beneficios',
            'sedes',
            'testimonios',
            'partners',
            'video1', 'video3', 'video5', 'videosTablet', 'videosMobile',
            'stickyBar',
            'noticiaDestacada'
        ));
    }

    public function carreras()
    {
        // Obtener todas las carreras ordenadas
        $carreras = Curso::ordered()->get();
        
        // Obtener beneficios ordenados
        $beneficios = Beneficio::ordered()->get();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener sedes disponibles para el footer
        $sedes = Sede::where('disponible', true)
                    ->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE'])
                    ->ordered()
                    ->get();
        
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();
        
        return view('carreras', compact('carreras', 'beneficios', 'partners', 'sedes', 'stickyBar'));
    }

    public function inscripcion(Curso $curso)
    {
        // Obtener sedes disponibles para el footer
        $sedes = Sede::where('disponible', true)
                    ->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE'])
                    ->ordered()
                    ->get();
        
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();
        
        // Buscar la carrera en cursadas que coincida con el nombre del curso
        $carreraSeleccionada = null;
        $carrerasDisponibles = Cursada::select('carrera')
            ->whereNotNull('carrera')
            ->where('carrera', '!=', '')
            ->distinct()
            ->orderBy('carrera')
            ->pluck('carrera')
            ->unique()
            ->values();
        
        // Buscar coincidencia flexible entre el nombre del curso y las carreras disponibles
        $nombreCurso = strtolower(trim($curso->nombre ?? ''));
        foreach ($carrerasDisponibles as $carrera) {
            $nombreCarrera = strtolower(trim($carrera));
            // Comparación flexible: si el nombre del curso contiene palabras clave de la carrera o viceversa
            if ($nombreCarrera === $nombreCurso || 
                strpos($nombreCarrera, $nombreCurso) !== false || 
                strpos($nombreCurso, $nombreCarrera) !== false) {
                $carreraSeleccionada = $carrera;
                break;
            }
        }
        
        // Si no hay coincidencia exacta, buscar por palabras clave comunes
        if (!$carreraSeleccionada) {
            $palabrasCurso = explode(' ', $nombreCurso);
            foreach ($carrerasDisponibles as $carrera) {
                $nombreCarrera = strtolower(trim($carrera));
                foreach ($palabrasCurso as $palabra) {
                    if (strlen($palabra) > 3 && strpos($nombreCarrera, $palabra) !== false) {
                        $carreraSeleccionada = $carrera;
                        break 2;
                    }
                }
            }
        }
        
        // Función helper para aplicar orden guardado
        $aplicarOrden = function($items, $categoria, $campoValor = null) {
            $ordenGuardado = \DB::table('filtro_orden')
                ->where('categoria', $categoria)
                ->orderBy('orden')
                ->pluck('valor')
                ->toArray();
            
            if (empty($ordenGuardado)) {
                return $items;
            }
            
            // Separar items ordenados y nuevos
            $itemsOrdenados = collect();
            $itemsNuevos = collect();
            
            foreach ($items as $item) {
                // Obtener el valor según el tipo de item
                if (is_object($item) && $campoValor) {
                    $valor = $item->{$campoValor} ?? null;
                } elseif (is_array($item) && $campoValor) {
                    $valor = $item[$campoValor] ?? null;
                } else {
                    $valor = $item; // Para strings directos
                }
                
                $posicion = array_search($valor, $ordenGuardado);
                if ($posicion !== false) {
                    // Item con orden guardado
                    $itemsOrdenados->push([
                        'item' => $item,
                        'orden' => $posicion
                    ]);
                } else {
                    // Item nuevo (no está en el orden guardado)
                    $itemsNuevos->push($item);
                }
            }
            
            // Ordenar los items con orden guardado
            $itemsOrdenados = $itemsOrdenados->sortBy('orden')->pluck('item');
            
            // Combinar: primero los ordenados, luego los nuevos (ordenados alfabéticamente)
            return $itemsOrdenados->merge($itemsNuevos->sort()->values())->values();
        };
        
        // Obtener datos únicos de cursadas para los filtros
        $carreras = $carrerasDisponibles->map(function($carrera) {
            return (object)['carrera' => $carrera];
        });
        // Aplicar orden guardado a carreras
        $carreras = $aplicarOrden($carreras, 'carrera', 'carrera');
        
        $sedesFiltro = Cursada::select('sede')
            ->distinct()
            ->whereNotNull('sede')
            ->where('sede', '!=', '')
            ->orderBy('sede')
            ->pluck('sede')
            ->unique()
            ->values();
        // Aplicar orden guardado a sedes
        $sedesFiltro = $aplicarOrden($sedesFiltro, 'sede');
        
        // Obtener combinaciones únicas de modalidad + régimen
        $modalidades = Cursada::select('xModalidad', 'Régimen')
            ->whereNotNull('xModalidad')
            ->where('xModalidad', '!=', '')
            ->whereNotNull('Régimen')
            ->where('Régimen', '!=', '')
            ->distinct()
            ->orderBy('xModalidad')
            ->orderBy('Régimen')
            ->get()
            ->map(function($item) {
                return [
                    'modalidad' => $item->xModalidad,
                    'regimen' => $item->Régimen,
                    'combinacion' => $item->xModalidad . ' - ' . $item->Régimen,
                    'valor' => $item->xModalidad . '|' . $item->Régimen // Separador para el filtrado
                ];
            })
            ->unique(function($item) {
                return $item['modalidad'] . '|' . $item['regimen'];
            })
            ->values();
        // Aplicar orden guardado a modalidades
        $modalidades = $aplicarOrden($modalidades, 'modalidad', 'valor');
        
        $turnos = Cursada::select('xTurno')
            ->distinct()
            ->whereNotNull('xTurno')
            ->where('xTurno', '!=', '')
            ->orderBy('xTurno')
            ->pluck('xTurno')
            ->unique()
            ->values();
        // Aplicar orden guardado a turnos
        $turnos = $aplicarOrden($turnos, 'turno');
        
        $dias = Cursada::select('xDias')
            ->distinct()
            ->whereNotNull('xDias')
            ->where('xDias', '!=', '')
            ->orderBy('xDias')
            ->pluck('xDias')
            ->unique()
            ->values();
        // Aplicar orden guardado a días
        $dias = $aplicarOrden($dias, 'dia');
        
        // Obtener TODAS las cursadas (no filtrar por carrera, el filtrado se hace en el frontend)
        $cursadas = Cursada::orderBy('Fecha_Inicio')
            ->get();
        
        return view('inscripcion', compact('curso', 'sedes', 'stickyBar', 'carreras', 'sedesFiltro', 'modalidades', 'turnos', 'dias', 'cursadas', 'carreraSeleccionada'));
    }

    public function storeLead(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'dni' => 'required|string|max:8|regex:/^[0-9]{7,8}$/',
                'correo' => 'required|email|max:255',
                'telefono' => 'required|string|max:12|regex:/^[0-9]{12}$/',
            ]);

            Lead::create($validated);

            return response()->json(['success' => true, 'message' => 'Lead guardado correctamente']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los datos: ' . $e->getMessage()
            ], 500);
        }
    }
}