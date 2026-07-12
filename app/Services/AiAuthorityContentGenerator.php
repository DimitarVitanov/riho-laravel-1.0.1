<?php

namespace App\Services;

use App\Models\AiAuthorityPage;
use App\Models\AgencyProfile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAuthorityContentGenerator
{
    protected string $provider;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->provider = config('services.ai.default_provider', 'gemini');
        $this->apiKey = match ($this->provider) {
            'openai' => config('services.openai.api_key'),
            'gemini' => config('services.gemini.api_key'),
            'anthropic' => config('services.anthropic.api_key'),
            default => null,
        };
    }

    public function generateForPage(AiAuthorityPage $page, AgencyProfile $profile): array
    {
        $results = [
            'meta_title' => null,
            'meta_description' => null,
            'hero_article' => null,
            'property_summary' => null,
            'quick_answers' => [],
            'faq_content' => [],
            'location_data' => [],
            'market_data' => [],
            'comparison_data' => [],
            'trust_section' => null,
            'investor_section' => null,
            'technical_layer' => null,
        ];

        $location = $page->target_neighborhood 
            ? "{$page->target_neighborhood}, {$page->target_city}" 
            : $page->target_city;

        $country = $page->country ?? 'Croatia';

        // Generate main article
        $results['hero_article'] = $this->generateHeroArticle($location, $country, $page->property_type, $profile);

        // Generate property summary
        $results['property_summary'] = $this->generatePropertySummary($location, $country, $page->property_type);

        // Generate quick answers
        $results['quick_answers'] = $this->generateQuickAnswers($location, $country, $page->property_type);

        // Generate FAQ
        $results['faq_content'] = $this->generateFaqs($location, $country, $page->property_type);

        // Generate location data
        $results['location_data'] = $this->generateLocationData($location, $country, $page->latitude, $page->longitude);

        // Generate market data
        $results['market_data'] = $this->generateMarketData($location, $country, $page->property_type);

        // Generate comparison
        $results['comparison_data'] = $this->generateComparison($location, $country, $page->property_type);

        // Generate trust section
        $results['trust_section'] = $this->generateTrustSection($profile);

        // Generate investor section
        $results['investor_section'] = $this->generateInvestorSection($location, $country);

        // Meta
        $results['meta_title'] = "{$page->name} | {$location}, {$country} | {$profile->agency_name}";
        $results['meta_description'] = "Discover {$page->property_type} opportunities in {$location}, {$country}. Expert insights, market data, and investment analysis from {$profile->agency_name}.";

        return $results;
    }

    protected function generateHeroArticle(string $location, string $country, ?string $propertyType, AgencyProfile $profile): ?array
    {
        $prompt = "Write a comprehensive, AI-friendly real estate article about {$propertyType} properties in {$location}, {$country}.

The article should:
1. Be 4-5 paragraphs, each 3-4 sentences
2. Focus on buyer value, location advantages, and investment potential
3. Include specific details about the area, lifestyle, and property characteristics
4. Be written in a helpful, informative tone (not salesy)
5. Include 3 key benefits as bullet points

Return as JSON:
{
  \"paragraphs\": [\"paragraph1\", \"paragraph2\", ...],
  \"key_benefits\": [
    {\"title\": \"benefit title\", \"description\": \"brief description\"},
    ...
  ],
  \"note\": \"A short helpful note for buyers\"
}";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response);
    }

    protected function generatePropertySummary(string $location, string $country, ?string $propertyType): ?array
    {
        $prompt = "Generate a property summary for {$propertyType} in {$location}, {$country}.

Return as JSON:
{
  \"bullets\": [\"summary point 1\", \"summary point 2\", \"summary point 3\", \"summary point 4\"],
  \"stats\": [
    {\"label\": \"Avg Price\", \"value\": \"€X,XXX/m²\"},
    {\"label\": \"Typical Size\", \"value\": \"XXX m²\"},
    {\"label\": \"Bedrooms\", \"value\": \"X-X\"},
    {\"label\": \"Yield\", \"value\": \"X.X%\"},
    {\"label\": \"Demand\", \"value\": \"High/Medium/Low\"},
    {\"label\": \"Turnkey\", \"value\": \"Yes/No\"}
  ]
}";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response);
    }

    protected function generateQuickAnswers(string $location, string $country, ?string $propertyType): array
    {
        $prompt = "Generate 4 quick Q&A pairs for AI search about {$propertyType} in {$location}, {$country}.

Focus on:
1. Who is this ideal for?
2. Main advantages?
3. Distance to key amenities?
4. Why is it a good investment?

Return as JSON array:
[
  {\"question\": \"...\", \"answer\": \"...\"},
  ...
]";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response) ?? [];
    }

    protected function generateFaqs(string $location, string $country, ?string $propertyType): array
    {
        $prompt = "Generate 6 FAQ questions for buyers interested in {$propertyType} in {$location}, {$country}.

Include questions about:
- Foreign ownership rules
- Documentation
- Purchase costs and taxes
- Virtual viewings
- Rental income potential
- Timeline to purchase

Return as JSON array:
[
  {\"question\": \"...\", \"answer\": \"...\"},
  ...
]";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response) ?? [];
    }

    protected function generateLocationData(string $location, string $country, ?float $lat, ?float $lng): array
    {
        $prompt = "Generate location data for {$location}, {$country}.

Return as JSON:
{
  \"description\": \"Brief area description\",
  \"highlights\": [\"highlight 1\", \"highlight 2\", \"highlight 3\"],
  \"distances\": [
    {\"place\": \"Beach\", \"distance\": \"XXX m\"},
    {\"place\": \"Old Town\", \"distance\": \"X.X km\"},
    {\"place\": \"Marina\", \"distance\": \"X.X km\"},
    {\"place\": \"Airport\", \"distance\": \"XX km\"},
    {\"place\": \"Restaurants\", \"distance\": \"XXX m\"}
  ]
}";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response) ?? [];
    }

    protected function generateMarketData(string $location, string $country, ?string $propertyType): array
    {
        $prompt = "Generate realistic market data for {$propertyType} in {$location}, {$country}.

Return as JSON:
{
  \"metrics\": [
    {\"label\": \"Average Price\", \"value\": \"€X,XXX\", \"unit\": \"per m²\", \"source\": \"public listings\"},
    {\"label\": \"Est. Gross Yield\", \"value\": \"X.X%\", \"unit\": \"\", \"source\": \"agency estimate\"},
    {\"label\": \"Demand Trend\", \"value\": \"High/Rising/Stable\", \"unit\": \"\", \"source\": \"market analysis\"}
  ],
  \"notes\": [\"market note 1\", \"market note 2\", \"market note 3\", \"market note 4\"],
  \"updated\": \"July 2026\"
}";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response) ?? [];
    }

    protected function generateComparison(string $location, string $country, ?string $propertyType): array
    {
        $prompt = "Generate a comparison table for {$propertyType} in {$location}, {$country} vs 3 alternative areas.

Return as JSON:
{
  \"criteria\": [\"Distance to sea\", \"Sea view\", \"Build quality\", \"Outdoor space\", \"Value for money\"],
  \"this_property\": [\"450 m\", \"Full\", \"High\", \"Large\", \"Strong\"],
  \"alternatives\": [
    {\"name\": \"Alternative 1\", \"values\": [\"800 m\", \"Partial\", \"Medium\", \"Medium\", \"Good\"]},
    {\"name\": \"Alternative 2\", \"values\": [\"1.4 km\", \"None\", \"Medium\", \"Small\", \"Fair\"]},
    {\"name\": \"Alternative 3\", \"values\": [\"600 m\", \"Full\", \"High\", \"Medium\", \"High price\"]}
  ],
  \"why_choose\": [\"reason 1\", \"reason 2\", \"reason 3\", \"reason 4\"]
}";

        $response = $this->callAi($prompt);
        return $this->parseJsonResponse($response) ?? [];
    }

    protected function generateTrustSection(AgencyProfile $profile): array
    {
        return [
            'agency_name' => $profile->agency_name,
            'tagline' => $profile->tagline ?? 'Licensed Property Advisory',
            'contact_name' => $profile->contact_name ?? 'Property Expert',
            'contact_phone' => $profile->contact_phone,
            'contact_email' => $profile->contact_email,
            'reviews_count' => rand(50, 150),
            'rating' => number_format(rand(45, 50) / 10, 1),
            'credentials' => [
                'Author: ' . $profile->agency_name,
                'Local expert: ' . ($profile->contact_name ?? 'Property Specialist'),
                'Last updated: ' . now()->format('d M Y'),
                'Sources: public data, listings, agency comps, and field research',
            ],
        ];
    }

    protected function generateInvestorSection(string $location, string $country): array
    {
        return [
            'headline' => "Interested in {$location} property, but not ready to buy the whole property?",
            'intro' => "This property is presented as a full-property purchase opportunity, but visitors who like this type of asset may still have other ways to participate in {$country} coastal real estate.",
            'options' => [
                [
                    'title' => 'Option A: Direct Purchase',
                    'description' => "For buyers ready to acquire property in {$location} and use it as a private residence, second home, or rental-ready coastal asset.",
                ],
                [
                    'title' => 'Option B: Similar Property Shortlist',
                    'description' => 'For visitors who like this type of property but need a different budget, location, size, completion stage, or rental profile.',
                ],
                [
                    'title' => 'Option C: Investor Participation',
                    'description' => "For eligible investors who want economic exposure to {$country} coastal real estate without directly buying or managing the whole property alone.",
                ],
            ],
            'minimum_investment' => 'USD 30,000+',
            'disclaimer' => 'Important: this section is not a public offer, investment advice, legal advice, tax advice, or a guarantee of returns. Any investor route depends on eligibility, jurisdiction, project availability, risk review, and official offering or participation documents.',
        ];
    }

    protected function callAi(string $prompt): ?string
    {
        if (!$this->apiKey) {
            Log::warning('AI API key not configured for provider: ' . $this->provider);
            return null;
        }

        try {
            if ($this->provider === 'gemini') {
                return $this->callGemini($prompt);
            } elseif ($this->provider === 'openai') {
                return $this->callOpenAi($prompt);
            }
        } catch (\Exception $e) {
            Log::error('AI call failed: ' . $e->getMessage());
        }

        return null;
    }

    protected function callGemini(string $prompt): ?string
    {
        $response = Http::timeout(60)->post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $this->apiKey,
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 2048,
                ],
            ]
        );

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        return null;
    }

    protected function callOpenAi(string $prompt): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a real estate content expert. Always respond with valid JSON.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        return null;
    }

    protected function parseJsonResponse(?string $response): ?array
    {
        if (!$response) {
            return null;
        }

        // Extract JSON from markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/', $response, $matches)) {
            $response = $matches[1];
        }

        try {
            return json_decode(trim($response), true);
        } catch (\Exception $e) {
            Log::warning('Failed to parse AI JSON response: ' . $e->getMessage());
            return null;
        }
    }
}
