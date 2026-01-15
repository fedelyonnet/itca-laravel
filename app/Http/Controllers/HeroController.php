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
            
            // 1. Subir nuevo archivo primero
            $file = $request->file('file');
            $newPath = $file->store('hero', 'public');
            
            // 2. Guardar ruta vieja para borrar después
            $oldPath = $hero->url;
            
            // 3. Actualizar BD con la nueva ruta
            $hero->url = $newPath;
            $hero->save();
            
            // 4. Borrar archivo anterior solo si la actualización fue exitosa
            if ($oldPath && $oldPath !== $newPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            
            return redirect()->route('admin.edit-hero')->with('success', 'Archivo subido correctamente');
        }
        
        return redirect()->route('admin.edit-hero')->with('error', 'No se seleccionó ningún archivo o hubo un error en la subida');
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
