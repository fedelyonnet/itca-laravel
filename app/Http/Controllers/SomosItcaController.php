<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SomosItcaContent;
use App\Models\Instalacion;
use App\Models\Formador;
use App\Models\PorQueItem;
use Illuminate\Support\Facades\Storage;

class SomosItcaController extends Controller
{
    public function index()
    {
        $content = SomosItcaContent::with([
            'instalaciones' => function($q) { $q->orderBy('orden', 'asc')->orderBy('id', 'asc'); },
            'formadores',
            'porQueItems' => function($q) { $q->orderBy('orden', 'asc')->orderBy('id', 'asc'); },
            'instalacionItems' => function($q) { $q->orderBy('orden', 'asc')->orderBy('id', 'asc'); }
        ])->first();

        if (!$content) {
            $content = SomosItcaContent::create();
        }
        
        $instalaciones = $content->instalaciones;
        $formadores = $content->formadores;
        $porQueItems = $content->porQueItems;
        $instalacionItems = $content->instalacionItems;

        return view('admin.somos-itca', compact('content', 'instalaciones', 'formadores', 'porQueItems', 'instalacionItems'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/webm|max:51200',
            'img_por_que' => 'nullable|image|max:2048',
        ]);

        $content = SomosItcaContent::first();
        if (!$content) {
            $content = new SomosItcaContent();
        }

        if ($request->hasFile('video_file')) {
            if ($content->video_url) {
                Storage::disk('public')->delete($content->video_url);
            }
            $path = $request->file('video_file')->store('uploads/somos-itca/videos', 'public');
            $content->video_url = $path;
        }

        if ($request->hasFile('img_por_que')) {
            if ($content->img_por_que) {
                Storage::disk('public')->delete($content->img_por_que);
            }
            $path = $request->file('img_por_que')->store('uploads/somos-itca', 'public');
            $content->img_por_que = $path;
        }

        // Save 'que_es_itca' text
        if ($request->has('que_es_itca')) {
            $content->que_es_itca = $request->input('que_es_itca');
        }

        $content->save();

        return redirect()->back()->with('success', 'Contenido actualizado correctamente.');
    }

    public function storeInstalacion(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'descripcion' => 'nullable|string',
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/somos-itca/instalaciones', 'public');
            
            $content->instalaciones()->create([
                'image_path' => $path,
                'descripcion' => $request->input('descripcion'),
            ]);
        }

        return redirect()->back()->with('success', 'Instalación agregada correctamente.');
    }
    
    // Método para actualizar la descripción (si fuera necesario separado)
    public function updateInstalacion(Request $request, $id)
    {
        $instalacion = Instalacion::findOrFail($id);
        
        if ($request->has('descripcion')) {
            $instalacion->descripcion = $request->input('descripcion');
            $instalacion->save();
            return redirect()->back()->with('success', 'Descripción actualizada.');
        }
        
        return redirect()->back();
    }

    public function destroyInstalacion($id)
    {
        $instalacion = Instalacion::findOrFail($id);
        
        if ($instalacion->image_path) {
            Storage::disk('public')->delete($instalacion->image_path);
        }
        
        $instalacion->delete();

        return redirect()->back()->with('success', 'Instalación eliminada.');
    }
    
    public function reorderInstalaciones(Request $request)
    {
        $orden = $request->input('orden'); 
        
        if ($orden && is_array($orden)) {
            foreach ($orden as $index => $id) {
                Instalacion::where('id', $id)->update(['orden' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function storeFormador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/somos-itca/formadores', 'public');
            
            $content->formadores()->create([
                'nombre' => $request->nombre,
                'image_path' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Formador agregado correctamente.');
    }

    public function destroyFormador($id)
    {
        $formador = Formador::findOrFail($id);
        
        if ($formador->image_path) {
            Storage::disk('public')->delete($formador->image_path);
        }
        
        $formador->delete();

        return redirect()->back()->with('success', 'Formador eliminado.');
    }

    public function storePorQueItem(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        $content->porQueItems()->create([
            'descripcion' => $request->descripcion
        ]);

        return redirect()->back()->with('success', 'Item agregado correctamente.');
    }

    public function destroyPorQueItem($id)
    {
        $item = PorQueItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item eliminado.');
    }

    public function reorderPorQueItems(Request $request)
    {
        $orden = $request->input('orden'); // Array de IDs en orden
        
        if ($orden && is_array($orden)) {
            foreach ($orden as $index => $id) {
                PorQueItem::where('id', $id)->update(['orden' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }

    // INSTALACION ITEMS (Lista con estrellas)
    public function storeInstalacionItem(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        $content->instalacionItems()->create([
            'descripcion' => $request->descripcion
        ]);

        return redirect()->back()->with('success', 'Item de instalación agregado.');
    }

    public function destroyInstalacionItem($id)
    {
        $item = \App\Models\InstalacionItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item eliminado.');
    }

    public function reorderInstalacionItems(Request $request)
    {
        $orden = $request->input('orden');
        
        if ($orden && is_array($orden)) {
            foreach ($orden as $index => $id) {
                \App\Models\InstalacionItem::where('id', $id)->update(['orden' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }
}
