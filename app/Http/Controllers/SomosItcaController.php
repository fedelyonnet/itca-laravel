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
            'formadores' => function($q) { $q->orderBy('orden', 'asc')->orderBy('id', 'asc'); },
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
            'hero_image' => 'nullable|image|max:2048',
            'title_line_1' => 'nullable|string|max:255',
            'title_line_2' => 'nullable|string|max:255',
            'title_line_3' => 'nullable|string|max:255',
            'm1_number' => 'nullable|string|max:255',
            'm1_title' => 'nullable|string|max:255',
            'm1_text' => 'nullable|string',
            'm2_number' => 'nullable|string|max:255',
            'm2_title' => 'nullable|string|max:255',
            'm2_text' => 'nullable|string',
            'm3_number' => 'nullable|string|max:255',
            'm3_title' => 'nullable|string|max:255',
            'm3_text' => 'nullable|string',
            'm4_number' => 'nullable|string|max:255',
            'm4_title' => 'nullable|string|max:255',
            'm4_text' => 'nullable|string',
            'cat1_title' => 'nullable|string|max:255',
            'cat1_text' => 'nullable|string',
            'cat1_img' => 'nullable|image|max:2048',
            'cat2_title' => 'nullable|string|max:255',
            'cat2_text' => 'nullable|string',
            'cat2_img' => 'nullable|image|max:2048',
            'cat3_title' => 'nullable|string|max:255',
            'cat3_text' => 'nullable|string',
            'cat3_img' => 'nullable|image|max:2048',
            'cat4_title' => 'nullable|string|max:255',
            'cat4_text' => 'nullable|string',
            'cat4_img' => 'nullable|image|max:2048',
        ]);

        $content = SomosItcaContent::first();
        if (!$content) {
            $content = new SomosItcaContent();
        }

        // Hero Image
        if ($request->hasFile('hero_image')) {
            if ($content->hero_image) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $path = $request->file('hero_image')->store('uploads/somos-itca/hero', 'public');
            $content->hero_image = $path;
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

        // Save text fields
        if ($request->has('title_line_1')) $content->title_line_1 = $request->input('title_line_1');
        if ($request->has('title_line_2')) $content->title_line_2 = $request->input('title_line_2');
        if ($request->has('title_line_3')) $content->title_line_3 = $request->input('title_line_3');
        
        if ($request->has('que_es_itca')) {
            $content->que_es_itca = $request->input('que_es_itca');
        }
        
        if ($request->has('formadores_texto')) {
            $content->formadores_texto = $request->input('formadores_texto');
        }

        // Metrics
        for ($i = 1; $i <= 4; $i++) {
            $numField = "m{$i}_number";
            $titleField = "m{$i}_title";
            $textField = "m{$i}_text";
            
            if ($request->has($numField)) $content->$numField = $request->input($numField);
            if ($request->has($titleField)) $content->$titleField = $request->input($titleField);
            if ($request->has($textField)) $content->$textField = $request->input($textField);
        }

        // Categories
        for ($i = 1; $i <= 4; $i++) {
            $titleField = "cat{$i}_title";
            $textField = "cat{$i}_text";
            $imgField = "cat{$i}_img";
            
            if ($request->has($titleField)) $content->$titleField = $request->input($titleField);
            if ($request->has($textField)) $content->$textField = $request->input($textField);
            
            if ($request->hasFile($imgField)) {
                // Delete old image if exists
                if ($content->$imgField) {
                    \Storage::disk('public')->delete($content->$imgField);
                }
                $path = $request->file($imgField)->store('somos-itca', 'public');
                $content->$imgField = $path;
            }
        }

        $content->save();

        $activeTab = $request->input('active_tab', 'header');
        return redirect()->back()->with('success', 'Contenido actualizado correctamente.')->with('active_tab', $activeTab);
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

        return redirect()->back()->with('success', 'Instalación agregada correctamente.')->with('active_tab', 'instalaciones');
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

        return redirect()->back()->with('success', 'Instalación eliminada.')->with('active_tab', 'instalaciones');
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

        return redirect()->back()->with('success', 'Formador agregado correctamente.')->with('active_tab', 'formadores');
    }

    public function destroyFormador($id)
    {
        $formador = Formador::findOrFail($id);
        
        if ($formador->image_path) {
            Storage::disk('public')->delete($formador->image_path);
        }
        
        $formador->delete();

        return redirect()->back()->with('success', 'Formador eliminado.')->with('active_tab', 'formadores');
    }

    public function reorderFormadores(Request $request)
    {
        $orden = $request->input('orden'); 
        
        if ($orden && is_array($orden)) {
            foreach ($orden as $index => $id) {
                Formador::where('id', $id)->update(['orden' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function storePorQueItem(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'icon' => 'nullable|image|max:2048', // Allow icon upload
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        $data = ['descripcion' => $request->descripcion];

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('uploads/somos-itca/icons', 'public');
            $data['image_path'] = $path;
        }

        $content->porQueItems()->create($data);

        return redirect()->back()->with('success', 'Item agregado correctamente.')->with('active_tab', 'porque');
    }

    public function updatePorQueItem(Request $request, $id)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'icon' => 'nullable|image|max:2048', // Allow icon update
        ]);

        $item = PorQueItem::findOrFail($id);
        
        $data = ['descripcion' => $request->descripcion];

        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $path = $request->file('icon')->store('uploads/somos-itca/icons', 'public');
            $data['image_path'] = $path;
        }

        $item->update($data);

        return redirect()->back()->with('success', 'Item actualizado correctamente.')->with('active_tab', 'porque');
    }

    public function destroyPorQueItem($id)
    {
        $item = PorQueItem::findOrFail($id);
        
        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $item->delete();
        return redirect()->back()->with('success', 'Item eliminado.')->with('active_tab', 'porque');
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

        return redirect()->back()->with('success', 'Item de instalación agregado.')->with('active_tab', 'instalaciones');
    }

    public function destroyInstalacionItem($id)
    {
        $item = \App\Models\InstalacionItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item eliminado.')->with('active_tab', 'instalaciones');
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
