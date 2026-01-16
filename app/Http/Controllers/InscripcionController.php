<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use Illuminate\Http\Request;

class InscripcionController extends Controller
{
    public function index()
    {
        $inscripciones = Inscripcion::with(['lead', 'cursada'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.inscriptos', compact('inscripciones'));
    }
}
