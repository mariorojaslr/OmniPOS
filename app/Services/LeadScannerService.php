<?php

namespace App\Services;

use App\Models\CrmActivity;
use Illuminate\Support\Str;

/**
 * LEAD SCANNER SERVICE
 * 
 * Motor gratuito de búsqueda de prospectos usando DuckDuckGo HTML (sin API, sin costo).
 * Busca negocios que podrían necesitar un sistema POS en Argentina.
 */
class LeadScannerService
{
    /**
     * Queries de búsqueda por canal.
     * Cada canal tiene queries diferentes para buscar distintos perfiles.
     */
    private $queries = [
        'LinkedIn' => [
            'site:linkedin.com/in "punto de venta" OR "POS" OR "retail" argentina',
            'site:linkedin.com/in "dueño" OR "comerciante" OR "emprendedor" tienda argentina',
            'site:linkedin.com/in "gerente comercial" OR "administrador" negocio minorista argentina',
        ],
        'Instagram' => [
            'site:instagram.com "tienda" OR "local" negocio emprendimiento argentina',
            'site:instagram.com "kiosco" OR "almacen" OR "ferreteria" OR "libreria" argentina',
            'site:instagram.com "indumentaria" OR "ropa" OR "zapatillas" tienda argentina',
        ],
        'Facebook' => [
            'site:facebook.com "punto de venta" OR "sistema POS" necesito argentina',
            'site:facebook.com "negocio" OR "tienda" "busco sistema" comercio argentina',
            'site:facebook.com "emprendimiento" OR "pyme" "venta" argentina',
        ],
        'WhatsApp' => [
            '"necesito sistema punto de venta" OR "busco software comercial" argentina whatsapp',
            '"sistema de ventas" OR "software para negocio" pyme argentina contacto',
        ],
        'Telegram' => [
            'site:t.me "punto de venta" OR "comercio" OR "ventas" argentina',
            '"grupo telegram" "comerciantes" OR "emprendedores" OR "pymes" argentina',
        ],
        'System Mail' => [
            '"punto de venta" OR "software comercial" argentina email contacto @gmail.com',
            '"sistema pos" OR "control stock" negocio argentina @hotmail.com OR @gmail.com',
        ],
    ];

    /**
     * EJECUTAR ESCANEO de un canal específico.
     * Retorna la cantidad de leads nuevos encontrados.
     */
    public function scan(string $channel): array
    {
        $queries = $this->queries[$channel] ?? [];
        $allResults = [];
        $errors = [];

        foreach ($queries as $query) {
            try {
                $results = $this->searchDuckDuckGo($query);
                $allResults = array_merge($allResults, $results);

                // Esperar 2-3 segundos entre queries para no ser bloqueado
                usleep(rand(2000000, 3000000));
            } catch (\Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Almacenar resultados únicos
        $stored = 0;
        $duplicates = 0;
        foreach ($allResults as $result) {
            $exists = CrmActivity::where('target_name', $result['title'])
                ->where('channel', $channel)
                ->exists();

            if (!$exists) {
                CrmActivity::create([
                    'channel'       => $channel,
                    'target_name'   => $result['title'],
                    'target_origin' => $result['url'] ?? 'Web',
                    'details'       => $result['snippet'] ?? 'Sin detalles',
                    'status'        => 'encontrado',
                ]);
                $stored++;
            } else {
                $duplicates++;
            }
        }

        return [
            'channel'    => $channel,
            'found'      => count($allResults),
            'stored'     => $stored,
            'duplicates' => $duplicates,
            'errors'     => $errors,
        ];
    }

    /**
     * ESCANEO COMPLETO de todos los canales.
     */
    public function scanAll(): array
    {
        $summary = [];
        foreach (array_keys($this->queries) as $channel) {
            $summary[$channel] = $this->scan($channel);
        }
        return $summary;
    }

    /**
     * Buscar en DuckDuckGo HTML (GRATIS, sin API key).
     */
    private function searchDuckDuckGo(string $query): array
    {
        $url = 'https://html.duckduckgo.com/html/?q=' . urlencode($query);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml',
                'Accept-Language: es-AR,es;q=0.9,en;q=0.8',
            ],
        ]);

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || $httpCode !== 200 || empty($html)) {
            throw new \Exception("DuckDuckGo error: HTTP {$httpCode} - {$error}");
        }

        return $this->parseDuckDuckGoResults($html);
    }

    /**
     * Parsear los resultados HTML de DuckDuckGo.
     */
    private function parseDuckDuckGoResults(string $html): array
    {
        $results = [];

        // DuckDuckGo HTML usa la clase "result" para cada resultado
        // Extraer bloques de resultados
        preg_match_all(
            '/<a[^>]*class="result__a"[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/si',
            $html,
            $linkMatches,
            PREG_SET_ORDER
        );

        // Extraer snippets
        preg_match_all(
            '/<a[^>]*class="result__snippet"[^>]*>(.*?)<\/a>/si',
            $html,
            $snippetMatches,
            PREG_SET_ORDER
        );

        $maxResults = min(count($linkMatches), 15); // Máximo 15 resultados por query

        for ($i = 0; $i < $maxResults; $i++) {
            $rawUrl = $linkMatches[$i][1] ?? '';
            $title = strip_tags($linkMatches[$i][2] ?? '');
            $snippet = strip_tags($snippetMatches[$i][1] ?? '');

            // DuckDuckGo envuelve las URLs en un redirect, extraer la URL real
            $realUrl = $this->extractRealUrl($rawUrl);

            // Limpiar el título
            $title = trim(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
            $snippet = trim(html_entity_decode($snippet, ENT_QUOTES, 'UTF-8'));

            if (empty($title) || strlen($title) < 5) {
                continue;
            }

            // Intentar extraer emails del snippet
            $emails = $this->extractEmails($snippet);

            // Enriquecer el detalle con emails encontrados
            $detail = $snippet;
            if (!empty($emails)) {
                $detail .= ' | EMAILS: ' . implode(', ', $emails);
            }

            $results[] = [
                'title'   => Str::limit($title, 120),
                'url'     => $realUrl,
                'snippet' => Str::limit($detail, 500),
            ];
        }

        return $results;
    }

    /**
     * Extraer la URL real del redirect de DuckDuckGo.
     */
    private function extractRealUrl(string $duckUrl): string
    {
        // DuckDuckGo usa: //duckduckgo.com/l/?uddg=https%3A%2F%2F...
        if (preg_match('/uddg=([^&]+)/', $duckUrl, $m)) {
            return urldecode($m[1]);
        }
        return $duckUrl;
    }

    /**
     * Extraer emails de un texto.
     */
    private function extractEmails(string $text): array
    {
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
        return array_unique($matches[0] ?? []);
    }

    /**
     * Obtener las queries configuradas (para mostrar en UI).
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * Obtener estadísticas actuales de la base de datos.
     */
    public function getStats(): array
    {
        $stats = [];
        foreach (array_keys($this->queries) as $channel) {
            $stats[$channel] = [
                'total'     => CrmActivity::where('channel', $channel)->count(),
                'hoy'       => CrmActivity::where('channel', $channel)->whereDate('created_at', today())->count(),
                'semana'    => CrmActivity::where('channel', $channel)->where('created_at', '>=', now()->subDays(7))->count(),
                'contactos' => CrmActivity::where('channel', $channel)->where('details', 'like', '%@%')->count(),
            ];
        }
        return $stats;
    }
}
