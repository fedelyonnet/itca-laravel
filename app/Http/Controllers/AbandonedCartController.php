<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbandonedCartController extends Controller
{
    /**
     * Recupera un carrito abandonado mediante token y redirige a la inscripción.
     */
    public function recover($token)
    {
        // Buscar el carrito por token
        $abandonedCart = \App\Models\AbandonedCart::where('token', $token)->first();

        if (!$abandonedCart) {
            // Si el token no existe, redirigir al home o mostrar error
            return redirect('/')->with('error', 'El enlace de recuperación no es válido o ha expirado.');
        }

        // Marcar como recuperado (opcional: solo si queremos trackear el click)
        // Podríamos querer actualizarlo solo si estaba en 'pendiente' o 'enviado'
        $abandonedCart->update([
            'estado' => 'recuperado',
            'updated_at' => now(), // Asegurar que se actualice el timestamp
        ]);

        // Redirigir a la ruta de inscripción existente con los parámetros necesarios
        return redirect()->route('inscripcion.retomar', [
            'retomar' => $abandonedCart->cursada_id,
            'lead' => $abandonedCart->lead_id,
        ]);
    }
}
