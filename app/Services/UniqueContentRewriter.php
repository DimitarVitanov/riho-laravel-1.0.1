<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * UniqueContentRewriter
 *
 * When Google check finds similar content, this service rewrites
 * the problematic phrases to make them unique.
 * 
 * Uses OpenAI to intelligently rewrite while keeping meaning.
 */
final class UniqueContentRewriter
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', env('OPENAI_API_KEY', ''));
        $this->model = config('services.openai.model', 'gpt-4o-mini');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Rewrite text to make it unique, avoiding phrases found on Google.
     *
     * @param string $originalText The original AI-generated text
     * @param array $problematicPhrases Phrases that were found on Google
     * @return array{
     *   success: bool,
     *   rewritten_text: string|null,
     *   changes_made: array<string, string>,
     *   error: string|null
     * }
     */
    public function rewrite(string $originalText, array $problematicPhrases): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'rewritten_text' => null,
                'changes_made' => [],
                'error' => 'OpenAI API not configured.',
            ];
        }

        if (empty($problematicPhrases)) {
            return [
                'success' => true,
                'rewritten_text' => $originalText,
                'changes_made' => [],
                'error' => null,
            ];
        }

        $phrasesForPrompt = implode("\n", array_map(fn($p) => "- \"{$p}\"", $problematicPhrases));

        $prompt = <<<PROMPT
You are a professional content rewriter. Your task is to rewrite ONLY the specific phrases that were found to be duplicated on Google, while keeping the rest of the text exactly the same.

ORIGINAL TEXT:
{$originalText}

PHRASES TO REWRITE (these were found on Google's first page):
{$phrasesForPrompt}

INSTRUCTIONS:
1. Find each problematic phrase in the original text
2. Rewrite ONLY those phrases to be unique while keeping the same meaning
3. Keep the same tone, style, and formatting
4. Do NOT change any other parts of the text
5. The rewritten phrases should sound natural and professional

Return ONLY the complete rewritten text, nothing else.
PROMPT;

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional content rewriter specializing in creating unique, SEO-friendly content. You rewrite only the specified phrases while maintaining the original meaning and style.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 4000,
                ]);

            if (!$response->successful()) {
                Log::error('OpenAI rewrite failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'rewritten_text' => null,
                    'changes_made' => [],
                    'error' => 'OpenAI API request failed: HTTP ' . $response->status(),
                ];
            }

            $data = $response->json();
            $rewrittenText = $data['choices'][0]['message']['content'] ?? null;

            if (!$rewrittenText) {
                return [
                    'success' => false,
                    'rewritten_text' => null,
                    'changes_made' => [],
                    'error' => 'No response from OpenAI.',
                ];
            }

            // Detect what was changed
            $changesMade = $this->detectChanges($originalText, $rewrittenText, $problematicPhrases);

            return [
                'success' => true,
                'rewritten_text' => trim($rewrittenText),
                'changes_made' => $changesMade,
                'error' => null,
            ];

        } catch (\Exception $e) {
            Log::error('OpenAI rewrite exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'rewritten_text' => null,
                'changes_made' => [],
                'error' => 'Rewrite failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Quick rewrite of specific phrases only (returns phrase mappings).
     */
    public function rewritePhrases(array $phrases): array
    {
        if (!$this->isConfigured() || empty($phrases)) {
            return [];
        }

        $phrasesText = implode("\n", array_map(fn($p, $i) => ($i + 1) . ". \"{$p}\"", $phrases, array_keys($phrases)));

        $prompt = <<<PROMPT
Rewrite each phrase below to be unique while keeping the exact same meaning. 
Return ONLY the rewritten phrases, one per line, in the same order.

{$phrasesText}
PROMPT;

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.8,
                    'max_tokens' => 1000,
                ]);

            if (!$response->successful()) {
                return [];
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? '';
            $rewrittenLines = array_filter(array_map('trim', explode("\n", $content)));

            $result = [];
            foreach ($phrases as $index => $original) {
                $rewritten = $rewrittenLines[$index] ?? null;
                if ($rewritten) {
                    // Clean up numbering if present
                    $rewritten = preg_replace('/^\d+\.\s*/', '', $rewritten);
                    $rewritten = trim($rewritten, '"\'');
                    $result[$original] = $rewritten;
                }
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Phrase rewrite failed', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Detect which phrases were changed.
     */
    private function detectChanges(string $original, string $rewritten, array $targetPhrases): array
    {
        $changes = [];

        foreach ($targetPhrases as $phrase) {
            // Check if original phrase is no longer in the rewritten text
            if (stripos($original, $phrase) !== false && stripos($rewritten, $phrase) === false) {
                $changes[$phrase] = '[rewritten]';
            }
        }

        return $changes;
    }
}
