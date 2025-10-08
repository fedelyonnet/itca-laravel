<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::ordered()->get();
        return view('admin.partners', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|url|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Verificar que el archivo existe antes de procesarlo
        if (!$request->hasFile('logo')) {
            return back()->withErrors(['logo' => 'El logo es obligatorio']);
        }
        
        $logoPath = $request->file('logo')->store('partners', 'public');

        Partner::create([
            'url' => $request->url,
            'logo' => $logoPath,
            'orden' => Partner::max('orden') + 1, // Asignar siguiente orden
        ]);

        return redirect()->route('admin.partners')->with('success', 'Partner creado exitosamente.');
    }

    public function getData($id)
    {
        $partner = Partner::findOrFail($id);
        return response()->json([
            'id' => $partner->id,
            'url' => $partner->url,
        ]);
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        $request->validate([
            'url' => 'required|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'url' => $request->url,
        ];

        if ($request->hasFile('logo')) {
            // Delete old image
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.partners')->with('success', 'Partner actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        
        // Delete image
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return redirect()->route('admin.partners')->with('success', 'Partner eliminado exitosamente.');
    }

    public function moverPartner(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:partners,id',
            'direccion' => 'required|in:up,down'
        ]);
        
        $partner = Partner::findOrFail($request->id);
        $direccion = $request->direccion;
        $ordenActual = $partner->orden;
        
        if ($direccion === 'up') {
            // Buscar el partner con orden inmediatamente menor
            $partnerAnterior = Partner::where('orden', '<', $ordenActual)
                                ->orderBy('orden', 'desc')
                                ->first();
            
            if (!$partnerAnterior) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la primera posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $partner->update(['orden' => $partnerAnterior->orden]);
            $partnerAnterior->update(['orden' => $ordenActual]);
            
        } else { // down
            // Buscar el partner con orden inmediatamente mayor
            $partnerSiguiente = Partner::where('orden', '>', $ordenActual)
                                  ->orderBy('orden', 'asc')
                                  ->first();
            
            if (!$partnerSiguiente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya está en la última posición'
                ], 422);
            }
            
            // Intercambiar órdenes
            $partner->update(['orden' => $partnerSiguiente->orden]);
            $partnerSiguiente->update(['orden' => $ordenActual]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Partner movido correctamente'
        ]);
    }
}
