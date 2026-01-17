<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatoContacto;
use Illuminate\Support\Facades\Storage;

class DatoContactoController extends Controller
{
    public function index()
    {
        $contactosInfo = DatoContacto::info()->get();
        $contactosSocial = DatoContacto::social()->get();
        
        return view('admin.contacto', compact('contactosInfo', 'contactosSocial'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:info,social',
            'icono' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'descripcion' => $request->descripcion,
            'contenido' => $request->contenido,
            'tipo' => $request->tipo,
        ];

        // Obtener el siguiente orden para el tipo específico
        $maxOrden = DatoContacto::where('tipo', $request->tipo)->max('orden') ?? 0;
        $data['orden'] = $maxOrden + 1;

        if ($request->hasFile('icono')) {
            $data['icono'] = $request->file('icono')->store('social', 'public');
        }

        DatoContacto::create($data);

        return redirect()->route('admin.contacto')->with('success', 'Dato de contacto creado exitosamente.');
    }

    public function getData($id)
    {
        $contacto = DatoContacto::findOrFail($id);
        
        return response()->json($contacto);
    }

    public function update(Request $request, $id)
    {
        $contacto = DatoContacto::findOrFail($id);
        
        $request->validate([
            'descripcion' => 'required|string|max:255',
            'contenido' => 'required|string',
            'tipo' => 'required|in:info,social',
            'icono' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'descripcion' => $request->descripcion,
            'contenido' => $request->contenido,
            'tipo' => $request->tipo,
        ];

        if ($request->hasFile('icono')) {
            // Delete old image
            if ($contacto->icono) {
                Storage::disk('public')->delete($contacto->icono);
            }
            $data['icono'] = $request->file('icono')->store('social', 'public');
        }

        $contacto->update($data);

        return redirect()->route('admin.contacto')->with('success', 'Dato de contacto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $contacto = DatoContacto::findOrFail($id);
        
        // Delete image if exists
        if ($contacto->icono) {
            Storage::disk('public')->delete($contacto->icono);
        }

        $contacto->delete();

        return redirect()->route('admin.contacto')->with('success', 'Dato de contacto eliminado exitosamente.');
    }
}
