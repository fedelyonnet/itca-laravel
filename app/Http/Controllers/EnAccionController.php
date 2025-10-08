<?php

namespace App\Http\Controllers;

use App\Models\EnAccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EnAccionController extends Controller
{
    public function index()
    {
        $videos = EnAccion::orderBy('created_at', 'desc')->get();
        return view('admin.en-accion', compact('videos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'version' => 'required|in:mob,desktop',
            'url' => 'required|url',
            'video' => 'required|file|mimes:mp4,avi,mov,wmv|max:102400' // 100MB max
        ]);

        $data = $request->only(['version', 'url']);
        
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
            $data['video'] = $videoPath;
        }

        EnAccion::create($data);

        return redirect()->route('admin.en-accion')->with('success', 'Video agregado exitosamente');
    }

    public function getData($id)
    {
        $enAccion = EnAccion::findOrFail($id);
        return response()->json([
            'id' => $enAccion->id,
            'version' => $enAccion->version,
            'url' => $enAccion->url,
            'video' => $enAccion->video
        ]);
    }

    public function update(Request $request, $id)
    {
        $enAccion = EnAccion::findOrFail($id);
        $request->validate([
            'version' => 'required|in:mob,desktop',
            'url' => 'required|url',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400'
        ]);

        $data = $request->only(['version', 'url']);
        
        if ($request->hasFile('video')) {
            // Eliminar video anterior si existe
            if ($enAccion->video && Storage::disk('public')->exists($enAccion->video)) {
                Storage::disk('public')->delete($enAccion->video);
            }
            
            $videoPath = $request->file('video')->store('videos', 'public');
            $data['video'] = $videoPath;
        }

        $enAccion->update($data);

        return redirect()->route('admin.en-accion')->with('success', 'Video actualizado exitosamente');
    }

    public function mover(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:en_accion,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $enAccion = EnAccion::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $enAccion->created_at;
        
        if ($direccion === 'up') {
            // Buscar el video con fecha inmediatamente anterior
            $videoAnterior = EnAccion::where('created_at', '<', $ordenActual)
                                ->orderBy('created_at', 'desc')
                                ->first();
            
            if (!$videoAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar fechas (simulando cambio de orden)
            $tempFecha = $enAccion->created_at;
            $enAccion->update(['created_at' => $videoAnterior->created_at]);
            $videoAnterior->update(['created_at' => $tempFecha]);
            
        } else { // down
            // Buscar el video con fecha inmediatamente posterior
            $videoSiguiente = EnAccion::where('created_at', '>', $ordenActual)
                                  ->orderBy('created_at', 'asc')
                                  ->first();
            
            if (!$videoSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar fechas (simulando cambio de orden)
            $tempFecha = $enAccion->created_at;
            $enAccion->update(['created_at' => $videoSiguiente->created_at]);
            $videoSiguiente->update(['created_at' => $tempFecha]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Video movido correctamente'
        ]);
    }

    public function destroy($id)
    {
        $enAccion = EnAccion::findOrFail($id);
        
        // Eliminar archivo de video si existe
        if ($enAccion->video && Storage::disk('public')->exists($enAccion->video)) {
            Storage::disk('public')->delete($enAccion->video);
        }

        $enAccion->delete();

        return redirect()->route('admin.en-accion')->with('success', 'Video eliminado exitosamente');
    }
}