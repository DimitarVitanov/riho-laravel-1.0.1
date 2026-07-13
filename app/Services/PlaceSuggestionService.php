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
        // Always include the primary location as the FIRST suggestion
        $primaryPlace = [
            'name' => $primaryCity,
            'type' => 'Primary Area',
            'distance' => '0 km',
            'reason' => 'Your selected primary market location',
            'priority' => 'HIGH',
        ];
        $apiKey = config('ai.google.api_key');

        if (empty($apiKey)) {
            return [$primaryPlace];
        }

        $model = config('ai.google.default_model') ?: 'gemini-2.5-flash';
        // Older Gemini models are retired on the v1beta generateContent endpoint; map to a current one.
        $retired = ['gemini-pro', 'gemini-1.5-flash', 'gemini-1.5-pro', 'gemini-1.0-pro'];
        if (in_array($model, $retired, true)) {
            $model = 'gemini-2.5-flash';
        }

        $location = trim($primaryCity . ($country ? ', ' . $country : ''));

        $prompt = "You are a local real-estate market expert for {$location}. "
            . "The user selected '{$primaryCity}' as their primary market. "
            . "Your task: suggest up to {$limit} SMALLER areas, neighborhoods, districts, or sub-localities WITHIN or very close to '{$primaryCity}'. "
            . "Rules: "
            . "- If '{$primaryCity}' is a CITY or large town: suggest its neighborhoods, districts, quarters, and nearby small villages/suburbs within {$coverage} {$unit}. "
            . "- If '{$primaryCity}' is a NEIGHBORHOOD or district: suggest streets, micro-areas, or adjacent neighborhoods within {$coverage} {$unit}. "
            . "- If '{$primaryCity}' is a STREET: suggest nearby streets and intersections within {$coverage} {$unit}. "
            . "- Do NOT suggest larger cities that contain '{$primaryCity}'. Only suggest places at the same level or smaller. "
            . "- Focus on real, existing place names that locals would recognize. "
            . "For each place return: name, type (e.g. Neighborhood, District, Quarter, Village, Suburb, Street, Area), "
            . "approximate distance from {$primaryCity} center (e.g. '2 km' or '500 m'), a short reason why it is relevant for real estate, "
            . "and priority (HIGH, MEDIUM, or LOW based on real estate activity). "
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
                        $places = $this->parsePlaces($text, $limit - 1); // -1 to leave room for primary
                        if (!empty($places)) {
                            // Prepend primary place, filter out duplicates
                            $places = array_filter($places, fn($p) => strtolower($p['name']) !== strtolower($primaryCity));
                            return array_merge([$primaryPlace], array_values($places));
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

        // Even if AI fails, return the primary place
        return [$primaryPlace];
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
