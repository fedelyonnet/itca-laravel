<?php

namespace App\Http\Controllers;

use App\Models\PromoBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoBadgeController extends Controller
{
    /**
     * Subir o actualizar el badge promocional
     */
    public function store(Request $request)
    {
        $request->validate([
            'archivo' => 'required|image|mimes:jpeg,jpg,png|max:2048', // 2MB máximo
        ]);

        try {
            // Obtener o crear el badge (solo puede haber uno)
            $promoBadge = PromoBadge::first();
            
            // Si ya existe, eliminar el archivo anterior
            if ($promoBadge && $promoBadge->archivo) {
                $rutaAnterior = public_path('images/badges-promo/' . $promoBadge->archivo);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            } else {
                $promoBadge = new PromoBadge();
            }

            // Verificar que el directorio existe y tiene permisos
            $directorio = public_path('images/badges-promo');
            if (!is_dir($directorio)) {
                if (!mkdir($directorio, 0777, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pudo crear el directorio de badges'
                    ], 500);
                }
                // Asegurar permisos después de crear
                chmod($directorio, 0777);
            }
            
            // Verificar y corregir permisos de escritura
            if (!is_writable($directorio)) {
                // Intentar corregir permisos
                @chmod($directorio, 0777);
                
                // Verificar nuevamente
                if (!is_writable($directorio)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El directorio no tiene permisos de escritura. Verifica los permisos del directorio public/images/badges-promo'
                    ], 500);
                }
            }

            // Guardar el nuevo archivo
            $file = $request->file('archivo');
            $nombreArchivo = 'promo-badge-' . time() . '.' . $file->getClientOriginalExtension();
            
            try {
                $file->move($directorio, $nombreArchivo);
            } catch (\Exception $e) {
                \Log::error('Error al mover archivo badge: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el archivo: ' . $e->getMessage()
                ], 500);
            }

            $promoBadge->archivo = $nombreArchivo;
            $promoBadge->save();

            return response()->json([
                'success' => true,
                'message' => 'Badge cargado correctamente',
                'archivo' => $nombreArchivo
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error al cargar badge: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el badge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar el badge promocional
     */
    public function destroy()
    {
        try {
            $promoBadge = PromoBadge::first();
            
            if ($promoBadge && $promoBadge->archivo) {
                // Eliminar el archivo
                $rutaArchivo = public_path('images/badges-promo/' . $promoBadge->archivo);
                if (file_exists($rutaArchivo)) {
                    unlink($rutaArchivo);
                }
                
                // Eliminar el registro
                $promoBadge->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Badge eliminado correctamente'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar badge: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el badge: ' . $e->getMessage()
            ], 500);
        }
    }
}
