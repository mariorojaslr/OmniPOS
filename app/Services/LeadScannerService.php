<?php

namespace App\Services;

use App\Models\CrmActivity;
use Illuminate\Support\Str;

/**
 * LEAD SCANNER SERVICE v3
 * 
 * Motor gratuito de búsqueda de prospectos usando Startpage (proxy de Google, gratis).
 * Encuentra perfiles de LinkedIn, Instagram, Facebook de negocios argentinos que
 * podrían necesitar un sistema POS.
 */
class LeadScannerService
{
    private $queries = [
        'LinkedIn' => [
            'punto de venta sistema POS negocio Argentina linkedin',
            'emprendedor comercio minorista dueño tienda Argentina linkedin',
            'gerente comercial retail negocio Argentina linkedin',
        ],
        'Instagram' => [
            'tienda local negocio emprendimiento instagram Argentina',
            'kiosco almacen ferreteria libreria instagram Argentina',
            'indumentaria ropa accesorios tienda instagram Argentina',
        ],
        'Facebook' => [
            'punto de venta necesito sistema POS facebook Argentina',
            'negocio tienda comercio emprendimiento facebook Argentina',
            'pyme venta facturacion stock facebook Argentina',
        ],
        'WhatsApp' => [
            'necesito sistema punto de venta software comercial Argentina contacto',
            'sistema ventas software negocio pyme Argentina whatsapp',
        ],
        'Telegram' => [
            'telegram comerciantes emprendedores pymes Argentina',
            'grupo telegram negocios ventas retail Argentina',
        ],
        'System Mail' => [
            'punto de venta software comercial Argentina email contacto',
            'sistema pos control stock inventario Argentina negocio',
        ],
    ];

    private $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.15',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:128.0) Gecko/20100101 Firefox/128.0',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ];

    /**
     * EJECUTAR ESCANEO de un canal.
     */
    public function scan(string $channel): array
    {
        $queries = $this->queries[$channel] ?? [];
        $allResults = [];
        $errors = [];

        foreach ($queries as $query) {
            try {
                $results = $this->searchStartpage($query);
                $allResults = array_merge($allResults, $results);
                // Pausa entre queries (2-4 segundos)
                usleep(rand(2000000, 4000000));
            } catch (\Throwable $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Filtrar duplicados dentro del mismo escaneo por URL
        $seen = [];
        $unique = [];
        foreach ($allResults as $r) {
            $key = md5($r['url'] ?? $r['title']);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $r;
            }
        }

        // Almacenar en BD (solo nuevos)
        $stored = 0;
        $duplicates = 0;
        foreach ($unique as $result) {
            $exists = CrmActivity::where('target_name', $result['title'])
                ->where('channel', $channel)
                ->exists();

            if (!$exists) {
                CrmActivity::create([
                    'channel'       => $channel,
                    'target_name'   => $result['title'],
                    'target_origin' => $result['url'] ?? 'Web',
                    'details'       => $result['snippet'] ?? 'Prospecto encontrado por búsqueda automática.',
                    'status'        => 'encontrado',
                ]);
                $stored++;
            } else {
                $duplicates++;
            }
        }

        return [
            'channel'    => $channel,
            'found'      => count($unique),
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
     * ==========================================================
     * MOTOR PRINCIPAL: STARTPAGE (proxy privado de Google, gratis)
     * ==========================================================
     */
    private function searchStartpage(string $query): array
    {
        $url = 'https://www.startpage.com/sp/search?q=' . urlencode($query) . '&language=espanol';
        $html = $this->makeRequest($url);

        if (empty($html)) {
            throw new \Exception("Startpage no respondió o bloqueó la solicitud.");
        }

        return $this->parseStartpageResults($html);
    }

    /**
     * Parsear resultados de Startpage.
     * Startpage usa: <a class="result-title" href="URL">TITULO</a>
     *                <p class="result-description">SNIPPET</p>
     */
    private function parseStartpageResults(string $html): array
    {
        $results = [];

        // Extraer títulos y URLs
        preg_match_all(
            '/<a[^>]*class="[^"]*result-title[^"]*"[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>/si',
            $html,
            $titleMatches,
            PREG_SET_ORDER
        );

        // Extraer snippets/descripciones
        preg_match_all(
            '/<p[^>]*class="[^"]*result-description[^"]*"[^>]*>(.*?)<\/p>/si',
            $html,
            $snippetMatches,
            PREG_SET_ORDER
        );

        $maxResults = min(count($titleMatches), 15);

        for ($i = 0; $i < $maxResults; $i++) {
            $url = $titleMatches[$i][1] ?? '';
            $title = strip_tags($titleMatches[$i][2] ?? '');
            $snippet = strip_tags($snippetMatches[$i][1] ?? '');

            $title = trim(html_entity_decode($title, ENT_QUOTES, 'UTF-8'));
            $snippet = trim(html_entity_decode($snippet, ENT_QUOTES, 'UTF-8'));

            if (empty($title) || strlen($title) < 5) continue;

            // Filtrar URLs de motores de búsqueda
            if (preg_match('/(google|startpage|bing|yahoo)\.(com|net|org)/i', $url)) continue;

            // Enriquecer con emails y teléfonos encontrados
            $emails = $this->extractEmails($snippet);
            $phones = $this->extractPhones($snippet);

            $detail = $snippet;
            if (!empty($emails)) {
                $detail .= ' | 📧 ' . implode(', ', $emails);
            }
            if (!empty($phones)) {
                $detail .= ' | 📞 ' . implode(', ', $phones);
            }

            $results[] = [
                'title'   => Str::limit($title, 150),
                'url'     => $url,
                'snippet' => Str::limit($detail, 500),
            ];
        }

        return $results;
    }

    /**
     * HTTP Request con user-agent rotativo.
     */
    private function makeRequest(string $url): string
    {
        $ua = $this->userAgents[array_rand($this->userAgents)];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => $ua,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: es-AR,es;q=0.9,en;q=0.8',
                'Accept-Encoding: identity',
                'Cache-Control: no-cache',
                'DNT: 1',
            ],
            CURLOPT_ENCODING       => '',
        ]);

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($html)) {
            return '';
        }

        return $html;
    }

    private function extractEmails(string $text): array
    {
        preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches);
        return array_unique($matches[0] ?? []);
    }

    private function extractPhones(string $text): array
    {
        preg_match_all('/(?:\+?\d{1,3}[-.\s]?)?\(?\d{2,4}\)?[-.\s]?\d{3,4}[-.\s]?\d{3,4}/', $text, $matches);
        $phones = array_unique($matches[0] ?? []);
        return array_values(array_filter($phones, fn($p) => strlen(preg_replace('/\D/', '', $p)) >= 8));
    }

    public function getQueries(): array
    {
        return $this->queries;
    }

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
