<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Cursada;
use App\Models\CareerMailTemplate;
use Illuminate\Http\Request;

class MailPreviewController extends Controller
{
    public function previewAbandonedCart(Request $request)
    {
        // Obtener la carrera/cursada especificada
        $idParam = $request->input('cursada_id');
        $cursada = null;
        $curso = null;
        
        if ($idParam) {
            $curso = \App\Models\Curso::find($idParam);
            
            if ($curso) {
                // Buscamos una cursada real con datos (Igual que en sendTest)
                $cursada = \App\Models\Cursada::where('Cod1', 'LIKE', $curso->Cod1 . '%')
                             ->where('Matric_Base', '>', 0)
                             ->orderBy('id', 'desc')
                             ->first();
                
                // Fallback si la búsqueda estricta falla
                if (!$cursada) {
                    $cursada = \App\Models\Cursada::where('Cod1', 'LIKE', $curso->Cod1 . '%')->first();
                }
            }
        }
        
        // Fallback final: agarrar CUALQUIER cursada
        if (!$cursada) {
            $cursada = \App\Models\Cursada::first();
        }
        
        if (!$cursada) {
            return response('ERROR DEBUG: No se encontró NINGUNA cursada en la base de datos.', 200);
        }

        // Generamos un destinatario al azar (Igual que en sendTest)
        $nombres = ['Juan Carlos', 'Santiago', 'Facundo', 'Ricardo', 'Ignacio', 'Mateo'];
        $apellidos = ['González', 'Fernández', 'Rodríguez', 'Sánchez', 'Gómez', 'Pérez'];
        $nombreAzar = $nombres[array_rand($nombres)];
        $apellidoAzar = $apellidos[array_rand($apellidos)];

        // Dummy Lead (Misma estructura que el real para el mail)
        $lead = new Lead();
        $lead->nombre = $nombreAzar;
        $lead->apellido = $apellidoAzar;
        $lead->correo = 'test@itca.com.ar';
        $lead->telefono = '+54 11 1234-5678';
        
        // Obtener template de mail para esta carrera
        $curso = $cursada->resolveCurso();
        $mailTemplate = $curso ? CareerMailTemplate::where('curso_id', $curso->id)->first() : null;
        $modalidadTipo = $cursada->getModalidadTipo();
        
        // Generar token ficticio para preview
        $token = 'preview-token-' . time();
        
        // Crear un objeto mock para $message que simule el método embed()
        $message = new class {
            public function embed($path) {
                // Para preview, convertir la ruta a una ruta web accesible
                if (strpos($path, 'storage/app/public/') !== false) {
                    $relativePath = str_replace(storage_path('app/public/'), '', $path);
                    return asset('storage/' . $relativePath);
                } elseif (strpos($path, 'public/images/') !== false) {
                    $relativePath = str_replace(public_path(), '', $path);
                    return asset($relativePath);
                }
                return $path;
            }
        };
        
        // Renderizar la vista del mail directamente
        return view('emails.abandoned_cart', [
            'lead' => $lead,
            'cursada' => $cursada,
            'token' => $token,
            'mailTemplate' => $mailTemplate,
            'modalidadTipo' => $modalidadTipo,
            'message' => $message
        ]);
    }
    
    public function listCursadas()
    {
        $cursadas = Cursada::with('curso')
            ->orderBy('carrera')
            ->get()
            ->map(function($cursada) {
                return [
                    'id' => $cursada->ID_Curso,
                    'nombre' => $cursada->carrera . ' - ' . $cursada->sede . ' - ' . $cursada->xModalidad . ' - ' . $cursada->xTurno
                ];
            });
            
        return view('mail-preview.selector', ['cursadas' => $cursadas]);
    }
}
