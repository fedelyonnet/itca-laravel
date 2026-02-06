<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficio;
use Illuminate\Support\Facades\Storage;

class BeneficioController extends Controller
{
    public function index()
    {
        $beneficios = Beneficio::all();
        return view('admin.beneficios', compact('beneficios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:500',
            'tipo_accion' => 'required|in:none,button,link',
            'url' => 'nullable|url|max:500',
            'imagen_desktop' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_mobile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'titulo_linea1' => 'required|string|max:100',
            'titulo_linea2' => 'required|string|max:100',
            'texto_boton' => 'nullable|string|max:100',
        ]);

        // Validar campos según el tipo de acción
        if ($request->tipo_accion === 'button' && !$request->url) {
            return back()->withErrors(['url' => 'La URL es obligatoria cuando se selecciona "Botón"']);
        }
        
        if ($request->tipo_accion === 'link' && (!$request->url || !$request->texto_boton)) {
            return back()->withErrors(['url' => 'La URL y el texto del enlace son obligatorios cuando se selecciona "Link"']);
        }

        // Verificar que los archivos existen antes de procesarlos
        if (!$request->hasFile('imagen_desktop') || !$request->hasFile('imagen_mobile')) {
            return back()->withErrors(['imagen' => 'Las imágenes son obligatorias']);
        }
        
        $desktopPath = $request->file('imagen_desktop')->store('beneficios', 'public');
        $mobilePath = $request->file('imagen_mobile')->store('beneficios', 'public');

        // Asignar mostrar_bottom automáticamente según tipo de acción
        $mostrarBottom = $request->tipo_accion !== 'none';
        
        // Siempre usar 'normal' para tipo_titulo
        $tipoTitulo = 'normal';

        Beneficio::create([
            'descripcion' => $request->descripcion,
            'link' => $request->tipo_accion === 'link' ? $request->texto_boton : null,
            'url' => in_array($request->tipo_accion, ['button', 'link']) ? $request->url : null,
            'imagen_desktop' => $desktopPath,
            'imagen_mobile' => $mobilePath,
            
            // Campos parametrizables
            'titulo_linea1' => $request->titulo_linea1,
            'titulo_linea2' => $request->titulo_linea2,
            'tipo_accion' => $request->tipo_accion,
            'texto_boton' => $request->texto_boton,
            'mostrar_bottom' => $mostrarBottom,
            'tipo_titulo' => $tipoTitulo,
        ]);

        return redirect()->route('admin.beneficios')->with('success', 'Beneficio creado exitosamente.');
    }

    public function show(Beneficio $beneficio)
    {
        // Redirigir a la vista principal de beneficios
        // La funcionalidad de mostrar/editar se maneja desde la vista principal con modales
        return redirect()->route('admin.beneficios');
    }

    public function edit($id)
    {
        // Redirigir a la vista principal de beneficios
        // La funcionalidad de editar se maneja desde la vista principal con modales
        return redirect()->route('admin.beneficios');
    }

    public function getData($id)
    {
        $beneficio = Beneficio::findOrFail($id);
        
        return response()->json([
            'id' => $beneficio->id,
            'descripcion' => $beneficio->descripcion,
            'link' => $beneficio->link,
            'url' => $beneficio->url,
            'link_type' => $beneficio->url ? ($beneficio->link ? 'link' : 'button') : 'none',
            
            // Campos parametrizables
            'titulo_linea1' => $beneficio->titulo_linea1,
            'titulo_linea2' => $beneficio->titulo_linea2,
            'tipo_accion' => $beneficio->tipo_accion,
            'texto_boton' => $beneficio->texto_boton,
            'alineacion_bottom' => $beneficio->alineacion_bottom,
            'mostrar_bottom' => $beneficio->mostrar_bottom,
            'tipo_titulo' => $beneficio->tipo_titulo,
        ]);
    }

    public function update(Request $request, $id)
    {
        $beneficio = Beneficio::findOrFail($id);
        
        $request->validate([
            'descripcion' => 'required|string|max:500',
            'tipo_accion' => 'required|in:none,button,link',
            'url' => 'nullable|url|max:500',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'titulo_linea1' => 'required|string|max:100',
            'titulo_linea2' => 'required|string|max:100',
            'texto_boton' => 'nullable|string|max:100',
        ]);

        // Validar campos según el tipo de acción
        if ($request->tipo_accion === 'button' && !$request->url) {
            return back()->withErrors(['url' => 'La URL es obligatoria cuando se selecciona "Botón"']);
        }
        
        if ($request->tipo_accion === 'link' && (!$request->url || !$request->texto_boton)) {
            return back()->withErrors(['url' => 'La URL y el texto del enlace son obligatorios cuando se selecciona "Link"']);
        }

        // Asignar mostrar_bottom automáticamente según tipo de acción
        $mostrarBottom = $request->tipo_accion !== 'none';
        
        // Siempre usar 'normal' para tipo_titulo
        $tipoTitulo = 'normal';

        $data = [
            'descripcion' => $request->descripcion,
            'link' => $request->tipo_accion === 'link' ? $request->texto_boton : null,
            'url' => in_array($request->tipo_accion, ['button', 'link']) ? $request->url : null,
            
            // Campos parametrizables
            'titulo_linea1' => $request->titulo_linea1,
            'titulo_linea2' => $request->titulo_linea2,
            'tipo_accion' => $request->tipo_accion,
            'texto_boton' => $request->texto_boton,
            'mostrar_bottom' => $mostrarBottom,
            'tipo_titulo' => $tipoTitulo,
        ];

        if ($request->hasFile('imagen_desktop')) {
            // Delete old image
            if ($beneficio->imagen_desktop) {
                Storage::disk('public')->delete($beneficio->imagen_desktop);
            }
            $data['imagen_desktop'] = $request->file('imagen_desktop')->store('beneficios', 'public');
        }

        if ($request->hasFile('imagen_mobile')) {
            // Delete old image
            if ($beneficio->imagen_mobile) {
                Storage::disk('public')->delete($beneficio->imagen_mobile);
            }
            $data['imagen_mobile'] = $request->file('imagen_mobile')->store('beneficios', 'public');
        }

        $beneficio->update($data);

        return redirect()->route('admin.beneficios')->with('success', 'Beneficio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $beneficio = Beneficio::findOrFail($id);
        
        // Delete images
        if ($beneficio->imagen_desktop) {
            Storage::disk('public')->delete($beneficio->imagen_desktop);
        }
        if ($beneficio->imagen_mobile) {
            Storage::disk('public')->delete($beneficio->imagen_mobile);
        }

        $beneficio->delete();

        return redirect()->route('admin.beneficios')->with('success', 'Beneficio eliminado exitosamente.');
    }

    public function updateOrden(Request $request, Beneficio $beneficio)
    {
        $request->validate([
            'orden' => 'required|integer|min:1|max:100'
        ]);
        
        $beneficio->update(['orden' => $request->orden]);
        
        return response()->json([
            'success' => true,
            'message' => 'Orden actualizado correctamente'
        ]);
    }

    public function moverBeneficio(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:beneficios,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $beneficio = Beneficio::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $beneficio->orden;
        
        if ($direccion === 'up') {
            // Buscar el beneficio con orden inmediatamente menor
            $beneficioAnterior = Beneficio::where('orden', '<', $ordenActual)
                                        ->orderBy('orden', 'desc')
                                        ->first();
            
            if (!$beneficioAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $beneficio->update(['orden' => $beneficioAnterior->orden]);
            $beneficioAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar el beneficio con orden inmediatamente mayor
            $beneficioSiguiente = Beneficio::where('orden', '>', $ordenActual)
                                          ->orderBy('orden', 'asc')
                                          ->first();
            
            if (!$beneficioSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $beneficio->update(['orden' => $beneficioSiguiente->orden]);
            $beneficioSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Beneficio movido correctamente'
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'required|exists:beneficios,id'
        ]);

        try {
            foreach ($request->orden as $index => $id) {
                Beneficio::where('id', $id)->update(['orden' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el orden: ' . $e->getMessage()
            ], 500);
        }
    }
}
