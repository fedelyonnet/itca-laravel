<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;
use App\Models\CategoriaNoticia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminNoticiaController extends Controller
{
    public function index()
    {
        // Traemos las noticias ordenadas por fecha de publicación
        $noticias = Noticia::with('categorias')->orderBy('fecha_publicacion', 'desc')->get();
        
        return view('admin.noticias.index', compact('noticias'));
    }



    public function create()
    {
        $categorias = CategoriaNoticia::orderBy('nombre')->get();
        return view('admin.noticias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'extracto' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'visible' => 'nullable|boolean',
            'destacada' => 'nullable|boolean',
            'autor_nombre' => 'nullable|string|max:255',
            'autor_puesto' => 'nullable|string|max:255',
            'imagen_hero' => 'nullable|image|max:2048',
            'imagen_thumb' => 'nullable|image|max:2048',
            'banner_publicitario' => 'nullable|image|max:2048',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias_noticias,id'
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'extracto.max' => 'El extracto no puede exceder los 500 caracteres.',
            'contenido.required' => 'El contenido de la noticia es obligatorio.',
            'fecha_publicacion.required' => 'La fecha de publicación es obligatoria.',
            'fecha_publicacion.date' => 'La fecha de publicación no es válida.',
            'autor_nombre.max' => 'El nombre del autor es muy largo.',
            'autor_puesto.max' => 'El puesto del autor es muy largo.',
            'imagen_hero.image' => 'La imagen principal debe ser un archivo de imagen (jpg, png, etc).',
            'imagen_hero.max' => 'La imagen principal no puede pesar más de 2MB.',
            'imagen_thumb.image' => 'La miniatura debe ser un archivo de imagen.',
            'imagen_thumb.max' => 'La miniatura no puede pesar más de 2MB.',
            'banner_publicitario.image' => 'El banner debe ser un archivo de imagen.',
            'banner_publicitario.max' => 'El banner no puede pesar más de 2MB.'
        ]);

        // Generar slug único
        $slug = Str::slug($validated['titulo']);
        $originalSlug = $slug;
        $counter = 1;
        while (Noticia::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Crear la noticia
        $noticia = new Noticia();
        $noticia->titulo = $validated['titulo'];
        $noticia->slug = $slug;
        $noticia->extracto = $validated['extracto'] ?? null;
        $noticia->contenido = $validated['contenido'];
        $noticia->fecha_publicacion = $validated['fecha_publicacion'];
        $noticia->visible = $request->has('visible') ? true : false;
        $noticia->destacada = $request->has('destacada') ? true : false;
        $noticia->autor_nombre = $validated['autor_nombre'] ?? null;
        $noticia->autor_puesto = $validated['autor_puesto'] ?? null;

        // Manejar imagen hero
        if ($request->hasFile('imagen_hero')) {
            $path = $request->file('imagen_hero')->store('noticias/hero', 'public');
            $noticia->imagen_hero = $path;
        }

        // Manejar imagen thumbnail
        if ($request->hasFile('imagen_thumb')) {
            $path = $request->file('imagen_thumb')->store('noticias/thumb', 'public');
            $noticia->imagen_thumb = $path;
        }

        // Manejar banner publicitario
        if ($request->hasFile('banner_publicitario')) {
            $path = $request->file('banner_publicitario')->store('noticias/banners', 'public');
            $noticia->banner_publicitario = $path;
        }

        $noticia->save();

        // Sincronizar categorías
        if ($request->has('categorias')) {
            $noticia->categorias()->sync($validated['categorias']);
        }

        if ($request->wantsJson()) {
            session()->flash('success', 'Noticia creada exitosamente');
            return response()->json(['success' => true, 'redirect' => route('admin.noticias.index')]);
        }

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia creada exitosamente');
    }

    public function edit($id)
    {
        $noticia = Noticia::with('categorias')->findOrFail($id);
        $categorias = CategoriaNoticia::orderBy('nombre')->get();
        
        return view('admin.noticias.edit', compact('noticia', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $noticia = Noticia::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'extracto' => 'nullable|string|max:500',
            'contenido' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'visible' => 'nullable|boolean',
            'destacada' => 'nullable|boolean',
            'autor_nombre' => 'nullable|string|max:255',
            'autor_puesto' => 'nullable|string|max:255',
            'imagen_hero' => 'nullable|image|max:2048',
            'imagen_thumb' => 'nullable|image|max:2048',
            'banner_publicitario' => 'nullable|image|max:2048',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias_noticias,id'
        ], [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede exceder los 255 caracteres.',
            'extracto.max' => 'El extracto no puede exceder los 500 caracteres.',
            'contenido.required' => 'El contenido de la noticia es obligatorio.',
            'fecha_publicacion.required' => 'La fecha de publicación es obligatoria.',
            'fecha_publicacion.date' => 'La fecha de publicación no es válida.',
            'autor_nombre.max' => 'El nombre del autor es muy largo.',
            'autor_puesto.max' => 'El puesto del autor es muy largo.',
            'imagen_hero.image' => 'La imagen principal debe ser un archivo de imagen (jpg, png, etc).',
            'imagen_hero.max' => 'La imagen principal no puede pesar más de 2MB.',
            'imagen_thumb.image' => 'La miniatura debe ser un archivo de imagen.',
            'imagen_thumb.max' => 'La miniatura no puede pesar más de 2MB.',
            'banner_publicitario.image' => 'El banner debe ser un archivo de imagen.',
            'banner_publicitario.max' => 'El banner no puede pesar más de 2MB.'
        ]);

        // Actualizar slug si cambió el título
        if ($noticia->titulo !== $validated['titulo']) {
            $slug = Str::slug($validated['titulo']);
            $originalSlug = $slug;
            $counter = 1;
            while (Noticia::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $noticia->slug = $slug;
        }

        $noticia->titulo = $validated['titulo'];
        $noticia->extracto = $validated['extracto'] ?? null;
        $noticia->contenido = $validated['contenido'];
        $noticia->fecha_publicacion = $validated['fecha_publicacion'];
        $noticia->visible = $request->has('visible') ? true : false;
        $noticia->destacada = $request->has('destacada') ? true : false;
        $noticia->autor_nombre = $validated['autor_nombre'] ?? null;
        $noticia->autor_puesto = $validated['autor_puesto'] ?? null;

        // Manejar imagen hero
        if ($request->hasFile('imagen_hero')) {
            // Eliminar imagen anterior
            if ($noticia->imagen_hero) {
                Storage::disk('public')->delete($noticia->imagen_hero);
            }
            $path = $request->file('imagen_hero')->store('noticias/hero', 'public');
            $noticia->imagen_hero = $path;
        }

        // Manejar imagen thumbnail
        if ($request->hasFile('imagen_thumb')) {
            // Eliminar imagen anterior
            if ($noticia->imagen_thumb) {
                Storage::disk('public')->delete($noticia->imagen_thumb);
            }
            $path = $request->file('imagen_thumb')->store('noticias/thumb', 'public');
            $noticia->imagen_thumb = $path;
        }

        // Manejar banner publicitario
        if ($request->hasFile('banner_publicitario')) {
            // Eliminar banner anterior
            if ($noticia->banner_publicitario) {
                Storage::disk('public')->delete($noticia->banner_publicitario);
            }
            $path = $request->file('banner_publicitario')->store('noticias/banners', 'public');
            $noticia->banner_publicitario = $path;
        }

        $noticia->save();

        // Sincronizar categorías
        if ($request->has('categorias')) {
            $noticia->categorias()->sync($validated['categorias']);
        } else {
            $noticia->categorias()->sync([]);
        }

        if ($request->wantsJson()) {
            session()->flash('success', 'Noticia actualizada exitosamente');
            return response()->json(['success' => true, 'redirect' => route('admin.noticias.index')]);
        }

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia actualizada exitosamente');
    }

    public function destroy($id)
    {
        $noticia = Noticia::findOrFail($id);

        // Eliminar imágenes
        if ($noticia->imagen_hero) {
            Storage::disk('public')->delete($noticia->imagen_hero);
        }
        if ($noticia->imagen_thumb) {
            Storage::disk('public')->delete($noticia->imagen_thumb);
        }
        if ($noticia->banner_publicitario) {
            Storage::disk('public')->delete($noticia->banner_publicitario);
        }

        // Eliminar relaciones con categorías
        $noticia->categorias()->detach();

        // Eliminar noticia
        $noticia->delete();

        return redirect()->route('admin.noticias.index')->with('success', 'Noticia eliminada exitosamente');
    }

    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias_noticias,nombre'
        ]);

        $slug = Str::slug($request->nombre);
        
        // Si el slug queda vacío (ej: caracteres raros), usar timestamp
        if (empty($slug)) {
            $slug = 'categoria-' . time();
        }

        // Verificar si el slug ya existe
        if (CategoriaNoticia::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $categoria = CategoriaNoticia::create([
            'nombre' => $request->nombre,
            'slug' => $slug
        ]);

        return response()->json([
            'success' => true,
            'categoria' => [
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'slug' => $categoria->slug, // Ensure slug is returned
                'noticias_count' => 0
            ]
        ]);
    }

    public function updateCategoria(Request $request, $id)
    {
        $categoria = CategoriaNoticia::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias_noticias,nombre,' . $id
        ]);

        $categoria->nombre = $request->nombre;
        $slug = Str::slug($request->nombre);
        
        if (empty($slug)) {
            $slug = 'categoria-' . time();
        }

        // Verificar unicidad del slug excluyendo el actual
        if (CategoriaNoticia::where('slug', $slug)->where('id', '!=', $id)->exists()) {
             $slug = $slug . '-' . time();
        }

        $categoria->slug = $slug;
        $categoria->save();

        return response()->json([
            'success' => true,
            'categoria' => $categoria
        ]);
    }

    public function destroyCategoria($id)
    {
        $categoria = CategoriaNoticia::findOrFail($id);
        
        // Laravel borrara automáticamente las entradas en la tabla pivot noticia_categoria 
        // debido a onDelete('cascade') en la migración.
        $categoria->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
