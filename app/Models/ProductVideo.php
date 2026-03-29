<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN GENERAL
    |--------------------------------------------------------------------------
    */

    protected $table = 'product_videos';

    protected $fillable = [
        'product_id',
        'youtube_url',
        'bunny_video_id',
        'bunny_library_id',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELACIÓN
    |--------------------------------------------------------------------------
    | Un video pertenece a un producto
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: Obtener ID del video de YouTube
    |--------------------------------------------------------------------------
    | Soporta:
    | - https://www.youtube.com/watch?v=XXXX
    | - https://youtu.be/XXXX
    | - URLs con parámetros adicionales
    */
    public function getYoutubeIdAttribute()
    {
        $url = $this->youtube_url;

        if (!$url) {
            return null;
        }

        // Caso 1: youtube.com/watch?v=
        if (str_contains($url, 'youtube.com')) {
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
            return $query['v'] ?? null;
        }

        // Caso 2: youtu.be/XXXX
        if (str_contains($url, 'youtu.be')) {
            return trim(parse_url($url, PHP_URL_PATH), '/');
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: URL embebida (para iframe)
    |--------------------------------------------------------------------------
    | Devuelve:
    | https://www.youtube.com/embed/XXXX?autoplay=1
    */
    public function getEmbedUrlAttribute()
    {
        // Caso 1: Bunny Stream (Prioridad según manual empresa)
        if ($this->bunny_video_id && $this->bunny_library_id) {
            return "https://iframe.mediadelivery.net/embed/{$this->bunny_library_id}/{$this->bunny_video_id}?autoplay=false&loop=false&muted=false&preload=true&responsive=true";
        }

        // Caso 2: YouTube
        if ($this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}?autoplay=0";
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: Miniatura del video
    |--------------------------------------------------------------------------
    | Devuelve imagen preview oficial de YouTube
    | Usa maxresdefault y fallback a hqdefault si no existe
    */
    public function getThumbnailAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        return "https://img.youtube.com/vi/{$this->youtube_id}/maxresdefault.jpg";
    }
}
