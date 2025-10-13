<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickyBar extends Model
{
    protected $fillable = [
        'visible',
        'texto',
        'texto_url',
        'url',
        'color'
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    // Función para formatear el texto
    public function getFormattedTextAttribute()
    {
        $texto = $this->texto ?? '';
        $textoUrl = $this->texto_url ?? '';
        $url = $this->url ?? '';
        
        // Construir el texto combinando texto principal + texto del enlace
        $text = '';
        
        if (!empty($texto)) {
            $text .= $texto;
        }
        
        if (!empty($textoUrl)) {
            if (!empty($text)) {
                $text .= ' ' . $textoUrl;
            } else {
                $text = $textoUrl;
            }
        }
        
        // Convertir **bold** a <strong>
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        
        // Convertir //italic// a <em>
        $text = preg_replace('/\/\/(.*?)\/\//', '<em>$1</em>', $text);
        
        // Si hay URL y texto del enlace, convertir solo el texto del enlace en enlace
        if (!empty($url) && !empty($textoUrl)) {
            // Formatear solo el texto del enlace
            $textoUrlFormateado = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $textoUrl);
            $textoUrlFormateado = preg_replace('/\/\/(.*?)\/\//', '<em>$1</em>', $textoUrlFormateado);
            
            $enlace = '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener noreferrer" style="color: inherit; text-decoration: underline;">' . $textoUrlFormateado . '</a>';
            
            // Reemplazar solo el texto del enlace original con el enlace clickeable
            $text = str_replace($textoUrl, $enlace, $text);
        }
        
        return $text;
    }
}
