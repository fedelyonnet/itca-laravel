<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\StickyBar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function index()
    {
        $heroes = Hero::all();
        $stickyBar = StickyBar::first();
        
        if (!$stickyBar) {
            $stickyBar = StickyBar::create([
                'visible' => true,
                'texto' => '¡Oferta especial! 🎉 **50% descuento** //hasta agotar stock//',
                'color' => '#1f2937'
            ]);
        }
        
        return view('admin.edit-hero', compact('heroes', 'stickyBar'));
    }

    public function update(Request $request, $id)
    {
        if ($request->hasFile('file')) {
            // Buscar o crear el registro
            $hero = Hero::find($id);
            
            if (!$hero || $id == 0) {
                // Si no existe, crear uno nuevo con los datos del formulario
                $version = $request->input('version');
                $type = $request->input('type');
                $hero = Hero::create([
                    'version' => $version,
                    'type' => $type,
                    'url' => null
                ]);
            }
            
            // Eliminar archivo anterior si existe
            if ($hero->url && Storage::disk('public')->exists($hero->url)) {
                Storage::disk('public')->delete($hero->url);
            }
            
            // Subir nuevo archivo
            $file = $request->file('file');
            $path = $file->store('hero', 'public');
            $hero->url = $path;
            $hero->save();
        }
        
        return redirect()->route('admin.edit-hero')->with('success', 'Archivo subido correctamente');
    }

    public function destroy($id)
    {
        $hero = Hero::find($id);
        
        if ($hero) {
            // Eliminar archivo del storage si existe
            if ($hero->url && Storage::disk('public')->exists($hero->url)) {
                Storage::disk('public')->delete($hero->url);
            }
            
            // Eliminar el registro completo de la base de datos
            $hero->delete();
        }
        
        return redirect()->route('admin.edit-hero')->with('success', 'Archivo eliminado correctamente');
    }
}
