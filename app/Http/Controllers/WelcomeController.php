<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Curso;
use App\Models\Beneficio;
use App\Models\Sede;
use App\Models\Testimonio;
use App\Models\Partner;
use App\Models\EnAccion;
use App\Models\StickyBar;
use App\Models\Noticia;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener todos los registros del hero
        $heroes = Hero::all();
        
        // Organizar por versión y tipo
        $desktopImg1 = $heroes->where('version', 'desktop')->where('type', 'img1')->first();
        $desktopImg2 = $heroes->where('version', 'desktop')->where('type', 'img2')->first();
        $desktopVideo = $heroes->where('version', 'desktop')->where('type', 'video')->first();
        $mobileImg1 = $heroes->where('version', 'mobile')->where('type', 'img1')->first();
        $mobileImg2 = $heroes->where('version', 'mobile')->where('type', 'img2')->first();
        $mobileVideo = $heroes->where('version', 'mobile')->where('type', 'video')->first();
        
        // Obtener cursos destacados
        $cursosFeatured = Curso::where('featured', true)->get();
        
        // Obtener beneficios ordenados
        $beneficios = Beneficio::ordered()->get();
        
        // Obtener todas las sedes ordenadas (disponibles y no disponibles)
        $sedes = Sede::ordered()->get();
        
        // Obtener testimonios visibles ordenados (máximo 8)
        $testimonios = Testimonio::where('visible', true)
                                ->ordered()
                                ->limit(8)
                                ->get();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener videos de En Acción desktop para el grid fijo
        $video1 = EnAccion::where('version', 'desktop')->where('url', 'like', '%instagram.com%')->first(); // Lugar 1 - Instagram
        $video3 = EnAccion::where('version', 'desktop')->where('url', 'like', '%tiktok.com%')->first(); // Lugar 3 - TikTok  
        $video5 = EnAccion::where('version', 'desktop')->where(function($query) {
            $query->where('url', 'like', '%youtube.com%')->orWhere('url', 'like', '%youtu.be%');
        })->first(); // Lugar 5 - YouTube
        
        // Obtener videos de En Acción tablet para el carousel
        $videosTablet = EnAccion::where('version', 'mob')->orderBy('created_at', 'desc')->get();
        
        // Obtener videos de En Acción mobile para el carousel
        $videosMobile = EnAccion::where('version', 'mob')->orderBy('created_at', 'desc')->get();
        
        // Obtener Sticky Bar
        $stickyBar = StickyBar::first();
        
        // Obtener noticia destacada
        $noticiaDestacada = Noticia::where('destacada', true)
                                  ->where('visible', true)
                                  ->first();
        
        return view('welcome', compact(
            'desktopImg1', 'desktopImg2', 'desktopVideo',
            'mobileImg1', 'mobileImg2', 'mobileVideo',
            'cursosFeatured',
            'beneficios',
            'sedes',
            'testimonios',
            'partners',
            'video1', 'video3', 'video5', 'videosTablet', 'videosMobile',
            'stickyBar',
            'noticiaDestacada'
        ));
    }

    public function carreras()
    {
        // Obtener todas las carreras ordenadas
        $carreras = Curso::ordered()->get();
        
        // Obtener beneficios ordenados
        $beneficios = Beneficio::ordered()->get();
        
        // Obtener partners ordenados
        $partners = Partner::ordered()->get();
        
        // Obtener sedes disponibles para el footer
        $sedes = Sede::where('disponible', true)
                    ->whereNotIn('nombre', ['online', 'Online', 'ONLINE', 'próximamente', 'Proximamente', 'PROXIMAMENTE'])
                    ->ordered()
                    ->get();
        
        return view('carreras', compact('carreras', 'beneficios', 'partners', 'sedes'));
    }
}