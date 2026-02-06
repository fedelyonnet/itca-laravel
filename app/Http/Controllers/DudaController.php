<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Duda;

class DudaController extends Controller
{
    public function index()
    {
        $dudas = Duda::all();
        return view('admin.dudas', compact('dudas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pregunta' => 'required|string|max:500',
            'respuesta' => 'required|string',
        ]);

        Duda::create([
            'pregunta' => $request->pregunta,
            'respuesta' => $request->respuesta,
        ]);

        return redirect()->route('admin.dudas')->with('success', 'FAQ creada exitosamente.');
    }

    public function getData($id)
    {
        $duda = Duda::findOrFail($id);
        
        return response()->json([
            'id' => $duda->id,
            'pregunta' => $duda->pregunta,
            'respuesta' => $duda->respuesta,
        ]);
    }

    public function update(Request $request, $id)
    {
        $duda = Duda::findOrFail($id);
        
        $request->validate([
            'pregunta' => 'required|string|max:500',
            'respuesta' => 'required|string',
        ]);

        $duda->update([
            'pregunta' => $request->pregunta,
            'respuesta' => $request->respuesta,
        ]);

        return redirect()->route('admin.dudas')->with('success', 'FAQ actualizada exitosamente.');
    }

    public function destroy($id)
    {
        $duda = Duda::findOrFail($id);
        $duda->delete();

        return redirect()->route('admin.dudas')->with('success', 'FAQ eliminada exitosamente.');
    }

    public function moverDuda(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:dudas,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $duda = Duda::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $duda->orden;
        
        if ($direccion === 'up') {
            $dudaAnterior = Duda::where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$dudaAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            $duda->update(['orden' => $dudaAnterior->orden]);
            $dudaAnterior->update(['orden' => $ordenActual]);
            
        } else {
            $dudaSiguiente = Duda::where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$dudaSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            $duda->update(['orden' => $dudaSiguiente->orden]);
            $dudaSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'FAQ movida correctamente'
        ]);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'orden' => 'required|array',
            'orden.*' => 'required|exists:dudas,id'
        ]);

        try {
            foreach ($request->orden as $index => $id) {
                Duda::where('id', $id)->update(['orden' => $index + 1]);
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
