<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonio;
use Illuminate\Support\Facades\Storage;

class TestimonioController extends Controller
{
    public function index()
    {
        $testimonios = Testimonio::ordered()->get();
        return view('admin.testimonios', compact('testimonios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'sede' => 'required|string|max:100',
            'carrera' => 'required|string|max:200',
            'texto' => 'required|string|min:50|max:500',
            'tiempo_testimonio' => 'required|integer|min:1|max:24',
            'calificacion' => 'required|integer|min:1|max:5',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icono' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'visible' => 'boolean',
        ]);

        // Verificar que los archivos existen antes de procesarlos
        if (!$request->hasFile('avatar') || !$request->hasFile('icono')) {
            return back()->withErrors(['imagen' => 'Las imágenes son obligatorias']);
        }
        
        $avatarPath = $request->file('avatar')->store('testimonios', 'public');
        $iconoPath = $request->file('icono')->store('testimonios', 'public');

        Testimonio::create([
            'nombre' => $request->nombre,
            'sede' => $request->sede,
            'carrera' => $request->carrera,
            'texto' => $request->texto,
            'tiempo_testimonio' => $request->tiempo_testimonio,
            'calificacion' => $request->calificacion,
            'avatar' => $avatarPath,
            'icono' => $iconoPath,
            'visible' => false, // No visible por defecto
        ]);

        return redirect()->route('admin.testimonios')->with('success', 'Testimonio creado exitosamente.');
    }

    public function getData($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        
        return response()->json([
            'id' => $testimonio->id,
            'nombre' => $testimonio->nombre,
            'sede' => $testimonio->sede,
            'carrera' => $testimonio->carrera,
            'texto' => $testimonio->texto,
            'tiempo_testimonio' => $testimonio->tiempo_testimonio,
            'calificacion' => $testimonio->calificacion,
            'visible' => $testimonio->visible,
        ]);
    }

    public function update(Request $request, $id)
    {
        $testimonio = Testimonio::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'sede' => 'required|string|max:100',
            'carrera' => 'required|string|max:200',
            'texto' => 'required|string|min:50|max:500',
            'tiempo_testimonio' => 'required|integer|min:1|max:24',
            'calificacion' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'icono' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'visible' => 'boolean',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'sede' => $request->sede,
            'carrera' => $request->carrera,
            'texto' => $request->texto,
            'tiempo_testimonio' => $request->tiempo_testimonio,
            'calificacion' => $request->calificacion,
        ];

        if ($request->hasFile('avatar')) {
            // Delete old image
            if ($testimonio->avatar) {
                Storage::disk('public')->delete($testimonio->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('testimonios', 'public');
        }

        if ($request->hasFile('icono')) {
            // Delete old image
            if ($testimonio->icono) {
                Storage::disk('public')->delete($testimonio->icono);
            }
            $data['icono'] = $request->file('icono')->store('testimonios', 'public');
        }

        $testimonio->update($data);

        return redirect()->route('admin.testimonios')->with('success', 'Testimonio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        
        // Delete images
        if ($testimonio->avatar) {
            Storage::disk('public')->delete($testimonio->avatar);
        }
        if ($testimonio->icono) {
            Storage::disk('public')->delete($testimonio->icono);
        }

        $testimonio->delete();

        return redirect()->route('admin.testimonios')->with('success', 'Testimonio eliminado exitosamente.');
    }

    public function toggleVisibility($id)
    {
        $testimonio = Testimonio::findOrFail($id);
        $visibleCount = Testimonio::where('visible', true)->count();
        
        // Si está intentando mostrar y ya hay 8 visibles, no permitir
        if (!$testimonio->visible && $visibleCount >= 8) {
            return response()->json([
                'success' => false,
                'message' => 'Solo pueden haber máximo 8 testimonios visibles'
            ], 422);
        }
        
        $testimonio->visible = !$testimonio->visible;
        $testimonio->save();

        return response()->json([
            'success' => true,
            'message' => $testimonio->visible ? 'Testimonio mostrado' : 'Testimonio ocultado'
        ]);
    }


    public function moverTestimonio(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:testimonios,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $testimonio = Testimonio::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $testimonio->orden;
        
        if ($direccion === 'up') {
            // Buscar el testimonio con orden inmediatamente menor
            $testimonioAnterior = Testimonio::where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$testimonioAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $testimonio->update(['orden' => $testimonioAnterior->orden]);
            $testimonioAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar el testimonio con orden inmediatamente mayor
            $testimonioSiguiente = Testimonio::where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$testimonioSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $testimonio->update(['orden' => $testimonioSiguiente->orden]);
            $testimonioSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Testimonio movido correctamente'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'required|exists:testimonios,id'
        ]);

        try {
            foreach ($request->orden as $index => $id) {
                Testimonio::where('id', $id)->update(['orden' => $index + 1]);
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
