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

        $content->save();

        $activeTab = $request->input('active_tab', 'header');
        return redirect()->back()->with('success', 'Contenido actualizado correctamente.')->with('active_tab', $activeTab);
    }
}
