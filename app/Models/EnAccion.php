<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnAccion extends Model
{
    use HasFactory;

    protected $table = 'en_accion';

    protected $fillable = [
        'version',
        'url',
        'video'
    ];

    /**
     * Obtener la clase CSS de la plataforma basada en la URL
     */
    public function getPlatformClass()
    {
        $url = strtolower($this->url);
        
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        } elseif (str_contains($url, 'instagram.com')) {
            return 'instagram';
        } elseif (str_contains($url, 'tiktok.com')) {
            return 'tiktok';
        }
        
        return 'youtube'; // Default
    }

    /**
     * Obtener el nombre de la plataforma basada en la URL
     */
    public function getPlatformName()
    {
        $url = strtolower($this->url);
        
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'YouTube';
        } elseif (str_contains($url, 'instagram.com')) {
            return 'Instagram';
        } elseif (str_contains($url, 'tiktok.com')) {
            return 'TikTok';
        }
        
        return 'YouTube'; // Default
    }
}
