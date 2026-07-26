<?php

namespace App\Services\CompetitorIntelligence;

use App\Models\CompetitorUrl;
use App\Models\CompetitorUrlSnapshot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PageExtractorService
{
    protected int $timeout = 30;
    protected string $userAgent = 'Mozilla/5.0 (compatible; VillaBitBot/1.0; +https://villabit.ai/bot)';

    public function extractPage(CompetitorUrl $url): ?CompetitorUrlSnapshot
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'User-Agent' => $this->userAgent,
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5,hr;q=0.3',
                ])
                ->get($url->url);

            if (!$response->successful()) {
                return $this->createErrorSnapshot($url, $response->status());
            }

            $html = $response->body();
            $extracted = $this->extractFromHtml($html);

            return $this->createSnapshot($url, $extracted, $response->status());

        } catch (\Exception $e) {
            Log::error("Page extraction failed for {$url->url}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    protected function extractFromHtml(string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR);
        $xpath = new \DOMXPath($dom);

        return [
            'title' => $this->extractTitle($xpath),
            'meta_description' => $this->extractMetaDescription($xpath),
            'h1' => $this->extractH1($xpath),
            'content' => $this->extractMainContent($xpath, $dom),
            'schema_json' => $this->extractSchemaJson($xpath),
            'cta_text' => $this->extractCtaText($xpath),
            'word_count' => $this->countWords($this->extractMainContent($xpath, $dom)),
        ];
    }

    protected function extractTitle(\DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query('//title');
        if ($nodes->length > 0) {
            return $this->normalizeText($nodes->item(0)->textContent);
        }
        return null;
    }

    protected function extractMetaDescription(\DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query('//meta[@name="description"]/@content');
        if ($nodes->length > 0) {
            return $this->normalizeText($nodes->item(0)->textContent);
        }
        return null;
    }

    protected function extractH1(\DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query('//h1');
        if ($nodes->length > 0) {
            return $this->normalizeText($nodes->item(0)->textContent);
        }
        return null;
    }

    protected function extractMainContent(\DOMXPath $xpath, \DOMDocument $dom): ?string
    {
        $mainSelectors = [
            '//main',
            '//article',
            '//*[@id="content"]',
            '//*[@class="content"]',
            '//*[@id="main"]',
            '//*[@class="main"]',
            '//body',
        ];

        foreach ($mainSelectors as $selector) {
            $nodes = $xpath->query($selector);
            if ($nodes->length > 0) {
                $content = $this->getTextContent($nodes->item(0));
                if (strlen($content) > 100) {
                    return $content;
                }
            }
        }

        return null;
    }

    protected function getTextContent(\DOMNode $node): string
    {
        $text = '';

        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text .= ' ' . $child->textContent;
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $tagName = strtolower($child->nodeName);

                if (!in_array($tagName, ['script', 'style', 'nav', 'header', 'footer', 'aside'])) {
                    $text .= ' ' . $this->getTextContent($child);
                }
            }
        }

        return $this->normalizeText($text) ?? '';
    }

    protected function extractSchemaJson(\DOMXPath $xpath): ?array
    {
        $schemas = [];
        $nodes = $xpath->query('//script[@type="application/ld+json"]');

        foreach ($nodes as $node) {
            try {
                $json = json_decode($node->textContent, true);
                if ($json) {
                    $schemas[] = $json;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return !empty($schemas) ? $schemas : null;
    }

    protected function extractCtaText(\DOMXPath $xpath): ?string
    {
        $ctaSelectors = [
            '//a[contains(@class, "cta")]',
            '//a[contains(@class, "btn")]',
            '//button[contains(@class, "cta")]',
            '//a[contains(@class, "button")]',
            '//a[contains(text(), "Contact")]',
            '//a[contains(text(), "Kontakt")]',
            '//a[contains(text(), "Inquiry")]',
            '//a[contains(text(), "Upit")]',
        ];

        $ctaTexts = [];

        foreach ($ctaSelectors as $selector) {
            $nodes = $xpath->query($selector);
            foreach ($nodes as $node) {
                $text = $this->normalizeText($node->textContent);
                if ($text && strlen($text) < 100) {
                    $ctaTexts[] = $text;
                }
            }
        }

        return !empty($ctaTexts) ? implode(' | ', array_unique($ctaTexts)) : null;
    }

    protected function normalizeText(?string $text): ?string
    {
        if (!$text) {
            return null;
        }

        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text ?: null;
    }

    protected function countWords(?string $text): int
    {
        if (!$text) {
            return 0;
        }

        return str_word_count($text);
    }

    protected function createSnapshot(CompetitorUrl $url, array $extracted, int $httpStatus): CompetitorUrlSnapshot
    {
        return CompetitorUrlSnapshot::create([
            'competitor_url_id' => $url->id,
            'title' => $extracted['title'],
            'title_hash' => $extracted['title'] ? hash('sha256', $extracted['title']) : null,
            'meta_description' => $extracted['meta_description'],
            'meta_description_hash' => $extracted['meta_description'] ? hash('sha256', $extracted['meta_description']) : null,
            'h1' => $extracted['h1'],
            'h1_hash' => $extracted['h1'] ? hash('sha256', $extracted['h1']) : null,
            'content_hash' => $extracted['content'] ? hash('sha256', $extracted['content']) : null,
            'schema_json' => $extracted['schema_json'],
            'schema_hash' => $extracted['schema_json'] ? hash('sha256', json_encode($extracted['schema_json'])) : null,
            'cta_text' => $extracted['cta_text'],
            'cta_hash' => $extracted['cta_text'] ? hash('sha256', $extracted['cta_text']) : null,
            'word_count' => $extracted['word_count'],
            'http_status' => $httpStatus,
            'captured_at' => now(),
        ]);
    }

    protected function createErrorSnapshot(CompetitorUrl $url, int $httpStatus): CompetitorUrlSnapshot
    {
        return CompetitorUrlSnapshot::create([
            'competitor_url_id' => $url->id,
            'http_status' => $httpStatus,
            'captured_at' => now(),
        ]);
    }
}
