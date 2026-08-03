<?php

namespace App\Services\Est8ads;

use App\Services\AiService;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Extracts the translatable UI strings from the EST8ADS public marketing
 * pages (nav, footer, homepage, contact) and AI-translates them into every
 * supported language, writing standard Laravel `lang/{locale}.json` files.
 *
 * The property-move intake form and the Terms of Use / Privacy Policy legal
 * body are intentionally excluded: the form's option text doubles as a
 * literal value checked by IntakeController's validation rules, and legal
 * text is kept in English until a certified translation is available.
 */
class PublicPageTranslator
{
    /** @var array<int, string> */
    private const SOURCE_VIEWS = [
        'est8ads/public/partials/nav.blade.php',
        'est8ads/public/partials/footer.blade.php',
        'est8ads/public/index.blade.php',
        'est8ads/public/contact.blade.php',
        'est8ads/public/terms.blade.php',
        'est8ads/public/privacy.blade.php',
    ];

    public function __construct(private AiService $ai)
    {
    }

    /**
     * Scans the public blade views for `__('...')` / `__("...")` calls and
     * returns the de-duplicated, sorted list of English source strings.
     *
     * @return array<int, string>
     */
    public function extractStrings(): array
    {
        $strings = [];

        foreach (self::SOURCE_VIEWS as $relative) {
            $path = resource_path('views/' . $relative);

            if (! File::exists($path)) {
                continue;
            }

            $source = File::get($path);

            if (preg_match_all('/__\(\'((?:[^\'\\\\]|\\\\.)*)\'/', $source, $matches)) {
                foreach ($matches[1] as $raw) {
                    $strings[$this->unescape($raw, "'")] = true;
                }
            }

            if (preg_match_all('/__\("((?:[^"\\\\]|\\\\.)*)"/', $source, $matches)) {
                foreach ($matches[1] as $raw) {
                    $strings[$this->unescape($raw, '"')] = true;
                }
            }
        }

        $list = array_keys($strings);
        sort($list);

        return $list;
    }

    private function unescape(string $raw, string $quote): string
    {
        return str_replace(['\\' . $quote, '\\\\'], [$quote, '\\'], $raw);
    }

    /**
     * Translates every string into the given language via a single
     * synchronous AI call. Convenient for one-off/manual use; the console
     * command uses buildPrompt()/requestMany()/parseResponse() instead so it
     * can translate many languages concurrently.
     *
     * @param  array<int, string>  $strings
     * @return array<string, string>|null Null when the AI call failed.
     */
    public function translate(string $languageName, array $strings): ?array
    {
        try {
            $result = $this->ai->generateJson(
                $this->buildPrompt($languageName, $strings),
                (string) config('est8ads.discovery.ai_provider', 'openai'),
                ['temperature' => 0.2, 'max_tokens' => 8000],
            );
        } catch (Throwable) {
            return null;
        }

        return is_array($result) ? array_map(fn ($value) => (string) $value, $result) : null;
    }

    /**
     * @param  array<int, string>  $strings
     */
    public function buildPrompt(string $languageName, array $strings): string
    {
        $source = json_encode(array_values($strings), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Translate every string in this JSON array from English into {$languageName} for a real-estate
marketing website. Keep the tone professional and concise.

Rules:
- Return a single JSON object where each key is the exact original English string and each value
  is its {$languageName} translation.
- Keep every HTML tag (e.g. <strong>, <em>, <br>) exactly as it appears, only translate the visible text.
- Keep every ":placeholder" token (e.g. ":url") exactly as it appears, do not translate or remove it.
- Do not translate the brand names "EST8ADS" or "Villa Bit AI".
- Keep dollar amounts, dates and version numbers unchanged.
- Every original string must appear as a key in your JSON object.

STRINGS:
{$source}

Respond with JSON only.
PROMPT;
    }

    /**
     * Fires one OpenAI chat-completion request per prompt concurrently
     * (Http::pool), so translating many languages takes roughly as long as
     * translating one.
     *
     * @param  array<string, string>  $prompts  key => prompt (the key is only used to line the responses back up)
     * @return array<string, Response|null>
     */
    public function requestMany(array $prompts): array
    {
        $apiKey = config('services.openai.api_key');

        if (! $apiKey || $prompts === []) {
            return [];
        }

        $keys = array_keys($prompts);

        $responses = Http::pool(fn (Pool $pool) => array_map(
            fn (string $key) => $pool->as($key)
                ->timeout(120)
                ->withHeaders(['Authorization' => 'Bearer ' . $apiKey, 'Content-Type' => 'application/json'])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [['role' => 'user', 'content' => $prompts[$key]]],
                    'temperature' => 0.2,
                    'max_tokens' => 8000,
                ]),
            $keys,
        ));

        $result = [];
        foreach ($keys as $key) {
            $response = $responses[$key] ?? null;
            $result[$key] = $response instanceof Response ? $response : null;
        }

        return $result;
    }

    /**
     * @return array<string, string>|null
     */
    public function parseResponse(?Response $response): ?array
    {
        if (! $response || $response->failed()) {
            return null;
        }

        $content = $response->json('choices.0.message.content');

        if (! is_string($content) || trim($content) === '') {
            return null;
        }

        if (! preg_match('/\{[\s\S]*\}/', $content, $matches)) {
            return null;
        }

        try {
            $decoded = json_decode($matches[0], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        return is_array($decoded) ? array_map(fn ($value) => (string) $value, $decoded) : null;
    }
}
