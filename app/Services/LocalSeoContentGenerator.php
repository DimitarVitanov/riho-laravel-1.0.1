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
            'highlight_box' => null,
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

            // 2b. Generate highlight box (key takeaway)
            $results['highlight_box'] = $this->generateHighlightBox($city, $subPrompt, $language);
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
        $prompt = "Write a short subtitle (2-3 sentences, max 50 words) for a property guide page about {$city}. 
This is for buyers looking at real estate. Focus on: what makes this area attractive, property types available, lifestyle benefits.
DO NOT mention SEO, marketing, or agency services. Write as if describing the area to a potential buyer.
Example tone: 'A buyer-focused guide to [area]: property types, prices, lifestyle, investment potential, available listings, and local buying advice.'";
        
        if ($subPrompt) {
            $prompt .= " Additional context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Be concise and informative.";

        $result = $this->callAnthropic($prompt, 100) ?? $this->callGemini($prompt, 80) ?? $this->callOpenAI($prompt, 80);
        
        return $result ?? "A buyer-focused guide to {$city}: property types, prices, lifestyle, investment potential, available listings, and local buying advice.";
    }

    /**
     * Generate highlight box content (key takeaway, max 2 sentences).
     */
    protected function generateHighlightBox(string $city, string $subPrompt, string $language): string
    {
        $prompt = "Write a key takeaway (1-2 sentences, max 30 words) summarizing why {$city} is worth considering for property buyers.
Focus on: unique selling points, lifestyle benefits, or investment potential.
DO NOT mention SEO, marketing, or content guidelines. Write only the takeaway text.
Example: 'With beach access, modern infrastructure, and strong rental demand, this area offers both lifestyle appeal and solid investment returns.'";
        
        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}.";

        $result = $this->callAnthropic($prompt, 80) ?? $this->callGemini($prompt, 60) ?? $this->callOpenAI($prompt, 60);
        
        return $result ?? "With its prime location, modern amenities, and growing demand, {$city} offers excellent opportunities for both lifestyle buyers and investors.";
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
        // Default FAQs with 3-sentence structure
        $defaultFaqs = [
            ['question' => "Can foreigners buy property in {$city}?", 'answer' => "Yes, foreigners can buy property in Croatia. EU citizens have the same rights as locals, while non-EU buyers need government approval (typically 2-6 months). Total buying costs are 6-8% of purchase price including 3% transfer tax."],
            ['question' => "What types of properties are available?", 'answer' => "Modern apartments (50-150m²), traditional stone houses, and villas with pools are most common. About 60% of sales are apartments, with 2-bedroom units (65-85m²) being most popular. Prices range €2,500-6,000/m² depending on location and sea view."],
            ['question' => "What matters most when buying here?", 'answer' => "Key factors are sea view quality, distance to beach (ideally under 10 min walk), parking availability, and building age. Newer buildings (post-2010) with elevators and terraces command 20-30% premium. Documentation and rental potential also matter for investors."],
            ['question' => "Can an apartment work for holiday rental?", 'answer' => "Yes, especially if it has sea views, parking, and a strong terrace. Well-located 2-bedroom apartments can achieve €100-180/night in peak season (June-September). Gross rental yields of 5-8% are realistic with professional management."],
            ['question' => "How long does a purchase take?", 'answer' => "A standard transaction takes 30-60 days from offer to completion. This includes 1-2 weeks for due diligence, 2-3 weeks for contract preparation, and 2-4 weeks for land registry transfer. Cash purchases can close in 3-4 weeks."],
            ['question' => "What are the ongoing ownership costs?", 'answer' => "Annual costs include property tax (€0.5-2/m²), utilities (€100-200/month), and building maintenance fees (€1-3/m²/month). If renting, management fees are typically 15-25% of rental income plus €50-80 per guest turnover for cleaning."],
        ];

        $prompt = "Generate 6 FAQ pairs for real estate buyers in {$city}. Agency: {$agencyName}.

ANSWER STRUCTURE (MUST follow exactly):
- Sentence 1: Direct answer (Yes/No + brief reason)
- Sentence 2: Key details and explanation
- Sentence 3: SPECIFIC NUMBERS (prices €, sizes m², percentages %, timeframes)

Questions to answer:
1. Can foreigners buy property in {$city}?
2. What types of properties are available?
3. What matters most when buying here?
4. Can an apartment work for holiday rental?
5. How long does a purchase take?
6. What are the ongoing ownership costs?

EXAMPLE FORMAT:
Q: Can foreigners buy here?
A: Yes, foreigners can purchase property in Croatia. EU citizens have equal rights as locals, while non-EU buyers need government approval. Total buying costs are 6-8% including 3% transfer tax, 1-2% notary, and 1-2% legal fees.

Return JSON array: [{\"question\": \"...\", \"answer\": \"...\"}]";

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
        $prompt = "Write 3 informative paragraphs about buying property in {$city}. 
Structure:
1) Why the area attracts buyers (location, lifestyle, beaches, infrastructure)
2) What property types are most in demand (apartments, villas, features like parking, terraces, sea views)
3) Rental and investment potential - END with a brief conclusion about why this area is worth considering

IMPORTANT RULES:
- Be specific and factual, not generic marketing
- DO NOT mention SEO, marketing rules, or content guidelines
- DO NOT include any meta-instructions or writing tips
- Write ONLY the article content that a buyer would read
- End with a natural conclusion paragraph, not advice for writers";
        
        if ($subPrompt) {
            $prompt .= " Additional context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}. Write naturally, avoid bullet points.";

        $result = $this->callAnthropic($prompt, 500) ?? $this->callGemini($prompt, 400) ?? $this->callOpenAI($prompt, 400);
        
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
            ['question' => "Is {$city} a good area to buy property?", 'answer' => "Yes, especially for buyers seeking sea proximity and modern amenities. The area offers newer buildings with parking, terraces, and beach access. Average prices range from €3,000-5,000/m² for quality apartments."],
            ['question' => "What kind of property is most attractive?", 'answer' => "Modern apartments with sea views are most in demand. Units with 60-90m², garage parking, elevator access, and good terraces typically sell fastest. Prices for such properties range €250,000-450,000."],
            ['question' => "Is it better for lifestyle or investment?", 'answer' => "Both work well here. Lifestyle buyers value the beach (5-10 min walk) and modern infrastructure. Investors see 5-8% gross rental yields, with peak season rates of €100-180/night for quality apartments."],
            ['question' => "What are the buying costs?", 'answer' => "Total costs are 6-8% of purchase price. This includes 3% property transfer tax, 1-2% notary fees, 1-2% legal fees. EU citizens have equal buying rights as locals."],
        ];

        $prompt = "Generate 4 Q&A pairs about buying property in {$city}.

ANSWER STRUCTURE (MUST follow this format):
- Sentence 1: Direct answer (Yes/No/It depends + brief reason)
- Sentence 2: Explanation with context
- Sentence 3: SPECIFIC NUMBERS (prices in €, sizes in m², percentages %, distances in km/minutes)

Questions to answer:
1) Is {$city} a good area to buy property?
2) What kind of property is most attractive here?
3) Is it better for lifestyle or investment?
4) What are the total buying costs?

EXAMPLE FORMAT:
Q: Is this a good area?
A: Yes, especially for buyers who want sea proximity and newer buildings. The area offers modern apartments with parking, terraces, and beach access within 10 minutes. Prices range €3,000-5,000/m², with 2-bedroom units (70-90m²) most popular.";
        
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

Generate UNIQUE comparison data for EACH location with 6 data points.
Return JSON array:
[
  {
    \"name\": \"Location Name\",
    \"main_strength\": \"2-4 words (e.g. 'Beach proximity', 'Historic charm', 'Modern builds')\",
    \"typical_buyer\": \"1-2 words (e.g. 'Families', 'Investors', 'Retirees')\",
    \"property_type\": \"1-2 words (e.g. 'Apartments', 'Villas', 'Stone houses')\",
    \"price_range\": \"Price per m² (e.g. '€3,000-4,500')\",
    \"beach_distance\": \"Walking time (e.g. '5-10 min', '15 min')\"
  }
]

RULES:
- Each location MUST have DIFFERENT values
- Keep text SHORT (2-4 words max per field)
- Include realistic price ranges for the area
- Beach distance in walking minutes";

        if ($subPrompt) {
            $prompt .= " Context: {$subPrompt}";
        }
        
        $prompt .= " Language: {$language}.";

        $response = $this->callAnthropic($prompt, 1000) ?? $this->callGemini($prompt, 800) ?? $this->callOpenAI($prompt, 800);
        
        if ($response) {
            $json = $this->extractJsonArray($response);
            if ($json && count($json) >= 1) {
                return $json;
            }
        }
        
        // Generate varied defaults if AI fails
        $defaults = [];
        $strengths = ['Beach proximity', 'Historic charm', 'Modern builds', 'Sea views', 'Quiet setting', 'Central location', 'Marina access', 'Green area', 'Tourist hub', 'Local village'];
        $buyers = ['Families', 'Investors', 'Retirees', 'Professionals', 'Expats', 'Local buyers', 'Vacation buyers', 'Digital nomads', 'Luxury buyers', 'First-timers'];
        $types = ['Apartments', 'Villas', 'Stone houses', 'New builds', 'Penthouses', 'Family homes', 'Studios', 'Townhouses', 'Duplexes', 'Mixed'];
        $prices = ['€2,500-3,500', '€3,000-4,500', '€3,500-5,000', '€4,000-6,000', '€2,000-3,000', '€3,000-4,000', '€4,500-6,500', '€2,800-4,000', '€3,200-4,800', '€2,500-4,000'];
        $beaches = ['2-5 min', '5-10 min', '10-15 min', '15-20 min', '1-3 min', '5-8 min', '8-12 min', '3-6 min', '12-18 min', '6-10 min'];
        
        foreach ($placeNames as $i => $name) {
            $defaults[] = [
                'name' => $name,
                'main_strength' => $strengths[$i % count($strengths)],
                'typical_buyer' => $buyers[$i % count($buyers)],
                'property_type' => $types[$i % count($types)],
                'price_range' => $prices[$i % count($prices)],
                'beach_distance' => $beaches[$i % count($beaches)],
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
