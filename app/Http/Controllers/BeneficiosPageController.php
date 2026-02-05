<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BeneficiosContent;
use Illuminate\Support\Facades\Storage;

class BeneficiosPageController extends Controller
{
    public function index()
    {
        $content = BeneficiosContent::first();

        if (!$content) {
            $content = BeneficiosContent::create();
        }

        return view('admin.beneficios-page', compact('content'));
    }

    public function updateContent(Request $request)
    {
        $request->validate([
            'hero_image' => 'nullable|image|max:2048',
            'club_itca_video' => 'nullable|mimes:mp4,mov,avi|max:102400',
            'club_itca_texto' => 'nullable|string',
            'club_itca_button_url' => 'nullable|string',
            'bolsa_work_image' => 'nullable|image|max:2048',
            'bolsa_work_text' => 'nullable|string',
            'bolsa_work_button_url' => 'nullable|string',
            'tienda_text' => 'nullable|string',
            'tienda_button_url' => 'nullable|string',
            'competencia_itca_video' => 'nullable|mimes:mp4,mov,avi|max:102400',
            'competencia_itca_texto' => 'nullable|string',
            'competencia_itca_button_url' => 'nullable|string',
        ]);

        $content = BeneficiosContent::first();
        if (!$content) {
            $content = new BeneficiosContent();
        }

        // Hero Image
        if ($request->hasFile('hero_image')) {
            if ($content->hero_image) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $path = $request->file('hero_image')->store('uploads/beneficios/hero', 'public');
            $content->hero_image = $path;
        }

        // Club ITCA Video
        if ($request->hasFile('club_itca_video')) {
            if ($content->club_itca_video) {
                Storage::disk('public')->delete($content->club_itca_video);
            }
            $path = $request->file('club_itca_video')->store('uploads/beneficios/club', 'public');
            $content->club_itca_video = $path;
        }

        // Club ITCA Texts
        if ($request->has('club_itca_texto')) {
            $content->club_itca_texto = $request->club_itca_texto;
        }
        if ($request->has('club_itca_button_url')) {
            $content->club_itca_button_url = $request->club_itca_button_url;
        }

        // Bolsa Laboral
        if ($request->hasFile('bolsa_work_image')) {
            if ($content->bolsa_work_image) {
                Storage::disk('public')->delete($content->bolsa_work_image);
            }
            $path = $request->file('bolsa_work_image')->store('uploads/beneficios/bolsa', 'public');
            $content->bolsa_work_image = $path;
        }

        if ($request->has('bolsa_work_text')) {
            $content->bolsa_work_text = $request->bolsa_work_text;
        }
        if ($request->has('bolsa_work_button_url')) {
            $content->bolsa_work_button_url = $request->bolsa_work_button_url;
        }

        // Tienda / Productos
        if ($request->has('tienda_text')) {
            $content->tienda_text = $request->tienda_text;
        }
        if ($request->has('tienda_button_url')) {
            $content->tienda_button_url = $request->tienda_button_url;
        }

        // Competencia ITCA
        if ($request->hasFile('competencia_itca_video')) {
            if ($content->competencia_itca_video) {
                Storage::disk('public')->delete($content->competencia_itca_video);
            }
            $path = $request->file('competencia_itca_video')->store('uploads/beneficios/competencia', 'public');
            $content->competencia_itca_video = $path;
        }

        if ($request->has('competencia_itca_texto')) {
            $content->competencia_itca_texto = $request->competencia_itca_texto;
        }
        if ($request->has('competencia_itca_button_url')) {
            $content->competencia_itca_button_url = $request->competencia_itca_button_url;
        }

        $content->save();

        $activeTab = $request->input('active_tab', 'header');
        return redirect()->back()->with('success', 'Contenido actualizado correctamente.')->with('active_tab', $activeTab);
    }

    // --- PRODUCTOS IMAGES ---
    public function storeProducto(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $content = BeneficiosContent::firstOrCreate([]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/beneficios/productos', 'public');
            
            $content->productos()->create([
                'image_path' => $path,
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado correctamente.')->with('active_tab', 'productos');
    }

    public function destroyProducto($id)
    {
        $producto = \App\Models\BeneficioProducto::findOrFail($id);
        
        if ($producto->image_path) {
            Storage::disk('public')->delete($producto->image_path);
        }
        
        $producto->delete();

        return redirect()->back()->with('success', 'Producto eliminado.')->with('active_tab', 'productos');
    }

    public function reorderProductos(Request $request)
    {
        $orden = $request->input('orden');
        
        if ($orden && is_array($orden)) {
            foreach ($orden as $index => $id) {
                \App\Models\BeneficioProducto::where('id', $id)->update(['orden' => $index]);
            }
        }

        return response()->json(['success' => true]);
    }
}
