<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\CareerMailTemplate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CareerMailTemplateController extends Controller
{
    /**
     * Muestra la vista principal.
     */
    public function index()
    {
        $cursos = Curso::orderBy('nombre')->get();
        return view('admin.leads.mail_templates', compact('cursos'));
    }

    /**
     * Obtiene los datos de un template para un curso específico (AJAX).
     */
    public function show($cursoId)
    {
        $template = CareerMailTemplate::where('curso_id', $cursoId)->first();
        
        // Si no existe, devolvemos un objeto vacío estructurado igual
        if (!$template) {
            return response()->json([
                'exists' => false,
                'data' => []
            ]);
        }

        return response()->json([
            'exists' => true,
            'data' => $template
        ]);
    }

    /**
     * Guarda o actualiza un template.
     */
    public function update(Request $request, $cursoId)
    {
        // Validación básica
        $request->validate([
            'header_image' => 'nullable|image|max:2048', // 2MB
            // Agregar resto de validaciones si es necesario
        ]);

        try {
            Log::info("Iniciando actualización de template para curso: $cursoId");

            $template = CareerMailTemplate::firstOrNew(['curso_id' => $cursoId]);
            
            // Lista de campos de imagen
            $imageFields = [
                'header_image', 'main_illustration', 'certificate_image',
                'benefit_1_image', 'benefit_2_image', 'benefit_3_image', 'benefit_4_image',
                'utn_banner_image', 'partners_image',
                'illustration_2', 'illustration_3', 'illustration_4', 'illustration_5',
                'bottom_image',
                'syllabus_year_1', 'syllabus_year_2', 'syllabus_year_3' // PDFs
            ];

            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    // Borrar anterior
                    if ($template->$field && Storage::disk('public')->exists($template->$field)) {
                        Storage::disk('public')->delete($template->$field);
                        
                        // Si es una imagen con slices, borrar también los slices
                        if (in_array($field, ['main_illustration', 'header_image', 'bottom_image'])) {
                            $maxSlices = ($field === 'header_image') ? 16 : 4;
                            for ($i = 1; $i <= $maxSlices; $i++) {
                                $slicePath = str_replace('.', "_slice_{$i}.", $template->$field);
                                if (Storage::disk('public')->exists($slicePath)) {
                                    Storage::disk('public')->delete($slicePath);
                                }
                            }
                        }
                    }

                    // Guardar nuevo
                    $folder = str_contains($field, 'syllabus') ? 'mails/pdfs' : 'mails/images';
                    $path = $request->file($field)->store($folder, 'public');
                    $template->$field = $path;
                    
                    // Si es main_illustration, header_image o bottom_image, cortarla en partes
                    if (in_array($field, ['main_illustration', 'header_image', 'bottom_image'])) {
                        $fullPath = storage_path('app/public/' . $path);
                        
                        // Intervention Image v3 API
                        $manager = new \Intervention\Image\ImageManager(
                            new \Intervention\Image\Drivers\Gd\Driver()
                        );
                        $img = $manager->read($fullPath);
                        
                        $width = $img->width();
                        $height = $img->height();
                        
                        // Header: 16 slices (8x2), otros: 4 slices (2x2)
                        if ($field === 'header_image') {
                            $sliceWidth = (int) floor($width / 8);
                            $sliceHeight = (int) floor($height / 2);
                            
                            $slices = [];
                            for ($row = 0; $row < 2; $row++) {
                                for ($col = 0; $col < 8; $col++) {
                                    $num = ($row * 8) + $col + 1;
                                    $slices[$num] = [
                                        'x' => $sliceWidth * $col,
                                        'y' => $sliceHeight * $row
                                    ];
                                }
                            }
                            
                            foreach ($slices as $num => $coords) {
                                $slice = $manager->read($fullPath);
                                $slice->crop($sliceWidth, $sliceHeight, $coords['x'], $coords['y']);
                                
                                $slicePath = str_replace('.', "_slice_{$num}.", $path);
                                $slice->save(storage_path('app/public/' . $slicePath));
                            }
                        } else {
                            // main_illustration y bottom_image: 4 slices (2x2)
                            $halfWidth = (int) floor($width / 2);
                            $halfHeight = (int) floor($height / 2);
                            
                            $slices = [
                                1 => ['x' => 0, 'y' => 0],
                                2 => ['x' => $halfWidth, 'y' => 0],
                                3 => ['x' => 0, 'y' => $halfHeight],
                                4 => ['x' => $halfWidth, 'y' => $halfHeight],
                            ];
                            
                            foreach ($slices as $num => $coords) {
                                $slice = $manager->read($fullPath);
                                $slice->crop($halfWidth, $halfHeight, $coords['x'], $coords['y']);
                                
                                $slicePath = str_replace('.', "_slice_{$num}.", $path);
                                $slice->save(storage_path('app/public/' . $slicePath));
                            }
                        }
                    }
                }
            }

            // Guardar URLs de beneficios
            $urlFields = ['benefit_1_url', 'benefit_2_url', 'benefit_3_url', 'benefit_4_url'];
            foreach ($urlFields as $field) {
                if ($request->has($field)) {
                    $template->$field = $request->input($field);
                }
            }

            $template->save();

            return response()->json([
                'success' => true, 
                'message' => 'Guardado correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error("Error guardando template: " . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Envía un mail de prueba.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'cursada_id' => 'required|exists:cursos,id'
        ]);

        try {
            $curso = \App\Models\Curso::find($request->cursada_id);
            
            // 1. Buscamos una cursada real que tenga datos de precios para que el mail no se vea vacío
            // Priorizamos las que tengan matricula y cuota > 0
            $cursada = \App\Models\Cursada::where('Cod1', 'LIKE', $curso->Cod1 . '%')
                                         ->where('Matric_Base', '>', 0)
                                         ->orderBy('id', 'desc')
                                         ->first();
            
            // Fallback si la búsqueda estricta falla
            if (!$cursada) {
                $cursada = \App\Models\Cursada::where('Cod1', 'LIKE', $curso->Cod1 . '%')->first();
            }

            if (!$cursada) {
                return response()->json(['success' => false, 'message' => 'No se encontraron cursadas con datos para la carrera ' . $curso->nombre], 404);
            }

            // 2. Generamos un destinatario al azar (nombres bien argentinos)
            $nombres = ['Juan Carlos', 'Santiago', 'Facundo', 'Ricardo', 'Ignacio', 'Mateo'];
            $apellidos = ['González', 'Fernández', 'Rodríguez', 'Sánchez', 'Gómez', 'Pérez'];
            $nombreAzar = $nombres[array_rand($nombres)];
            $apellidoAzar = $apellidos[array_rand($apellidos)];

            // 3. Dummy Lead (sin guardar en DB)
            $lead = new \App\Models\Lead();
            $lead->nombre = $nombreAzar;
            $lead->apellido = $apellidoAzar;
            $lead->correo = $request->email;
            
            $token = 'TEST-TEMPLATE-' . time();

            // 4. Enviar Mail
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\AbandonedCartMail($lead, $cursada, $token));

            return response()->json([
                'success' => true, 
                'message' => "Email enviado con éxito a {$request->email} usando datos de la carrera '{$cursada->carrera}' y el nombre ficticio '{$nombreAzar} {$apellidoAzar}'."
            ]);

        } catch (\Exception $e) {
            Log::error("Error enviando test mail: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
