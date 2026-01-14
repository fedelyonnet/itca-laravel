<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StickyBar;

class StickyBarController extends Controller
{
    public function index()
    {
        $stickyBar = StickyBar::first();
        
        if (!$stickyBar) {
            $stickyBar = StickyBar::create([
                'visible' => true,
                'texto' => '¡Oferta especial! 🎉 **50% descuento** //hasta agotar stock//',
                'color' => '#1f2937'
            ]);
        }
        
        return view('admin.edit-hero', compact('stickyBar'));
    }
    
    public function update(Request $request)
    {
        \Log::info('StickyBar update method called');
        \Log::info('Request data: ' . json_encode($request->all()));
        
        $request->validate([
            'texto' => 'nullable|max:200',
            'texto_url' => 'nullable|max:100',
            'url' => 'nullable|url|max:255',
            'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'visible' => 'boolean'
        ]);
        
        // Siempre obtener o crear el único registro
        $stickyBar = StickyBar::first();
        if (!$stickyBar) {
            $stickyBar = StickyBar::create([
                'visible' => true,
                'texto' => '¡Oferta especial! 🎉 **50% descuento** //hasta agotar stock//',
                'texto_url' => 'más info',
                'url' => null,
                'color' => '#1f2937',
                'text_color' => '#ffffff'
            ]);
        }
        
        $updateData = [
            'visible' => $request->input('visible') == '1',
        ];
        
        // Solo actualizar color si se proporciona
        if ($request->has('color')) {
            $updateData['color'] = $request->color;
        }

        // Solo actualizar color de texto si se proporciona
        if ($request->has('text_color')) {
            $updateData['text_color'] = $request->text_color;
        }
        
        // Solo actualizar texto si se proporciona
        if ($request->has('texto')) {
            $updateData['texto'] = $request->texto;
        }
        
        // Solo actualizar texto del enlace si se proporciona
        if ($request->has('texto_url')) {
            $updateData['texto_url'] = $request->texto_url;
        }
        
        // Solo actualizar URL si se proporciona
        if ($request->has('url')) {
            $updateData['url'] = $request->url;
        }
        
        $stickyBar->update($updateData);
        
        \Log::info('StickyBar updated: ' . json_encode($stickyBar->fresh()->toArray()));
        
        // Mensaje específico según el cambio
        $message = 'Sticky Bar actualizado correctamente';
        
        // Mensajes específicos según los campos actualizados
        if ($request->has('visible') && !$request->has('texto') && !$request->has('texto_url') && !$request->has('url') && !$request->has('color')) {
            $message = $request->input('visible') == '1' ? 'Sticky Bar activado correctamente' : 'Sticky Bar desactivado correctamente';
        } elseif ($request->has('color') && !$request->has('texto') && !$request->has('texto_url') && !$request->has('url')) {
            $message = 'Color del Sticky Bar actualizado correctamente';
        } elseif (($request->has('texto') || $request->has('texto_url') || $request->has('url')) && !$request->has('color')) {
            $message = 'Texto del Sticky Bar actualizado correctamente';
        } else {
            $message = 'Sticky Bar actualizado correctamente';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
}
