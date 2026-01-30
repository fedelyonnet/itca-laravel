<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SomosItcaContent;
use App\Models\Instalacion;
use App\Models\Formador;
use Illuminate\Support\Facades\Storage;

class SomosItcaController extends Controller
{
    public function index()
    {
        $content = SomosItcaContent::with(['instalaciones', 'formadores'])->first();
        if (!$content) {
            // Create default record if not exists
            $content = SomosItcaContent::create();
        }
        
        $instalaciones = $content->instalaciones;
        $formadores = $content->formadores;

        return view('admin.somos-itca', compact('content', 'instalaciones', 'formadores'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/webm|max:51200', // 50MB Max
            'img_por_que' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $content = SomosItcaContent::first();
        if (!$content) {
            $content = new SomosItcaContent();
        }

        if ($request->hasFile('video_file')) {
            // Delete old video if exists
            if ($content->video_url) {
                Storage::disk('public')->delete($content->video_url);
            }
            $path = $request->file('video_file')->store('uploads/somos-itca/videos', 'public');
            $content->video_url = $path;
        }

        if ($request->hasFile('img_por_que')) {
            // Delete old image if exists
            if ($content->img_por_que) {
                Storage::disk('public')->delete($content->img_por_que);
            }
            $path = $request->file('img_por_que')->store('uploads/somos-itca', 'public');
            $content->img_por_que = $path;
        }

        $content->save();

        return redirect()->back()->with('success', 'Contenido actualizado correctamente.');
    }

    public function storeInstalacion(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $content = SomosItcaContent::firstOrCreate([]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/somos-itca/instalaciones', 'public');
            
            $content->instalaciones()->create([
                'image_path' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Instalación agregada correctamente.');
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
}
