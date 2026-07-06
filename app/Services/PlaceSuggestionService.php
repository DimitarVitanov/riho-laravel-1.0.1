<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceSuggestionService
{
    /**
     * Suggest relevant places (cities, towns, islands, coastal areas, neighborhoods)
     * within the given coverage radius around a primary market, using Gemini.
     *
     * @return array<int, array{name:string,type:string,distance:string,reason:string,priority:string}>
     */
    public function suggest(string $primaryCity, ?string $country, int $coverage, string $unit = 'km', int $limit = 12): array
    {
        $apiKey = config('ai.google.api_key');

        if (empty($apiKey)) {
            return [];
        }

        $model = config('ai.google.default_model') ?: 'gemini-2.5-flash';
        // Older Gemini models are retired on the v1beta generateContent endpoint; map to a current one.
        $retired = ['gemini-pro', 'gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-1.0-pro'];
        if (in_array($model, $retired, true)) {
            $model = 'gemini-2.5-flash';
        }

        $location = trim($primaryCity . ($country ? ', ' . $country : ''));

        $prompt = "You are a local real-estate market expert. List up to {$limit} real, existing places "
            . "(cities, towns, villages, islands, coastal areas, or neighborhoods) within {$coverage} {$unit} of {$location}. "
            . "For each place return: name, type (e.g. City, Town, Island, Coastal Area, Neighborhood), "
            . "approximate distance from {$primaryCity} (e.g. '27 km'), a short reason why it is relevant for a real estate agency there, "
            . "and priority (HIGH, MEDIUM, or LOW). "
            . "Return ONLY valid JSON: an array of objects with keys name, type, distance, reason, priority. No markdown, no commentary.";

        $payload = [
            'contents' => [
                ['parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'responseMimeType' => 'application/json',
            ],
        ];

        // gemini-2.5-flash is the primary model; fall back to flash-latest if it's overloaded.
        $models = array_values(array_unique([$model, 'gemini-flash-latest', 'gemini-2.5-flash']));

        foreach ($models as $candidate) {
            for ($attempt = 1; $attempt <= 3; $attempt++) {
                try {
                    $response = Http::timeout(45)
                        ->post("https://generativelanguage.googleapis.com/v1beta/models/{$candidate}:generateContent?key={$apiKey}", $payload);

                    if ($response->successful()) {
                        $text = data_get($response->json(), 'candidates.0.content.parts.0.text', '');
                        $places = $this->parsePlaces($text, $limit);
                        if (!empty($places)) {
                            return $places;
                        }
                        // Empty parse — try again / next model.
                        Log::warning('PlaceSuggestionService empty parse', ['model' => $candidate, 'text' => mb_substr($text, 0, 300)]);
                        break;
                    }

                    // Retry only on transient overload/rate-limit; otherwise move to next model.
                    if (in_array($response->status(), [429, 500, 503], true)) {
                        Log::info("PlaceSuggestionService transient {$response->status()} on {$candidate}, attempt {$attempt}");
                        usleep(600000 * $attempt); // 0.6s, 1.2s, 1.8s backoff
                        continue;
                    }

                    Log::warning('PlaceSuggestionService Gemini error', ['model' => $candidate, 'status' => $response->status(), 'body' => mb_substr($response->body(), 0, 300)]);
                    break;
                } catch (\Throwable $e) {
                    Log::warning('PlaceSuggestionService exception: ' . $e->getMessage());
                    break;
                }
            }
        }

        return [];
    }

    protected function parsePlaces(string $text, int $limit): array
    {
        $text = trim($text);
        // Strip markdown code fences if present.
        $text = preg_replace('/^```(?:json)?|```$/m', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);
        if (!is_array($data)) {
            return [];
        }

        $places = [];
        foreach ($data as $item) {
            if (!is_array($item) || empty($item['name'])) {
                continue;
            }
            $places[] = [
                'name' => (string) $item['name'],
                'type' => (string) ($item['type'] ?? ''),
                'distance' => (string) ($item['distance'] ?? ''),
                'reason' => (string) ($item['reason'] ?? ''),
                'priority' => strtoupper((string) ($item['priority'] ?? 'MEDIUM')),
            ];
            if (count($places) >= $limit) {
                break;
            }
        }

        return $places;
    }
}
