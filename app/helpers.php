<?php

if (!function_exists('convertYoutubeToEmbed')) {

    function convertYoutubeToEmbed($url)
    {
        if (str_contains($url, 'youtu.be/')) {
            $videoId = explode('youtu.be/', $url)[1];
        } elseif (str_contains($url, 'watch?v=')) {
            $videoId = explode('watch?v=', $url)[1];
        } else {
            return $url;
        }

        $videoId = explode('&', $videoId)[0];

        return "https://www.youtube.com/embed/" . $videoId;
    }
}
