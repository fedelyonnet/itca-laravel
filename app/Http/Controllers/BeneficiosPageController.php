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

        $content->save();

        $activeTab = $request->input('active_tab', 'header');
        return redirect()->back()->with('success', 'Contenido actualizado correctamente.')->with('active_tab', $activeTab);
    }
}
