<?php

/**
 * translate_missing.php
 * Run with: php translate_missing.php
 * Translates all keys missing from non-EN language files using OpenAI.
 */

$apiKey = getenv('OPENAI_API_KEY'); // set via environment variable

$langNames = [
    'bs' => 'Bosnian',
    'mk' => 'Macedonian',
    'nl' => 'Dutch',
    'pl' => 'Polish',
    'pt' => 'Portuguese',
    'ru' => 'Russian',
    'sl' => 'Slovenian',
    'sq' => 'Albanian',
    'sr' => 'Serbian',
    'sv' => 'Swedish',
    'tr' => 'Turkish',
    'zh' => 'Chinese (Simplified)',
];

$en = include __DIR__ . '/resources/lang/en/messages.php';

foreach ($langNames as $code => $langName) {
    $file = __DIR__ . "/resources/lang/{$code}/messages.php";
    $existing = file_exists($file) ? include $file : [];

    $missing = array_diff_key($en, $existing);
    if (empty($missing)) {
        echo "{$code}: nothing to translate\n";
        continue;
    }

    echo "{$code} ({$langName}): translating " . count($missing) . " keys...\n";

    // Split into batches of 50 to avoid token limits
    $batches = array_chunk($missing, 50, true);
    $translated = [];

    foreach ($batches as $batchIndex => $batch) {
        $jsonInput = json_encode($batch, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = "You are a professional translator. Translate the following PHP array values from English to {$langName}.\n"
            . "RULES:\n"
            . "- Return ONLY valid JSON object with the same keys\n"
            . "- Keep ALL_CAPS status labels in ALL CAPS (e.g. DRAFT, PASSED, FAILED)\n"
            . "- Keep 'Villa Bit AI', 'Villa Bit Review' untranslated\n"
            . "- Keep technical terms like SEO, FAQ, AI, ChatGPT, Gemini, Google, YouTube, WhatsApp, Stripe, PayPal untranslated\n"
            . "- Do not add any explanation, only the JSON\n\n"
            . $jsonInput;

        $response = callOpenAI($apiKey, $prompt);

        if (!$response) {
            echo "  ERROR on batch {$batchIndex} for {$code}\n";
            // Fallback to English for this batch
            $translated = array_merge($translated, $batch);
            continue;
        }

        $parsed = json_decode($response, true);
        if (!is_array($parsed)) {
            echo "  JSON parse error on batch {$batchIndex} for {$code}: {$response}\n";
            $translated = array_merge($translated, $batch);
            continue;
        }

        $translated = array_merge($translated, $parsed);
        echo "  batch " . ($batchIndex + 1) . "/" . count($batches) . " done\n";

        // Small delay to respect rate limits
        usleep(300000);
    }

    // Merge existing + newly translated
    $final = array_merge($existing, $translated);

    // Write the file
    $output = "<?php\n\nreturn [\n";
    foreach ($final as $key => $value) {
        $escaped = str_replace("'", "\\'", $value);
        $output .= "    '{$key}' => '{$escaped}',\n";
    }
    $output .= "];\n";

    file_put_contents($file, $output);
    echo "  {$code}: written ({$file})\n\n";
}

echo "Done!\n";

function callOpenAI(string $apiKey, string $prompt): ?string
{
    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.3,
        'max_tokens' => 4000,
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        echo "  HTTP {$httpCode}: {$result}\n";
        return null;
    }

    $decoded = json_decode($result, true);
    $content = $decoded['choices'][0]['message']['content'] ?? null;

    // Strip markdown code blocks if present
    $content = preg_replace('/^```json\s*/m', '', $content);
    $content = preg_replace('/^```\s*/m', '', $content);
    $content = trim($content);

    return $content;
}
