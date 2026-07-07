<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

/**
 * InternalUniquenessChecker
 *
 * 100% self-hosted / no Copyscape / no external API.
 *
 * WHAT IT CHECKS:
 * - exact duplicates using SHA-256
 * - copied phrases between a new text and your own stored content
 * - repeated 7-word sequences ("shingles")
 *
 * WHAT IT CANNOT CHECK:
 * - pages across the entire public Internet.
 *   A real Internet-wide check requires a search/crawling index owned by
 *   someone (Copyscape, a search engine API, or your own very expensive crawler).
 */
final class InternalUniquenessChecker
{
    public function __construct(
        private readonly int $shingleWords = 7,
        private readonly float $reviewAtPercent = 5.0,
        private readonly float $failAtPercent = 15.0,
        private readonly array $ignorePhrases = [],
    ) {
        if ($this->shingleWords < 3) {
            throw new InvalidArgumentException('shingleWords must be at least 3.');
        }
    }

    /**
     * @param string $newText New AI text to check.
     * @param iterable<array{id:int|string, title?:string, text:string}> $existingDocuments
     *
     * @return array{
     *   verdict:'passed'|'review'|'failed',
     *   internal_only: true,
     *   exact_duplicate: bool,
     *   words_checked: int,
     *   repeated_new_text_percent: float,
     *   comparison_count: int,
     *   matches: array<int, array{
     *      id:int|string,
     *      title:string,
     *      repeated_new_text_percent:float,
     *      repeated_old_text_percent:float,
     *      shared_phrases:array<int,string>
     *   }>
     * }
     */
    public function check(string $newText, iterable $existingDocuments): array
    {
        $normalizedNew = $this->normalize($newText);
        $newTokens = $this->tokens($normalizedNew);

        if (count($newTokens) < 20) {
            throw new InvalidArgumentException(
                'Text is too short for a reliable uniqueness check. Use at least 20 words.'
            );
        }

        $newHash = hash('sha256', $normalizedNew);
        $newShingles = $this->shingles($newTokens);
        $newShingleCount = max(1, count($newShingles));

        $matches = [];
        $allSharedNewShingleHashes = [];
        $comparisonCount = 0;

        foreach ($existingDocuments as $document) {
            if (!isset($document['id'], $document['text'])) {
                continue;
            }

            $oldNormalized = $this->normalize((string) $document['text']);
            if ($oldNormalized === '') {
                continue;
            }

            $comparisonCount++;

            if (hash_equals($newHash, hash('sha256', $oldNormalized))) {
                return [
                    'verdict' => 'failed',
                    'internal_only' => true,
                    'exact_duplicate' => true,
                    'words_checked' => count($newTokens),
                    'repeated_new_text_percent' => 100.0,
                    'comparison_count' => $comparisonCount,
                    'matches' => [[
                        'id' => $document['id'],
                        'title' => (string) ($document['title'] ?? ''),
                        'repeated_new_text_percent' => 100.0,
                        'repeated_old_text_percent' => 100.0,
                        'shared_phrases' => ['Exact duplicate after normalisation.'],
                    ]],
                ];
            }

            $oldTokens = $this->tokens($oldNormalized);
            if (count($oldTokens) < $this->shingleWords) {
                continue;
            }

            $oldShingles = $this->shingles($oldTokens);

            $shared = array_intersect_key($newShingles, $oldShingles);
            if ($shared === []) {
                continue;
            }

            foreach ($shared as $hash => $_phrase) {
                $allSharedNewShingleHashes[$hash] = true;
            }

            $repeatedNewPercent = round(
                (count($shared) / $newShingleCount) * 100,
                2
            );
            $repeatedOldPercent = round(
                (count($shared) / max(1, count($oldShingles))) * 100,
                2
            );

            $matches[] = [
                'id' => $document['id'],
                'title' => (string) ($document['title'] ?? ''),
                'repeated_new_text_percent' => $repeatedNewPercent,
                'repeated_old_text_percent' => $repeatedOldPercent,
                'shared_phrases' => array_slice(array_values($shared), 0, 6),
            ];
        }

        usort(
            $matches,
            static fn (array $a, array $b): int =>
                $b['repeated_new_text_percent'] <=> $a['repeated_new_text_percent']
        );

        $totalRepeatedNewPercent = round(
            (count($allSharedNewShingleHashes) / $newShingleCount) * 100,
            2
        );

        $verdict = match (true) {
            $totalRepeatedNewPercent >= $this->failAtPercent => 'failed',
            $totalRepeatedNewPercent >= $this->reviewAtPercent => 'review',
            default => 'passed',
        };

        return [
            'verdict' => $verdict,
            'internal_only' => true,
            'exact_duplicate' => false,
            'words_checked' => count($newTokens),
            'repeated_new_text_percent' => $totalRepeatedNewPercent,
            'comparison_count' => $comparisonCount,
            'matches' => array_slice($matches, 0, 10),
        ];
    }

    private function normalize(string $text): string
    {
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = mb_strtolower($text, 'UTF-8');

        foreach ($this->ignorePhrases as $phrase) {
            $phrase = trim((string) $phrase);
            if ($phrase !== '') {
                $text = str_ireplace($phrase, ' ', $text);
            }
        }

        $text = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $text) ?? '';
        $text = preg_replace('/\s+/u', ' ', trim($text)) ?? '';

        return $text;
    }

    /** @return array<int, string> */
    private function tokens(string $normalizedText): array
    {
        if ($normalizedText === '') {
            return [];
        }

        return preg_split('/\s+/u', $normalizedText, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }

    /**
     * @param array<int, string> $tokens
     * @return array<string, string> SHA-1 hash => original phrase
     */
    private function shingles(array $tokens): array
    {
        $count = count($tokens);
        $size = min($this->shingleWords, max(3, intdiv($count, 2)));

        if ($count < $size) {
            return [];
        }

        $result = [];

        for ($i = 0; $i <= $count - $size; $i++) {
            $phrase = implode(' ', array_slice($tokens, $i, $size));
            $result[sha1($phrase)] = $phrase;
        }

        return $result;
    }
}
