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
            'main_article' => null,
            'quick_answers' => [],
            'area_descriptions' => [],
            'area_comparison' => [],
            'faq_content' => [],
            'about_content' => null,
            'market_snapshot' => [],
            'buyer_fit' => [],
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

            // 2. Generate main article
            $results['main_article'] = $this->generateMainArticle($city, $agencyName, $subPrompt, $language);
            usleep(500000);

            // 3. Generate quick answers (for "Your Questions, Answered" section)
            $results['quick_answers'] = $this->generateQuickAnswers($city, $agencyName, $subPrompt, $language);
            usleep(500000);

            // 4. Generate area descriptions for each target place (limit to 6 to avoid rate limits)
            $places = array_slice($campaign->target_places ?? [], 0, 6);
            foreach ($places as $index => $place) {
                $name = $place['name'] ?? '';
                if (empty($name)) continue;
                
                $results['area_descriptions'][$index] = $this->generateAreaDescription(
                    $name, $city, $subPrompt, $language
                );
                usleep(500000); // 0.5s delay
            }

            // 5. Generate market snapshot
            $results['market_snapshot'] = $this->generateMarketSnapshot($city, $subPrompt, $language);
            usleep(500000);

            // 6. Generate buyer fit info
            $results['buyer_fit'] = $this->generateBuyerFit($city, $subPrompt, $language);
            usleep(500000);

            // 7. Generate area comparison data (unique for each place)
            $results['area_comparison'] = $this->generateAreaComparison($places, $city, $subPrompt, $language);
            usleep(500000);

            // 8. Generate FAQ content
            $results['faq_content'] = $this->generateFaqs($city, $agencyName, $subPrompt, $language);
            usleep(500000); // 0.5s delay

            // 8. Generate about section
            $results['about_content'] = $this->generateAbout($city, $agencyName, $campaign, $subPrompt, $language);

            // 9. Generate SEO meta
            $results['meta_description'] = $this->generateMetaDescription($city, $agencyName, $subPrompt, $language);
            $results['seo_title'] = "{$campaign->name} | {$agencyName} - Real Estate in {$city}";

            // Calculate word count
            $allText = ($results['hero_content'] ?? '') . ' ' . ($results['main_article'] ?? '') . ' ' . ($results['about_content'] ?? '') . ' ' . ($results['meta_description'] ?? '');
            foreach ($results['area_descriptions'] as $desc) {
                $allText .= ' ' . ($desc['description'] ?? '');
            }
            foreach ($results['quick_answers'] as $qa) {
                $allText .= ' ' . ($qa['question'] ?? '') . ' ' . ($qa['answer'] ?? '');
            }
            foreach ($results['faq_content'] as $faq) {
                $allText .= ' ' . ($faq['question'] ?? '') . ' ' . ($faq['answer'] ?? '');
            }
            $allText .= ' ' . ($results['market_snapshot']['price_range'] ?? '') . ' ' . ($results['buyer_fit']['best_for'] ?? '');
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
     * Generate FAQ content with specific numbers and stats.
     */
    protected function generateFaqs(string $city, string $agencyName, string $subPrompt, string $language): array
    {
        // Default FAQs with specific numbers as fallback
        $defaultFaqs = [
            ['question' => "What types of properties are available in {$city}?", 'answer' => "In {$city}, you'll find modern apartments (50-150m²), traditional stone houses, villas with pools, and building plots. About 60% of sales are apartments, with 2-bedroom units (65-85m²) being most popular."],
            ['question' => "What is the average price per m² in {$city}?", 'answer' => "Prices in {$city} range from €2,500-€4,000/m² for standard apartments, €4,000-€6,500/m² for sea-view properties, and €6,000-€10,000/m² for premium waterfront locations."],
            ['question' => "What rental yield can I expect in {$city}?", 'answer' => "Well-located properties in {$city} can achieve 5-8% gross rental yield. A 70m² sea-view apartment renting at €120-180/night during peak season (June-September) can generate €15,000-25,000 annually."],
            ['question' => "What are the buying costs for foreign buyers?", 'answer' => "Total buying costs are typically 6-8% of purchase price: 3% property transfer tax, 1-2% notary fees, 1-2% legal fees, and 2-3% agency commission. EU citizens have the same rights as locals."],
            ['question' => "How long does a property transaction take?", 'answer' => "A standard transaction takes 30-60 days: 1-2 weeks for due diligence, 2-3 weeks for contract preparation, and 2-4 weeks for land registry transfer. Cash purchases can close in 3-4 weeks."],
            ['question' => "What property management services are available?", 'answer' => "{$agencyName} offers full rental management at 15-25% of rental income, including guest communication, cleaning (€50-80 per turnover), maintenance, and 24/7 emergency support."],
        ];

        $prompt = "Generate 6 FAQ pairs for real estate buyers in {$city}. Agency: {$agencyName}.

IMPORTANT: Each answer MUST include SPECIFIC NUMBERS and STATISTICS:
- Price ranges in € per m²
- Percentages (rental yields, costs, etc.)
- Timeframes (days, weeks, months)
- Size ranges in m²
- Rental income estimates
- Transaction costs breakdown

Topics to cover:
1. Property types and sizes available (include m² ranges)
2. Price per m² for different property types
3. Rental yields and income potential (include % and € estimates)
4. Total buying costs for foreigners (include % breakdown)
5. Transaction timeline (include specific timeframes)
6. Property management services and costs

Return JSON array: [{\"question\": \"...\", \"answer\": \"...\"}]
Each answer should be 2-3 sentences with real numbers.";

        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}.";

        $response = $this->callAnthropic($prompt, 1000) ?? $this->callGemini($prompt, 800) ?? $this->callOpenAI($prompt, 800);
        
        if ($response) {
            $json = $this->extractJsonArray($response);
            if ($json && count($json) >= 4) {
                return $json;
            }
        }

        // Return defaults with numbers if AI fails
        return $defaultFaqs;
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
            // Use gemini-2.5-flash (stable, fast)
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$this->geminiKey}",
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
                    'model' => 'claude-haiku-4-5-20251001',
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
     * Generate main article content.
     */
    protected function generateMainArticle(string $city, string $agencyName, string $subPrompt, string $language): string
    {
        $prompt = "Write 3 informative paragraphs about buying property in {$city}. Cover: 1) Why the area attracts buyers (location, lifestyle, beaches, infrastructure), 2) What property types are most in demand (apartments, villas, features like parking, terraces, sea views), 3) Rental and investment potential. Be specific and factual, not generic marketing.";
        
        if ($subPrompt) {
            $prompt .= " Additional context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Write naturally, avoid bullet points.";

        $result = $this->callAnthropic($prompt, 400) ?? $this->callGemini($prompt, 300) ?? $this->callOpenAI($prompt, 300);
        
        return $result ?? "{$city} is one of the most practical coastal areas for buyers who want sea proximity without depending on the old town. The area is known for its beach zone, newer residential buildings, sea-view apartments, wider roads, and easier parking compared with historic centers.\n\nThe strongest demand usually comes from buyers looking for modern apartments with terraces, garage parking, elevator access, and open sea views. These details matter because many older neighborhoods offer charm but not always the practical features buyers expect from a second home or rental-ready property.\n\nFrom a rental perspective, the area benefits from beach access, family-friendly positioning, and short travel time to main attractions. Apartments near the beach, properties with parking, and units with a strong terrace or sea-view angle can be easier to market during the high season.";
    }

    /**
     * Generate quick answers for "Your Questions, Answered" section.
     * These are short, direct answers optimized for Google snippets and AI search.
     * IMPORTANT: Include specific data like m², prices, distances, percentages.
     */
    protected function generateQuickAnswers(string $city, string $agencyName, string $subPrompt, string $language): array
    {
        $defaultAnswers = [
            ['question' => "What is the average apartment size in {$city}?", 'answer' => "Most sought-after apartments range from 50m² to 120m². Two-bedroom units (60-80m²) with terraces are the most popular for both living and rental."],
            ['question' => "What are typical property prices per m² in {$city}?", 'answer' => "Quality apartments with sea views typically range from €3,000 to €6,000 per m². Premium locations near the beach can reach €7,000-€8,000 per m²."],
            ['question' => "How far is {$city} from the airport and city center?", 'answer' => "The area is approximately 5-15 minutes from the city center and 20-30 minutes from the airport, depending on traffic."],
            ['question' => "What rental yield can I expect?", 'answer' => "Well-positioned apartments with sea views and parking can achieve 5-8% gross rental yield during high season (June-September)."],
        ];

        $prompt = "Generate 4 quick Q&A pairs about buying property in {$city}. 
IMPORTANT: Include SPECIFIC DATA in every answer - square meters (m²), price ranges (€), distances (km/minutes), percentages (%), rental yields, etc.
Questions should cover:
1) Average apartment sizes in m²
2) Price per m² for different property types
3) Distances to key locations (airport, center, beach)
4) Rental yields or investment returns

Answers must be factual with numbers, not generic marketing text.";
        
        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Return JSON array: [{\"question\": \"...\", \"answer\": \"...\"}, ...]";

        $response = $this->callAnthropic($prompt, 500) ?? $this->callGemini($prompt, 400) ?? $this->callOpenAI($prompt, 400);
        
        if ($response) {
            $json = $this->extractJsonArray($response);
            if ($json && count($json) >= 3) {
                return $json;
            }
        }
        
        return $defaultAnswers;
    }

    /**
     * Generate market snapshot data.
     */
    protected function generateMarketSnapshot(string $city, string $subPrompt, string $language): array
    {
        $prompt = "For {$city} real estate market, provide: 1) typical price range per m² for quality apartments (e.g. '€3k–€6k'), 2) current buyer demand level (High/Medium/Low). Return JSON: {\"price_range\": \"...\", \"demand\": \"...\"}";
        
        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }

        $response = $this->callAnthropic($prompt, 100) ?? $this->callGemini($prompt, 80) ?? $this->callOpenAI($prompt, 80);
        
        if ($response) {
            $json = $this->extractJson($response);
            if ($json && isset($json['price_range'])) {
                return $json;
            }
        }
        
        return [
            'price_range' => '€3k–€6k',
            'demand' => 'High',
        ];
    }

    /**
     * Generate area comparison data for each place with unique characteristics.
     */
    protected function generateAreaComparison(array $places, string $city, string $subPrompt, string $language): array
    {
        $placeNames = array_map(fn($p) => $p['name'] ?? '', $places);
        $placeNames = array_filter($placeNames);
        
        if (empty($placeNames)) {
            return [];
        }

        $placeList = implode(', ', $placeNames);
        
        $prompt = "For these locations near {$city}: {$placeList}

Generate UNIQUE comparison data for EACH location. Each location must have DIFFERENT values.
Return JSON array with one object per location:
[
  {
    \"name\": \"Location Name\",
    \"main_strength\": \"Unique 3-5 word strength (e.g. 'Historic center, walkable', 'Beach access, modern builds', 'Quiet hills, panoramic views')\",
    \"typical_buyer\": \"Who buys here (e.g. 'Young professionals', 'Retirees seeking peace', 'Families with children')\",
    \"property_type\": \"Best property type (e.g. 'Stone houses', 'New apartments', 'Villas with land')\"
  }
]

IMPORTANT: Each location MUST have different values. Do not repeat the same text.";

        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}.";

        $response = $this->callAnthropic($prompt, 800) ?? $this->callGemini($prompt, 600) ?? $this->callOpenAI($prompt, 600);
        
        if ($response) {
            $json = $this->extractJsonArray($response);
            if ($json && count($json) >= 1) {
                return $json;
            }
        }
        
        // Generate varied defaults if AI fails
        $defaults = [];
        $strengths = ['Beach proximity, newer builds', 'Historic charm, stone houses', 'Quiet residential, family-friendly', 'Sea views, modern apartments', 'Central location, walkable', 'Hillside setting, panoramic views', 'Marina access, waterfront', 'Green surroundings, peaceful', 'Tourist hub, rental potential', 'Local village feel, authentic'];
        $buyers = ['Second-home buyers, families', 'Investors seeking rentals', 'Retirees, lifestyle buyers', 'Young professionals', 'Foreign buyers, expats', 'Local families upgrading', 'Vacation home seekers', 'Digital nomads', 'Luxury buyers', 'First-time buyers'];
        $types = ['Modern apartments', 'Stone houses', 'Villas with gardens', 'Seafront properties', 'Renovated historic', 'New construction', 'Penthouses', 'Family homes', 'Investment units', 'Mixed-use properties'];
        
        foreach ($placeNames as $i => $name) {
            $defaults[] = [
                'name' => $name,
                'main_strength' => $strengths[$i % count($strengths)],
                'typical_buyer' => $buyers[$i % count($buyers)],
                'property_type' => $types[$i % count($types)],
            ];
        }
        
        return $defaults;
    }

    /**
     * Generate buyer fit information.
     */
    protected function generateBuyerFit(string $city, string $subPrompt, string $language): array
    {
        $prompt = "For {$city} property market, describe in 1 sentence each: 1) Who is this area best for? 2) Who might prefer elsewhere? 3) What's the investor angle? Return JSON: {\"best_for\": \"...\", \"less_ideal\": \"...\", \"investor\": \"...\"}";
        
        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }

        $response = $this->callAnthropic($prompt, 200) ?? $this->callGemini($prompt, 150) ?? $this->callOpenAI($prompt, 150);
        
        if ($response) {
            $json = $this->extractJson($response);
            if ($json && isset($json['best_for'])) {
                return $json;
            }
        }
        
        return [
            'best_for' => 'Buyers who want sea access, newer buildings and easier parking.',
            'less_ideal' => 'Buyers who want historic stone-house character.',
            'investor' => 'Strong if the unit has a terrace, view, parking and clean docs.',
        ];
    }

    /**
     * Extract JSON array from AI response.
     */
    protected function extractJsonArray(string $text): ?array
    {
        // Find JSON array in response
        if (preg_match('/\[[\s\S]*\]/', $text, $matches)) {
            try {
                $result = json_decode($matches[0], true);
                if (is_array($result)) {
                    return $result;
                }
            } catch (\Exception $e) {
                return null;
            }
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
