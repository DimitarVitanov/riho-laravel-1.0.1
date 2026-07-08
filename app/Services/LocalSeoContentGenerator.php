<?php

namespace App\Services;

use App\Models\LocalSeoCampaign;
use App\Models\AgencyProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalSeoContentGenerator
{
    protected string $openaiKey;
    protected string $geminiKey;
    protected string $anthropicKey;

    public function __construct()
    {
        $this->openaiKey = config('services.openai.api_key', '');
        $this->geminiKey = config('services.gemini.api_key', '');
        $this->anthropicKey = config('services.anthropic.api_key', '');
    }

    /**
     * Generate all AI content for a campaign.
     */
    public function generateForCampaign(LocalSeoCampaign $campaign, AgencyProfile $profile): array
    {
        $results = [
            'hero_content' => null,
            'area_descriptions' => [],
            'faq_content' => [],
            'about_content' => null,
            'meta_description' => null,
            'seo_title' => null,
            'generated_at' => now()->toIso8601String(),
            'word_count' => 0,
        ];

        $language = $profile->ai_content_language ?? 'English';
        $city = $campaign->primary_city ?? 'this area';
        $agencyName = $profile->agency_name ?? 'Our Agency';
        $subPrompt = $campaign->positioning_note ?? '';

        try {
            // 1. Generate hero/intro content
            $results['hero_content'] = $this->generateHero($city, $agencyName, $subPrompt, $language);
            usleep(500000); // 0.5s delay to avoid rate limits

            // 2. Generate area descriptions for each target place (limit to 6 to avoid rate limits)
            $places = array_slice($campaign->target_places ?? [], 0, 6);
            foreach ($places as $index => $place) {
                $name = $place['name'] ?? '';
                if (empty($name)) continue;
                
                $results['area_descriptions'][$index] = $this->generateAreaDescription(
                    $name, $city, $subPrompt, $language
                );
                usleep(500000); // 0.5s delay
            }

            // 3. Generate FAQ content
            $results['faq_content'] = $this->generateFaqs($city, $agencyName, $subPrompt, $language);
            usleep(500000); // 0.5s delay

            // 4. Generate about section
            $results['about_content'] = $this->generateAbout($city, $agencyName, $campaign, $subPrompt, $language);

            // 5. Generate SEO meta
            $results['meta_description'] = $this->generateMetaDescription($city, $agencyName, $subPrompt, $language);
            $results['seo_title'] = "{$campaign->name} | {$agencyName} - Real Estate in {$city}";

            // Calculate word count
            $allText = $results['hero_content'] . ' ' . $results['about_content'] . ' ' . $results['meta_description'];
            foreach ($results['area_descriptions'] as $desc) {
                $allText .= ' ' . ($desc['description'] ?? '');
            }
            foreach ($results['faq_content'] as $faq) {
                $allText .= ' ' . ($faq['question'] ?? '') . ' ' . ($faq['answer'] ?? '');
            }
            $results['word_count'] = str_word_count($allText);

            // Save to campaign
            $campaign->update([
                'ai_generated_content' => $results,
                'content_generated_at' => now(),
            ]);

        } catch (\Exception $e) {
            Log::error('LocalSeoContentGenerator error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Generate hero/intro paragraph.
     */
    protected function generateHero(string $city, string $agencyName, string $subPrompt, string $language): string
    {
        $prompt = "Write a compelling 2-3 sentence introduction for a real estate page about {$city}. Agency: {$agencyName}. Focus on: local expertise, property opportunities, trusted guidance.";
        
        if ($subPrompt) {
            $prompt .= " Additional focus: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Be professional and engaging.";

        $result = $this->callAnthropic($prompt, 150) ?? $this->callGemini($prompt, 100) ?? $this->callOpenAI($prompt, 100);
        
        return $result ?? "Explore real estate opportunities in {$city} with {$agencyName}. We offer local expertise and trusted guidance for buyers, sellers, and investors.";
    }

    /**
     * Generate description for a specific area/place.
     */
    protected function generateAreaDescription(string $placeName, string $city, string $subPrompt, string $language): array
    {
        $prompt = "Write 2 sentences about why '{$placeName}' near {$city} is attractive for real estate buyers. Include: location benefits, lifestyle, investment potential.";
        
        if ($subPrompt) {
            $prompt .= " Focus on: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Be specific and factual.";

        $result = $this->callAnthropic($prompt, 100) ?? $this->callGemini($prompt, 60) ?? $this->callOpenAI($prompt, 60);

        return [
            'name' => $placeName,
            'description' => $result ?? "{$placeName} offers excellent real estate opportunities near {$city}. The area combines convenient location with attractive lifestyle options.",
        ];
    }

    /**
     * Generate FAQ content.
     */
    protected function generateFaqs(string $city, string $agencyName, string $subPrompt, string $language): array
    {
        // Default FAQs as fallback
        $defaultFaqs = [
            ['question' => "What types of properties are available in {$city}?", 'answer' => "The {$city} market offers apartments, villas, houses, and land plots. {$agencyName} specializes in matching buyers with the right property."],
            ['question' => "What is the average property price in {$city}?", 'answer' => "Prices vary by location, size, and condition. Contact {$agencyName} for current market analysis."],
            ['question' => "Is {$city} good for real estate investment?", 'answer' => "{$city} offers strong investment potential with growing demand and tourism appeal."],
            ['question' => "What is the buying process for foreign buyers?", 'answer' => "Foreign buyers can purchase with proper documentation. {$agencyName} guides you through the entire process."],
            ['question' => "How long does a property transaction take?", 'answer' => "A standard transaction takes 30-60 days from offer to completion."],
            ['question' => "Do you offer property management?", 'answer' => "Yes, {$agencyName} offers rental management, maintenance, and tenant relations."],
        ];

        $topics = [
            "property types available in {$city}",
            "average property prices in {$city}",
            "investment potential in {$city}",
            "buying process for foreign buyers",
            "typical transaction timeline",
            "property management services",
        ];

        $faqs = [];
        foreach ($topics as $index => $topic) {
            $prompt = "Generate 1 FAQ about {$topic}. Agency: {$agencyName}.";
            if ($subPrompt) {
                $prompt .= " Context: {$subPrompt}";
            }
            $prompt .= " Language: {$language}. Return JSON: {\"question\": \"...\", \"answer\": \"...\"}";

            $response = $this->callAnthropic($prompt, 150) ?? $this->callGemini($prompt, 120) ?? $this->callOpenAI($prompt, 120);
            
            // Parse JSON if we got a response
            if ($response) {
                $json = $this->extractJson($response);
                if ($json && isset($json['question']) && isset($json['answer'])) {
                    $faqs[] = $json;
                    continue;
                }
            }
            
            // Use default FAQ as fallback
            if (isset($defaultFaqs[$index])) {
                $faqs[] = $defaultFaqs[$index];
            }
        }

        return $faqs;
    }

    /**
     * Generate about section content.
     */
    protected function generateAbout(string $city, string $agencyName, LocalSeoCampaign $campaign, string $subPrompt, string $language): string
    {
        $coverage = $campaign->coverage_area ? "{$campaign->coverage_area} {$campaign->coverage_unit}" : 'the surrounding area';
        
        $prompt = "Write a professional 3-4 sentence about section for {$agencyName}, a real estate agency in {$city}. Coverage: {$coverage}. Focus: local expertise, market knowledge, client trust.";
        
        if ($subPrompt) {
            $prompt .= " Emphasize: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}.";

        $result = $this->callAnthropic($prompt, 200) ?? $this->callGemini($prompt, 120) ?? $this->callOpenAI($prompt, 120);
        
        return $result ?? "{$agencyName} is a trusted real estate agency serving {$city} and {$coverage}. We combine local market expertise with personalized service to help you find the perfect property.";
    }

    /**
     * Generate meta description.
     */
    protected function generateMetaDescription(string $city, string $agencyName, string $subPrompt, string $language): string
    {
        $prompt = "Write a 155-character SEO meta description for {$agencyName}'s real estate page about {$city}.";
        
        if ($subPrompt) {
            $prompt .= " Focus: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Include call-to-action.";

        $result = $this->callAnthropic($prompt, 80) ?? $this->callGemini($prompt, 50) ?? $this->callOpenAI($prompt, 50);
        $result = $result ?? "Discover real estate in {$city} with {$agencyName}. Expert local guidance for buyers, sellers and investors. Contact us today!";
        return substr($result, 0, 160);
    }

    /**
     * Call Gemini API (cheaper, use for drafts).
     */
    protected function callGemini(string $prompt, int $maxTokens = 100): ?string
    {
        if (empty($this->geminiKey)) {
            Log::info('Gemini API key not configured');
            return null;
        }

        try {
            Log::info('Calling Gemini API...');
            // Use gemini-2.0-flash or gemini-1.5-flash-latest
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->geminiKey}",
                [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'maxOutputTokens' => $maxTokens,
                        'temperature' => 0.7,
                    ],
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                Log::info('Gemini response received', ['length' => strlen($text ?? '')]);
                return $text;
            } else {
                Log::warning('Gemini API failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Call OpenAI API (premium, use for final polish).
     */
    protected function callOpenAI(string $prompt, int $maxTokens = 100): ?string
    {
        if (empty($this->openaiKey)) {
            Log::info('OpenAI API key not configured');
            return null;
        }

        try {
            Log::info('Calling OpenAI API...');
            $response = Http::timeout(30)
                ->withHeaders(['Authorization' => "Bearer {$this->openaiKey}"])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a professional real estate copywriter. Write concise, engaging content.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => $maxTokens,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? null;
                Log::info('OpenAI response received', ['length' => strlen($text ?? '')]);
                return $text;
            } else {
                Log::warning('OpenAI API failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::warning('OpenAI API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Call Anthropic Claude API.
     */
    protected function callAnthropic(string $prompt, int $maxTokens = 100): ?string
    {
        if (empty($this->anthropicKey)) {
            Log::info('Anthropic API key not configured');
            return null;
        }

        try {
            Log::info('Calling Anthropic Claude API...');
            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->anthropicKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model' => 'claude-3-5-sonnet-20241022',
                    'max_tokens' => $maxTokens,
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['content'][0]['text'] ?? null;
                Log::info('Anthropic response received', ['length' => strlen($text ?? '')]);
                return $text;
            } else {
                Log::warning('Anthropic API failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::warning('Anthropic API error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Extract JSON from AI response.
     */
    protected function extractJson(string $text): ?array
    {
        // Find JSON in response
        if (preg_match('/\{[^{}]*\}/', $text, $matches)) {
            try {
                return json_decode($matches[0], true);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
