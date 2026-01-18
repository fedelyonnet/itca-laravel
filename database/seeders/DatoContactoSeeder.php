<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatoContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Info de contacto
        \App\Models\DatoContacto::create([
            'descripcion' => 'Tel:',
            'contenido' => '0810-220-4822',
            'tipo' => 'info',
            'orden' => 1
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'WhatsApp:',
            'contenido' => '11-2267-4822',
            'tipo' => 'info',
            'orden' => 2
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'Mail:',
            'contenido' => 'inscripciones@itca.edu.ar',
            'tipo' => 'info',
            'orden' => 3
        ]);
        
        // Redes Sociales
        \App\Models\DatoContacto::create([
            'descripcion' => 'Instagram',
            'contenido' => 'https://www.instagram.com/itca.oficial/?hl=en',
            'tipo' => 'social',
            'icono' => 'images/social/ig.png',
            'orden' => 1
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'TikTok',
            'contenido' => 'https://www.tiktok.com/@itca.oficial',
            'tipo' => 'social',
            'icono' => 'images/social/tik.png',
            'orden' => 2
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'Facebook',
            'contenido' => 'https://www.facebook.com/ITCAoficial/',
            'tipo' => 'social',
            'icono' => 'images/social/fb.png',
            'orden' => 3
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'LinkedIn',
            'contenido' => 'https://www.linkedin.com/school/itca-oficial/',
            'tipo' => 'social',
            'icono' => 'images/social/lin.png',
            'orden' => 4
        ]);
        
        \App\Models\DatoContacto::create([
            'descripcion' => 'YouTube',
            'contenido' => 'https://www.youtube.com/canalITCAoficial',
            'tipo' => 'social',
            'icono' => 'images/social/yt.png',
            'orden' => 5
        ]);
    }
}
