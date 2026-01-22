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
use App\Models\PromoBadge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        
        // Obtener cursos destacados ordenados
        $cursosFeatured = Curso::where('featured', true)->ordered()->get();
        
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
        
        // Obtener curso para el Hero por nombre
        // Buscamos "Mecánica y Tecnologías del Automóvil" usando LIKE para ser flexible con acentos/espacios
        $cursoHero = Curso::where('nombre', 'like', '%Mecánica y Tecnologías del Automóvil%')->first();
        
        // Fallback: Si no lo encuentra, buscar por palabras clave principales
        if (!$cursoHero) {
            $cursoHero = Curso::where('nombre', 'like', '%Mecánica%')
                            ->where('nombre', 'like', '%Automóvil%')
                            ->first();
        }
        
        // Fallback final: primer curso destacado
        if (!$cursoHero) {
            $cursoHero = $cursosFeatured->first();
        }

        // Obtener datos de contacto
        $contactosInfo = \App\Models\DatoContacto::info()->get();
        $contactosSocial = \App\Models\DatoContacto::social()->get();

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
            'noticiaDestacada',
            'cursoHero',
            'contactosInfo',
            'contactosSocial'
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

        // Obtener datos de contacto
        $contactosInfo = \App\Models\DatoContacto::info()->get();
        $contactosSocial = \App\Models\DatoContacto::social()->get();
        
        return view('carreras', compact('carreras', 'beneficios', 'partners', 'sedes', 'stickyBar', 'contactosInfo', 'contactosSocial'));
    }

    public function somosItca()
    {
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener sedes disponibles para contacto
        $sedes = Sede::where('disponible', true)
                    ->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE'])
                    ->ordered()
                    ->get();
        
        // Obtener datos de contacto
        $contactosInfo = \App\Models\DatoContacto::info()->get();
        $contactosSocial = \App\Models\DatoContacto::social()->get();
        
        return view('somos-itca', compact('stickyBar', 'partners', 'sedes', 'contactosInfo', 'contactosSocial'));
    }

    public function inscripcion(Curso $curso)
    {
        $startTime = microtime(true);
        
        // Usar caché para los filtros (TTL: 30 minutos)
        // Cachear por curso para que si hay cambios en un curso no afecte a otros
        $cacheKey = 'inscripcion_filtros_' . $curso->id;
        $cacheTTL = 30 * 60; // 30 minutos
        
        $filtrosData = cache()->remember($cacheKey, $cacheTTL, function () use ($curso, &$startTime) {
            logger()->info('Inscripcion: Cache miss - generando datos', ['curso_id' => $curso->id]);
            $queryStart = microtime(true);
            // Obtener sedes disponibles para el footer
            $sedes = Sede::where('disponible', true)
                        ->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE'])
                        ->ordered()
                        ->get();
            
            // Obtener Sticky Bar
            $stickyBar = StickyBar::first();
            
            // Base query optimizada: usar índice en lugar de whereRaw
            $baseQuery = Cursada::where('ver_curso', 'ver');
            
            // Buscar la carrera en cursadas que coincida con el nombre del curso
            $carreraSeleccionada = null;
            // Optimizar: usar groupBy en lugar de distinct() + unique()
            $carrerasDisponibles = (clone $baseQuery)
                ->select('carrera')
                ->whereNotNull('carrera')
                ->where('carrera', '!=', '')
                ->groupBy('carrera')
                ->orderBy('carrera')
                ->pluck('carrera');
            
            // Pre-calcular nombres convertidos una sola vez (optimización)
            $carrerasConNombresConvertidos = $carrerasDisponibles->map(function($carrera) {
                return [
                    'original' => $carrera,
                    'convertido' => mb_strtolower(trim(corregirNombreCarrera($carrera)), 'UTF-8')
                ];
            });
            
            // Buscar coincidencia flexible entre el nombre del curso y las carreras disponibles
            $nombreCurso = mb_strtolower(trim($curso->nombre ?? ''), 'UTF-8');
            foreach ($carrerasConNombresConvertidos as $carreraData) {
                $nombreCarreraConvertido = $carreraData['convertido'];
                
                // Comparación flexible: si el nombre del curso coincide con el nombre convertido de la carrera
                if ($nombreCarreraConvertido === $nombreCurso || 
                    strpos($nombreCarreraConvertido, $nombreCurso) !== false || 
                    strpos($nombreCurso, $nombreCarreraConvertido) !== false) {
                    $carreraSeleccionada = $carreraData['original']; // Guardar el valor original de la BD
                    break;
                }
            }
            
            // Si no hay coincidencia exacta, buscar por palabras clave comunes
            if (!$carreraSeleccionada) {
                $palabrasCurso = explode(' ', $nombreCurso);
                foreach ($carrerasConNombresConvertidos as $carreraData) {
                    $nombreCarreraConvertido = $carreraData['convertido'];
                    foreach ($palabrasCurso as $palabra) {
                        if (strlen($palabra) > 3 && strpos($nombreCarreraConvertido, $palabra) !== false) {
                            $carreraSeleccionada = $carreraData['original']; // Guardar el valor original de la BD
                            break 2;
                        }
                    }
                }
            }
            
            // Cargar TODOS los órdenes guardados en una sola consulta
            $ordenesGuardados = DB::table('filtro_orden')
                ->whereIn('categoria', ['carrera', 'sede', 'modalidad', 'turno', 'dia'])
                ->orderBy('categoria')
                ->orderBy('orden')
                ->get()
                ->groupBy('categoria')
                ->map(function($items) {
                    return $items->pluck('valor')->toArray();
                });
            
            // Función helper para aplicar orden guardado (optimizada)
            $aplicarOrden = function($items, $categoria, $campoValor = null) use ($ordenesGuardados) {
                $ordenGuardado = $ordenesGuardados->get($categoria, []);
                
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
            
            // Obtener datos únicos de cursadas para los filtros (optimizado con índices)
            $carreras = $carrerasDisponibles->map(function($carrera) {
                return (object)['carrera' => $carrera];
            });
            // Aplicar orden guardado a carreras
            $carreras = $aplicarOrden($carreras, 'carrera', 'carrera');
            
            $sedesFiltro = (clone $baseQuery)
                ->select('sede')
                ->whereNotNull('sede')
                ->where('sede', '!=', '')
                ->groupBy('sede')
                ->orderBy('sede')
                ->pluck('sede');
            // Aplicar orden guardado a sedes
            $sedesFiltro = $aplicarOrden($sedesFiltro, 'sede');
            
            // Obtener combinaciones únicas de modalidad + régimen
            $modalidades = (clone $baseQuery)
                ->select('xModalidad', 'Régimen')
                ->whereNotNull('xModalidad')
                ->where('xModalidad', '!=', '')
                ->whereNotNull('Régimen')
                ->where('Régimen', '!=', '')
                ->groupBy('xModalidad', 'Régimen')
                ->orderBy('xModalidad')
                ->orderBy('Régimen')
                ->get()
                ->map(function($item) {
                    // Normalizar "Sempresencial" a "Semipresencial" para consistencia
                    $modalidad = $item->xModalidad;
                    if (stripos($modalidad, 'Sempresencial') !== false) {
                        $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                    }
                    return [
                        'modalidad' => $modalidad,
                        'regimen' => $item->Régimen,
                        'combinacion' => $modalidad . ' - ' . $item->Régimen,
                        'valor' => $modalidad . '|' . $item->Régimen // Separador para el filtrado
                    ];
                });
            // Aplicar orden guardado a modalidades
            $modalidades = $aplicarOrden($modalidades, 'modalidad', 'valor');
            
            $turnos = (clone $baseQuery)
                ->select('xTurno')
                ->whereNotNull('xTurno')
                ->where('xTurno', '!=', '')
                ->groupBy('xTurno')
                ->orderBy('xTurno')
                ->pluck('xTurno');
            // Aplicar orden guardado a turnos
            $turnos = $aplicarOrden($turnos, 'turno');
            
            $dias = (clone $baseQuery)
                ->select('xDias')
                ->whereNotNull('xDias')
                ->where('xDias', '!=', '')
                ->groupBy('xDias')
                ->orderBy('xDias')
                ->pluck('xDias');
            // Aplicar orden guardado a días
            $dias = $aplicarOrden($dias, 'dia');
            
            // NO cargar cursadas aquí - se cargarán vía AJAX para reducir HTML inicial
            // Esto reduce el HTML de 3.5MB a ~100KB en la carga inicial
            
            return compact('sedes', 'stickyBar', 'carreras', 'sedesFiltro', 'modalidades', 'turnos', 'dias', 'carreraSeleccionada');
        });
        
        $cacheTime = microtime(true) - $startTime;
        logger()->info('Inscripcion: Tiempo total con cache', ['time' => round($cacheTime * 1000, 2) . 'ms', 'curso_id' => $curso->id]);
        
        // Obtener el badge activo (no cachear porque puede cambiar)
        $badgeStart = microtime(true);
        $promoBadge = PromoBadge::getActive();
        $badgeTime = microtime(true) - $badgeStart;
        logger()->info('Inscripcion: Badge cargado', ['time' => round($badgeTime * 1000, 2) . 'ms']);
        
        // Extraer datos del caché
        extract($filtrosData);
        
        // No pasar cursadas - se cargarán vía AJAX
        $cursadas = collect();
        
        $viewStart = microtime(true);
        $view = view('inscripcion', compact('curso', 'sedes', 'stickyBar', 'carreras', 'sedesFiltro', 'modalidades', 'turnos', 'dias', 'cursadas', 'carreraSeleccionada', 'promoBadge'));
        $viewTime = microtime(true) - $viewStart;
        logger()->info('Inscripcion: Vista renderizada', ['time' => round($viewTime * 1000, 2) . 'ms', 'cursadas_count' => 0]);
        
        $totalTime = microtime(true) - $startTime;
        logger()->info('Inscripcion: Tiempo TOTAL', ['time' => round($totalTime * 1000, 2) . 'ms']);
        
        return $view;
    }
    
    public function getCursadas(Curso $curso)
    {
        $startTime = microtime(true);
        
        // Obtener el badge activo
        $promoBadge = PromoBadge::getActive();
        
        // Base query optimizada
        $baseQuery = Cursada::where('ver_curso', 'ver');
        
        // Obtener y pre-procesar cursadas
        $cursadas = $baseQuery
            ->select([
                'id', 'ID_Curso', 'carrera', 'sede', 'xModalidad', 'Régimen', 'xTurno', 'xDias',
                'Fecha_Inicio', 'Horario', 'Vacantes', 'Matric_Base', 'Sin_iva_Mat', 'Cta_Web',
                'Sin_IVA_cta', 'Dto_Cuota', 'cuotas', 'Promo_Mat_logo'
            ])
            ->orderBy('Fecha_Inicio')
            ->get()
            ->map(function($cursada) {
                // Pre-calcular todos los valores que se usan en la vista
                $vacantes = intval($cursada->Vacantes ?? 0);
                $sinVacantes = ($vacantes === 0);
                $tieneDescuento = false;
                $dtoCuotaValue = 0;
                if (!empty($cursada->Dto_Cuota) && $cursada->Dto_Cuota !== null) {
                    $dtoCuotaValue = floatval($cursada->Dto_Cuota);
                    $tieneDescuento = (abs($dtoCuotaValue) > 0.01);
                }
                
                // Pre-calcular día completo
                $diaCompleto = convertirDiasCompletos($cursada->xDias ?? '');
                $diaMayusculas = mb_strtoupper($diaCompleto, 'UTF-8');
                
                // Pre-calcular turno
                $turno = $cursada->xTurno ?? '';
                $turnoMayusculas = mb_strtoupper($turno, 'UTF-8');
                
                // Pre-calcular horario formateado
                $horario = $cursada->Horario ?? '';
                $horarioFormateado = '';
                if (!empty($horario)) {
                    $horario = trim($horario);
                    $horario = preg_replace('/\s*hs?\s*/i', '', $horario);
                    $horario = preg_replace('/\s*-\s*/', ' a ', $horario);
                    $horario = preg_replace('/\s+/', ' ', $horario);
                    if (!preg_match('/hs?$/i', $horario)) {
                        $horarioFormateado = $horario . 'hs';
                    } else {
                        $horarioFormateado = $horario;
                    }
                }
                
                // Pre-calcular fecha formateada
                $fechaFormateada = 'N/A';
                $mesNombre = '';
                if ($cursada->Fecha_Inicio) {
                    $fecha = \Carbon\Carbon::parse($cursada->Fecha_Inicio);
                    $meses = [
                        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                    ];
                    $mesNombre = $meses[$fecha->month] ?? $fecha->format('F');
                    $fechaFormateada = $mesNombre . ' ' . $fecha->year;
                }
                
                // Pre-calcular modalidad completa
                $modalidad = $cursada->xModalidad ?? '';
                $regimen = $cursada->Régimen ?? '';
                if (stripos($modalidad, 'Sempresencial') !== false) {
                    $modalidad = str_ireplace('Sempresencial', 'Semipresencial', $modalidad);
                }
                $modalidadCompleta = '';
                if (!empty($modalidad) && !empty($regimen)) {
                    $modalidadCompleta = $modalidad . ' - ' . $regimen;
                } elseif (!empty($modalidad)) {
                    $modalidadCompleta = $modalidad;
                } elseif (!empty($regimen)) {
                    $modalidadCompleta = $regimen;
                } else {
                    $modalidadCompleta = 'N/A';
                }
                
                // Pre-calcular sede
                $sedeCompleta = corregirNombreSede($cursada->sede ?? 'N/A');
                $sedeSimplificada = simplificarNombreSede($sedeCompleta);
                
                // Retornar como array para JSON
                return [
                    'id' => $cursada->id,
                    'ID_Curso' => $cursada->ID_Curso,
                    'carrera' => $cursada->carrera,
                    'sede' => $cursada->sede,
                    'xModalidad' => $cursada->xModalidad,
                    'Régimen' => $cursada->Régimen,
                    'xTurno' => $cursada->xTurno,
                    'xDias' => $cursada->xDias,
                    'Fecha_Inicio' => $cursada->Fecha_Inicio ? (\Carbon\Carbon::parse($cursada->Fecha_Inicio)->format('Y-m-d')) : null,
                    'Horario' => $cursada->Horario,
                    'Vacantes' => $cursada->Vacantes,
                    'Matric_Base' => $cursada->Matric_Base,
                    'Sin_iva_Mat' => $cursada->Sin_iva_Mat,
                    'Cta_Web' => $cursada->Cta_Web,
                    'Sin_IVA_cta' => $cursada->Sin_IVA_cta,
                    'Dto_Cuota' => $cursada->Dto_Cuota,
                    'cuotas' => $cursada->cuotas,
                    'Promo_Mat_logo' => $cursada->Promo_Mat_logo,
                    'pre_calculado' => [
                        'vacantes' => $vacantes,
                        'sinVacantes' => $sinVacantes,
                        'tieneDescuento' => $tieneDescuento,
                        'dtoCuotaValue' => $dtoCuotaValue,
                        'diaCompleto' => $diaCompleto,
                        'diaMayusculas' => $diaMayusculas,
                        'turno' => $turno,
                        'turnoMayusculas' => $turnoMayusculas,
                        'horarioFormateado' => $horarioFormateado,
                        'fechaFormateada' => $fechaFormateada,
                        'mesNombre' => $mesNombre,
                        'modalidadCompleta' => $modalidadCompleta,
                        'sedeCompleta' => $sedeCompleta,
                        'sedeSimplificada' => $sedeSimplificada,
                    ]
                ];
            });
        
        $totalTime = microtime(true) - $startTime;
        logger()->info('Inscripcion AJAX: Cursadas cargadas', ['count' => $cursadas->count(), 'time' => round($totalTime * 1000, 2) . 'ms']);
        
        // Preparar información del badge para el frontend
        $badgeInfo = null;
        if ($promoBadge && $promoBadge->archivo) {
            $badgeInfo = [
                'image_path' => $promoBadge->image_path,
                'archivo' => true
            ];
        }
        
        return response()->json([
            'success' => true,
            'cursadas' => $cursadas->values(),
            'promoBadge' => $badgeInfo
        ]);
    }

    public function storeLead(Request $request)
    {
        // Esta ruta siempre devuelve JSON (solo se llama desde AJAX)
        try {
            $validated = $request->validate([
                'id' => 'nullable|exists:leads,id',
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'dni' => 'required|string|max:8|regex:/^[0-9]{7,8}$/',
                'correo' => 'required|email|max:255',
                'telefono' => 'required|string|max:20', // Prefijo internacional + 10 dígitos
                'cursada_id' => 'required|string|max:50|exists:cursadas,ID_Curso',
                'tipo' => 'nullable|string|max:255',
                'acepto_terminos' => 'boolean',
                'g-recaptcha-response' => 'nullable|string',
            ]);

            // Validar reCAPTCHA v3
            // Solo validamos si NO estamos en entorno local para evitar bloqueos durante desarrollo
            $isLocal = app()->environment('local');
            $recaptchaToken = $request->input('g-recaptcha-response');
            $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');

            if (!$isLocal && $recaptchaSecret && $recaptchaToken) {
                try {
                    $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $recaptchaSecret,
                        'response' => $recaptchaToken,
                        'remoteip' => $request->ip(),
                    ]);

                    $result = $response->json();

                    if (!($result['success'] ?? false) || ($result['score'] ?? 0) < 0.5) {
                         logger()->warning('Lead rechazado por reCAPTCHA bajo score', [
                             'score' => $result['score'] ?? 'N/A',
                             'ip' => $request->ip(),
                             'email' => $request->input('correo')
                         ]);
                         
                         return response()->json([
                             'success' => false, 
                             'message' => 'Validación de seguridad fallida. Por favor recargue la página e intente nuevamente.'
                         ], 422);
                    }
                } catch (\Exception $e) {
                    logger()->error('Error conectando con Google reCAPTCHA', ['error' => $e->getMessage()]);
                }
            }

            // Obtener la cursada por ID_Curso
            $cursada = \App\Models\Cursada::where('ID_Curso', $validated['cursada_id'])->firstOrFail();
            
            // 1. Identificar o Crear al Usuario (Lead)
            $lead = null;
            
            // Prioridad 1: ID explícito (Sesión actual del navegador)
            if (isset($validated['id']) && $validated['id']) {
                $lead = Lead::find($validated['id']);
            }
            
            // Prioridad 2: Búsqueda por Email (Usuario recurrente)
            if (!$lead) {
                $lead = Lead::where('correo', $validated['correo'])->first();
            }
            
            // Datos personales para actualizar/crear
            $personalData = [
                'nombre' => $validated['nombre'],
                'apellido' => $validated['apellido'],
                'dni' => $validated['dni'],
                'correo' => $validated['correo'],
                'telefono' => $validated['telefono'],
            ];

            if ($lead) {
                // Actualizar datos personales
                $lead->update($personalData);
                $message = 'Datos actualizados correctamente';
            } else {
                // Crear nuevo usuario
                $lead = Lead::create($personalData);
                $message = 'Lead guardado correctamente';
            }

            // 2. Registrar el Interés (LeadCursada)
            // Verificar si ya existe una inscripción RECIENTE (últimos 5 min) para la MISMA cursada
            // Esto evita duplicados por "doble click" o refresco
            $lastInscripcion = $lead->cursadas()
                                    ->where('cursada_id', $validated['cursada_id'])
                                    ->latest()
                                    ->first();

            $shouldCreateInscripcion = true;
            // Si es la misma cursada hace menos de 2 min, solo actualizamos (si hiciera falta)
            if ($lastInscripcion && $lastInscripcion->created_at->diffInMinutes(now()) < 2) {
                // Si es la misma cursada hace menos de 5 min, solo actualizamos (si hiciera falta)
                // y no creamos registro nuevo para no spamear
                $lastInscripcion->update([
                    'tipo' => $validated['tipo'] ?? 'Lead',
                    'acepto_terminos' => $validated['acepto_terminos'] ?? false,
                ]);
                $shouldCreateInscripcion = false;
            }

            if ($shouldCreateInscripcion) {
                $lead->cursadas()->create([
                    'cursada_id' => $validated['cursada_id'],
                    'tipo' => $validated['tipo'] ?? 'Lead',
                    'acepto_terminos' => $validated['acepto_terminos'] ?? false,
                ]);

                // Enviar email de notificación (Solo si es una NUEVA intención)
                // CONTROL: Poner SEND_LEAD_EMAILS=false en .env para desactivar
                if (env('SEND_LEAD_EMAILS', true)) {
                    try {
                        // Obtener email desde BD o usar fallback
                        $emailSetting = \App\Models\LeadSetting::where('key_name', 'notification_email')->first();
                        $toEmailString = $emailSetting ? $emailSetting->value : env('MAIL_TO_ADMIN', 'federico.lyonnet@gmail.com');
                        
                        // Parsear múltiples emails separados por ;
                        $toEmails = collect(explode(';', $toEmailString))
                                    ->map(fn($email) => trim($email))
                                    ->filter(fn($email) => !empty($email))
                                    ->toArray();
                        
                        // Mail::to($toEmails)->send(new \App\Mail\LeadNotification($lead, $cursada));

                        // --- INTEGRACION CRM (ADF) ---
                        try {
                            $tecnomService = new \App\Services\TecnomService();
                            $adfData = $tecnomService->generateAdfXml($lead, $cursada);
                            // Enviar a la dirección configurada
                            Mail::to($toEmails)->send(new \App\Mail\DebugAdfMail($adfData));
                        } catch (\Exception $e) {
                            logger()->error('Error enviando ADF Mail: ' . $e->getMessage());
                        }
                        // ------------------------
                    } catch (\Exception $emailException) {
                        logger()->error('Error al enviar email de notificación de lead', [
                            'lead_id' => $lead->id,
                            'error' => $emailException->getMessage(),
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true, 
                'message' => $message,
                'lead_id' => $lead->id
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Error de validación al guardar lead', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            logger()->error('Error general al guardar lead', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Error interno del servidor'], 500);
        }
    }

    public function updateLeadTerms(Request $request, $id)
    {
        try {
            // Validar que el ID sea numérico
            if (!is_numeric($id)) {
                return response()->json(['success' => false, 'message' => 'ID inválido'], 400);
            }

            // Buscar el lead
            $lead = Lead::findOrFail($id);
            
            // Actualizar el estado de acepto_terminos
            // 1. En tabla LeadCursada (La última interacción)
            $lead->cursadas()->latest()->first()?->update([
                'acepto_terminos' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Términos actualizados correctamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Lead no encontrado'], 404);
        } catch (\Exception $e) {
            logger()->error('Error al actualizar términos del lead', [
                'lead_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Error interno del servidor'], 500);
        }
    }
}