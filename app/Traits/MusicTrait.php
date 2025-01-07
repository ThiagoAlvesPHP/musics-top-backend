<?php

namespace App\Traits;

use Exception;

trait MusicTrait
{
    /**
     * Extract video ID from a YouTube URL
     */
    public static function extractVideoId($url)
    {
        $videoId = null;

        // YouTube URL Patterns
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/', // youtube.com/watch?v=ID
            '/youtu\.be\/([^?]+)/',            // youtu.be/ID
            '/youtube\.com\/embed\/([^?]+)/',   // youtube.com/embed/ID
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                break;
            }
        }

        return $videoId;
    }

    /**
     * Search video information using web scraping
     */
    public static function getVideoInfo($videoId)
    {
        $url = "https://www.youtube.com/watch?v=" . $videoId;

        // Initialize cURL
        $ch = curl_init();

        // Configure cURL for the request
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ]);

        // Make the request
        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception("Erro ao acessar o YouTube: " . curl_error($ch));
        }

        curl_close($ch);

        // Extract the title
        if (!preg_match('/<title>(.+?) - YouTube<\/title>/', $response, $titleMatches)) {
            throw new Exception("Não foi possível encontrar o título do vídeo");
        }

        $title = html_entity_decode($titleMatches[1], ENT_QUOTES);

        // Extract views
        // Search for the pattern of views in the JSON of the video data
        if (preg_match('/"viewCount":\s*"(\d+)"/', $response, $viewMatches)) {
            $views = (int)$viewMatches[1];
        } else {
            // Try an alternative pattern
            if (preg_match('/\"viewCount\"\s*:\s*{.*?\"simpleText\"\s*:\s*\"([\d,\.]+)\"/', $response, $viewMatches)) {
                $views = (int)str_replace(['.', ','], '', $viewMatches[1]);
            } else {
                $views = 0;
            }
        }

        if ($title === '') {
            throw new Exception("Vídeo não encontrado ou indisponível");
        }

        return [
            'title' => $title,
            'count_views' => $views,
            'youtube_id' => $videoId,
            'thumb' => 'https://img.youtube.com/vi/'.$videoId.'/hqdefault.jpg'
        ];
    }
}
