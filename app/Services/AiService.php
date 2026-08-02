<?php

namespace App\Services;

use App\Models\AiApiLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected array $providers = [
        'gemini' => [
            // gemini-1.5-* was retired and now returns HTTP 404 from v1beta.
            'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
            'config_key' => 'services.gemini.api_key',
            'model' => 'gemini-2.5-flash',
        ],
        'openai' => [
            'endpoint' => 'https://api.openai.com/v1/chat/completions',
            'config_key' => 'services.openai.api_key',
            'model' => 'gpt-4o-mini',
        ],
        'anthropic' => [
            'endpoint' => 'https://api.anthropic.com/v1/messages',
            'config_key' => 'services.anthropic.api_key',
            'model' => 'claude-3-haiku-20240307',
        ],
    ];

    public function generate(string $prompt, string $provider = 'gemini', array $options = []): ?string
    {
        $startTime = microtime(true);

        try {
            $response = match ($provider) {
                'gemini' => $this->callGemini($prompt, $options),
                'openai' => $this->callOpenAI($prompt, $options),
                'anthropic' => $this->callAnthropic($prompt, $options),
                default => throw new \InvalidArgumentException("Unknown provider: {$provider}"),
            };

            $this->logApiCall($provider, $prompt, $response, microtime(true) - $startTime);

            return $response;

        } catch (\Exception $e) {
            Log::error("AI generation failed", [
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            $this->logApiCall($provider, $prompt, null, microtime(true) - $startTime, $e->getMessage());

            return null;
        }
    }

    public function generateJson(string $prompt, string $provider = 'gemini', array $options = []): ?array
    {
        $response = $this->generate($prompt, $provider, $options);

        if (!$response) {
            return null;
        }

        $jsonMatch = preg_match('/\{[\s\S]*\}/', $response, $matches);
        if ($jsonMatch) {
            try {
                return json_decode($matches[0], true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::warning("Failed to parse JSON from AI response", ['response' => $response]);
                return null;
            }
        }

        return null;
    }

    protected function callGemini(string $prompt, array $options = []): ?string
    {
        $apiKey = config($this->providers['gemini']['config_key']);

        if (!$apiKey) {
            throw new \Exception("Gemini API key not configured");
        }

        $model = $options['model'] ?? config('services.gemini.model', $this->providers['gemini']['model']);
        $endpoint = str_replace('{model}', $model, $this->providers['gemini']['endpoint']);

        $response = Http::timeout(60)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint . '?key=' . $apiKey, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => $options['temperature'] ?? 0.7,
                    'maxOutputTokens' => $options['max_tokens'] ?? 2048,
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception("Gemini API error: " . $response->body());
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    protected function callOpenAI(string $prompt, array $options = []): ?string
    {
        $apiKey = config($this->providers['openai']['config_key']);

        if (!$apiKey) {
            throw new \Exception("OpenAI API key not configured");
        }

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post($this->providers['openai']['endpoint'], [
                'model' => $options['model'] ?? $this->providers['openai']['model'],
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => $options['temperature'] ?? 0.7,
                'max_tokens' => $options['max_tokens'] ?? 2048,
            ]);

        if (!$response->successful()) {
            throw new \Exception("OpenAI API error: " . $response->body());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content'] ?? null;
    }

    protected function callAnthropic(string $prompt, array $options = []): ?string
    {
        $apiKey = config($this->providers['anthropic']['config_key']);

        if (!$apiKey) {
            throw new \Exception("Anthropic API key not configured");
        }

        $response = Http::timeout(60)
            ->withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ])
            ->post($this->providers['anthropic']['endpoint'], [
                'model' => $options['model'] ?? $this->providers['anthropic']['model'],
                'max_tokens' => $options['max_tokens'] ?? 2048,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception("Anthropic API error: " . $response->body());
        }

        $data = $response->json();

        return $data['content'][0]['text'] ?? null;
    }

    protected function logApiCall(
        string $provider,
        string $prompt,
        ?string $response,
        float $duration,
        ?string $error = null
    ): void {
        try {
            AiApiLog::create([
                'provider' => $provider,
                'model' => $this->providers[$provider]['model'] ?? 'default',
                'prompt_tokens' => $this->estimateTokens($prompt),
                'completion_tokens' => $response ? $this->estimateTokens($response) : 0,
                'duration_ms' => (int)($duration * 1000),
                'status' => $error ? 'error' : 'success',
                'error_message' => $error,
            ]);
        } catch (\Exception $e) {
            Log::warning("Failed to log AI API call", ['error' => $e->getMessage()]);
        }
    }

    protected function estimateTokens(string $text): int
    {
        return (int)(strlen($text) / 4);
    }

    public function getProviderForTask(string $taskType): string
    {
        return match ($taskType) {
            'competitor_scan_summary',
            'faq_generation',
            'freshness_update',
            'local_seo_draft' => 'gemini',

            'authority_review_page',
            'investment_lead_magnet',
            'premium_writing' => 'openai',

            'legal_reputation_risk',
            'investor_safe_answer',
            'premium_review' => 'anthropic',

            default => 'gemini',
        };
    }
}
