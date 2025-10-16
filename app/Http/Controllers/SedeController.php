<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;
use Illuminate\Support\Facades\Storage;

class SedeController extends Controller
{
    public function index()
    {
        $sedes = Sede::ordered()->get();
        return view('admin.sedes', compact('sedes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo_titulo' => 'required|in:normal,dos_lineas',
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|max:50',
            'link_google_maps' => 'nullable|url|max:500',
            'link_whatsapp' => 'nullable|url|max:500',
            'imagen_desktop' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_mobile' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'disponible' => 'boolean',
        ]);

        // Verificar que los archivos existen antes de procesarlos
        if (!$request->hasFile('imagen_desktop') || !$request->hasFile('imagen_mobile')) {
            return back()->withErrors(['imagen' => 'Las imágenes son obligatorias']);
        }
        
        $desktopPath = $request->file('imagen_desktop')->store('sedes', 'public');
        $mobilePath = $request->file('imagen_mobile')->store('sedes', 'public');

        Sede::create([
            'nombre' => $request->nombre,
            'tipo_titulo' => $request->tipo_titulo,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'link_google_maps' => $request->link_google_maps,
            'link_whatsapp' => $request->link_whatsapp,
            'imagen_desktop' => $desktopPath,
            'imagen_mobile' => $mobilePath,
            'disponible' => $request->has('disponible'),
        ]);

        return redirect()->route('admin.sedes')->with('success', 'Sede creada exitosamente.');
    }

    public function getData($id)
    {
        $sede = Sede::findOrFail($id);
        
        return response()->json([
            'id' => $sede->id,
            'nombre' => $sede->nombre,
            'tipo_titulo' => $sede->tipo_titulo,
            'direccion' => $sede->direccion,
            'telefono' => $sede->telefono,
            'link_google_maps' => $sede->link_google_maps,
            'link_whatsapp' => $sede->link_whatsapp,
            'disponible' => $sede->disponible,
        ]);
    }

    public function update(Request $request, $id)
    {
        $sede = Sede::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo_titulo' => 'required|in:normal,dos_lineas',
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|max:50',
            'link_google_maps' => 'nullable|url|max:500',
            'link_whatsapp' => 'nullable|url|max:500',
            'imagen_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imagen_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'disponible' => 'boolean',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'tipo_titulo' => $request->tipo_titulo,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'link_google_maps' => $request->link_google_maps,
            'link_whatsapp' => $request->link_whatsapp,
            'disponible' => $request->has('disponible'),
        ];

        if ($request->hasFile('imagen_desktop')) {
            // Delete old image
            if ($sede->imagen_desktop) {
                Storage::disk('public')->delete($sede->imagen_desktop);
            }
            $data['imagen_desktop'] = $request->file('imagen_desktop')->store('sedes', 'public');
        }

        if ($request->hasFile('imagen_mobile')) {
            // Delete old image
            if ($sede->imagen_mobile) {
                Storage::disk('public')->delete($sede->imagen_mobile);
            }
            $data['imagen_mobile'] = $request->file('imagen_mobile')->store('sedes', 'public');
        }

        $sede->update($data);

        return redirect()->route('admin.sedes')->with('success', 'Sede actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $sede = Sede::findOrFail($id);
        
        // Delete images
        if ($sede->imagen_desktop) {
            Storage::disk('public')->delete($sede->imagen_desktop);
        }
        if ($sede->imagen_mobile) {
            Storage::disk('public')->delete($sede->imagen_mobile);
        }

        $sede->delete();

        return redirect()->route('admin.sedes')->with('success', 'Sede eliminada exitosamente.');
    }

    public function moverSede(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:sedes,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $sede = Sede::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $sede->orden;
        
        if ($direccion === 'up') {
            // Buscar la sede con orden inmediatamente menor
            $sedeAnterior = Sede::where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$sedeAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $sede->update(['orden' => $sedeAnterior->orden]);
            $sedeAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar la sede con orden inmediatamente mayor
            $sedeSiguiente = Sede::where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$sedeSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $sede->update(['orden' => $sedeSiguiente->orden]);
            $sedeSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Sede movida correctamente'
        ]);
    }
}
