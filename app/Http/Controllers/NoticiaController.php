<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Http\Requests\StoreNoticiaRequest;
use App\Http\Requests\UpdateNoticiaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $noticias = Noticia::orderBy('orden')->get();
        return view('admin.noticias', compact('noticias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.noticias.create');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Noticia $noticia)
    {
        return view('admin.noticias.edit', compact('noticia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoticiaRequest $request)
    {
        try {
            \Log::info('Store noticia request data:', $request->all());
            
            $data = $request->validated();
            \Log::info('Validated data:', $data);
            
            // Convertir fecha manualmente
            if (isset($data['fecha_publicacion'])) {
                $fecha = \DateTime::createFromFormat('d/m/Y', $data['fecha_publicacion']);
                if ($fecha) {
                    $data['fecha_publicacion'] = $fecha->format('Y-m-d');
                } else {
                    // Intentar con formato estándar
                    try {
                        $fecha = new \DateTime($data['fecha_publicacion']);
                        $data['fecha_publicacion'] = $fecha->format('Y-m-d');
                    } catch (\Exception $e) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', 'Formato de fecha inválido. Use dd/mm/aaaa');
                    }
                }
            }
            
            // Manejar la imagen (obligatoria en creación)
            if ($request->hasFile('imagen')) {
                \Log::info('Image file detected');
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $rutaImagen = $imagen->storeAs('noticias', $nombreImagen, 'public');
                $data['imagen'] = $rutaImagen;
                \Log::info('Image stored at:', ['path' => $rutaImagen]);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La imagen es obligatoria');
            }
            
            // Convertir checkbox a boolean
            $data['visible'] = $request->has('visible');
            $data['destacada'] = false; // Por defecto no es destacada
            
            // Asignar orden automáticamente (siguiente número disponible)
            $maxOrden = Noticia::max('orden') ?? 0;
            $data['orden'] = $maxOrden + 1;
            
            // Asignar slug como #
            $data['slug'] = '#';
            
            \Log::info('Final data before create:', $data);
            
            // Crear la noticia
            $noticia = Noticia::create($data);
            \Log::info('Noticia created with ID:', ['id' => $noticia->id]);
            
            // No actualizar slug, ya está como #
            
            return redirect()->route('admin.noticias')->with('success', 'Noticia creada exitosamente');
            
        } catch (\Exception $e) {
            \Log::error('Error creating noticia:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la noticia: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Placeholder
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreNoticiaRequest $request, Noticia $noticia)
    {
        try {
            \Log::info('Update noticia request data:', $request->all());
            
            $data = $request->validated();
            \Log::info('Validated data:', $data);
            
            // Convertir fecha manualmente
            if (isset($data['fecha_publicacion'])) {
                $fecha = \DateTime::createFromFormat('Y-m-d', $data['fecha_publicacion']);
                if ($fecha) {
                    $data['fecha_publicacion'] = $fecha->format('Y-m-d');
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Formato de fecha inválido');
                }
            }
            
            // Manejar la imagen (opcional en edición)
            if ($request->hasFile('imagen')) {
                \Log::info('Image file detected');
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $rutaImagen = $imagen->storeAs('noticias', $nombreImagen, 'public');
                $data['imagen'] = $rutaImagen;
                \Log::info('Image stored at:', ['path' => $rutaImagen]);
            }
            // Si no hay imagen nueva, mantener la existente
            else {
                unset($data['imagen']);
            }
            
            // Convertir checkbox a boolean
            $data['visible'] = $request->has('visible');
            
            \Log::info('Final data before update:', $data);
            
            // Actualizar la noticia
            $noticia->update($data);
            \Log::info('Noticia updated with ID:', ['id' => $noticia->id]);
            
            return redirect()->route('admin.noticias')->with('success', 'Noticia actualizada exitosamente');
            
        } catch (\Exception $e) {
            \Log::error('Error updating noticia:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la noticia: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $noticia = Noticia::findOrFail($id);
            
            // Eliminar imagen si existe
            if ($noticia->imagen && Storage::disk('public')->exists($noticia->imagen)) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            
            // Eliminar la noticia
            $noticia->delete();
            
            return redirect()->route('admin.noticias')->with('success', 'Noticia eliminada exitosamente');
            
        } catch (\Exception $e) {
            return redirect()->route('admin.noticias')
                ->with('error', 'Error al eliminar la noticia: ' . $e->getMessage());
        }
    }

    /**
     * Get data for AJAX requests
     */
    public function getData(string $id)
    {
        $noticia = Noticia::findOrFail($id);
        return response()->json($noticia);
    }

    /**
     * Toggle visibility of a noticia
     */
    public function toggleVisibility(string $id)
    {
        $noticia = Noticia::findOrFail($id);
        
        // Si la noticia está destacada y se intenta ocultar, no permitir
        if ($noticia->destacada && $noticia->visible) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede ocultar una noticia destacada. Primero quítala del destacado.'
            ], 422);
        }
        
        $noticia->visible = !$noticia->visible;
        $noticia->save();

        return response()->json([
            'success' => true,
            'visible' => $noticia->visible
        ]);
    }

    /**
     * Toggle destacada status of a noticia
     */
    public function toggleDestacada(string $id)
    {
        try {
            $noticia = Noticia::findOrFail($id);
            
            // Si se está marcando como destacada, desmarcar todas las demás
            if (!$noticia->destacada) {
                Noticia::where('destacada', true)->update(['destacada' => false]);
            }
            
            $noticia->destacada = !$noticia->destacada;
            
            // Si se está marcando como destacada, también ponerla visible
            if ($noticia->destacada) {
                $noticia->visible = true;
            }
            
            $noticia->save();

            $message = $noticia->destacada ? 'Noticia marcada como destacada y puesta visible' : 'Noticia desmarcada como destacada';
            
            return redirect()->route('admin.noticias')->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->route('admin.noticias')
                ->with('error', 'Error al cambiar el estado destacado: ' . $e->getMessage());
        }
    }

    /**
     * Move noticia order
     */
    public function moverNoticia(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:noticias,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $noticia = Noticia::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $noticia->orden;
        
        if ($direccion === 'up') {
            // Buscar la noticia con orden inmediatamente menor
            $noticiaAnterior = Noticia::where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$noticiaAnterior) {
                return redirect()->route('admin.noticias')
                    ->with('error', 'Ya está en la primera posición');
            }
            
            // Intercambiar órdenes
            $noticia->update(['orden' => $noticiaAnterior->orden]);
            $noticiaAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar la noticia con orden inmediatamente mayor
            $noticiaSiguiente = Noticia::where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$noticiaSiguiente) {
                return redirect()->route('admin.noticias')
                    ->with('error', 'Ya está en la última posición');
            }
            
            // Intercambiar órdenes
            $noticia->update(['orden' => $noticiaSiguiente->orden]);
            $noticiaSiguiente->update(['orden' => $ordenActual]);
        }
        
        return redirect()->route('admin.noticias')
            ->with('success', 'Orden actualizado correctamente');
    }
}
