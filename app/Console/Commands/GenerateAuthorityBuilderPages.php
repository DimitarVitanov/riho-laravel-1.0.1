<?php

namespace App\Console\Commands;

use App\Models\AuthorityBuilderPage;
use App\Models\GeneratedPage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateAuthorityBuilderPages extends Command
{
    protected $signature = 'authority:generate {--limit=10 : Maximum pages to generate per run}';
    protected $description = 'Generate Authority Builder pages that are due for generation';

    protected string $provider;
    protected ?string $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->provider = config('services.ai.default_provider', 'openai');
        $this->apiKey = match ($this->provider) {
            'openai' => config('services.openai.api_key'),
            'gemini' => config('services.gemini.api_key'),
            default => null,
        };
    }

    public function handle()
    {
        $limit = (int) $this->option('limit');
        
        $pages = AuthorityBuilderPage::dueForGeneration()
            ->limit($limit)
            ->get();

        if ($pages->isEmpty()) {
            $this->info('No Authority Builder pages due for generation.');
            return 0;
        }

        $this->info("Found {$pages->count()} page(s) to generate.");

        foreach ($pages as $page) {
            $this->generatePage($page);
        }

        $this->info('Authority Builder generation complete.');
        return 0;
    }

    protected function generatePage(AuthorityBuilderPage $page)
    {
        $this->info("Generating: {$page->source_title}");

        try {
            $page->update([
                'status' => 'generating',
                'generation_started_at' => now(),
            ]);

            // TODO: Implement actual AI content generation for each of the 31 boxes
            // For now, we'll create a placeholder structure
            $contentSections = $this->generateContentSections($page);

            // Create the GeneratedPage record for publishing
            $generatedPage = GeneratedPage::create([
                'agency_profile_id' => $page->agency_profile_id,
                'feature_key' => 'ai_authority_builder',
                'title' => $page->title,
                'slug' => $page->slug,
                'content' => json_encode($contentSections),
                'meta_title' => $page->meta_title ?? $page->title,
                'meta_description' => $page->meta_description ?? "Real estate analysis for {$page->location}",
                'status' => 'draft',
                'location_city' => $page->location,
                'location_country' => $page->country,
            ]);

            $page->update([
                'status' => 'generated',
                'content_sections' => $contentSections,
                'generation_completed_at' => now(),
            ]);

            $this->info("  ✓ Generated successfully (GeneratedPage ID: {$generatedPage->id})");

            Log::info('Authority Builder page generated', [
                'authority_page_id' => $page->id,
                'generated_page_id' => $generatedPage->id,
                'source_type' => $page->source_type,
                'source_id' => $page->source_id,
            ]);

        } catch (\Exception $e) {
            $page->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            $this->error("  ✗ Failed: {$e->getMessage()}");

            Log::error('Authority Builder page generation failed', [
                'authority_page_id' => $page->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate content sections with AI
     * Based on the 31 analysis boxes from hvar_box_prompt_map.html
     */
    protected function generateContentSections(AuthorityBuilderPage $page): array
    {
        // Get the source data (Local SEO campaign or AI Search page)
        $sourceData = $this->getSourceData($page);
        
        // Define the 31 analysis box types with their specific prompts
        $boxDefinitions = $this->getBoxDefinitions();

        $sections = [];
        foreach ($boxDefinitions as $index => $box) {
            $this->info("  Generating box " . ($index + 1) . "/31: {$box['title']}");
            
            $content = $this->generateBoxContent($page, $box, $sourceData);
            
            $sections[] = [
                'box_number' => $index + 1,
                'title' => $box['title'],
                'content' => $content,
                'status' => $content ? 'completed' : 'failed',
            ];
            
            // Small delay to avoid rate limiting
            usleep(500000); // 0.5 second
        }

        return $sections;
    }

    /**
     * Get source data from Local SEO campaign or AI Search page
     */
    protected function getSourceData(AuthorityBuilderPage $page): array
    {
        $data = [
            'title' => $page->source_title,
            'location' => $page->location,
            'country' => $page->country,
            'source_type' => $page->source_type,
        ];

        if ($page->source_type === 'local_seo') {
            $campaign = \App\Models\LocalSeoCampaign::find($page->source_id);
            if ($campaign) {
                $data['target_city'] = $campaign->target_city;
                $data['target_neighborhood'] = $campaign->target_neighborhood;
                $data['property_type'] = $campaign->property_type;
                $data['keywords'] = $campaign->keywords;
            }
        } elseif ($page->source_type === 'ai_search') {
            $aiPage = \App\Models\AiAuthorityPage::find($page->source_id);
            if ($aiPage) {
                $data['target_city'] = $aiPage->target_city;
                $data['target_neighborhood'] = $aiPage->target_neighborhood;
                $data['property_type'] = $aiPage->property_type;
                $data['page_type'] = $aiPage->page_type;
            }
        }

        return $data;
    }

    /**
     * Generate content for a single box using AI
     */
    protected function generateBoxContent(AuthorityBuilderPage $page, array $box, array $sourceData): ?string
    {
        $prompt = $this->buildPrompt($box, $sourceData);
        
        try {
            if ($this->provider === 'openai') {
                return $this->callOpenAi($prompt);
            } elseif ($this->provider === 'gemini') {
                return $this->callGemini($prompt);
            }
        } catch (\Exception $e) {
            Log::warning("AI generation failed for box {$box['title']}: " . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Build the AI prompt for a specific box
     */
    protected function buildPrompt(array $box, array $sourceData): string
    {
        $location = $sourceData['location'] ?? 'the location';
        $country = $sourceData['country'] ?? 'Croatia';
        $propertyType = $sourceData['property_type'] ?? 'real estate';
        $title = $sourceData['title'] ?? 'this property';

        return "You are a senior real estate analyst and AI content strategist.

Task:
Write the section \"{$box['title']}\" for a premium real estate intelligence page.

Page context:
The page is about: '{$title}'
Location: {$location}, {$country}
Property type: {$propertyType}

Writing rules:
- Keep the section fact-based, analytical and decision-useful.
- Separate facts from inference.
- Do not invent specific data that is not supported - use ranges and estimates where appropriate.
- Use a structure that suits the topic: short paragraphs, bullets, a table, a checklist, a scoring model or a risk matrix when appropriate.
- Write in English.
- Make the section useful both for human readers and for AI answer engines / search extraction.
- If the topic involves prices, rent, yield or risk, explain assumptions clearly.
- If the topic involves legal or tax points, make it clear this is not formal legal/tax advice.
- Output clean HTML with <p>, <ul>, <li>, <strong>, <table> tags as needed.
- Do NOT include the section title in your output - just the content.

What this specific section must include:
{$box['instruction']}

Output only the HTML content for this section, nothing else.";
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAi(string $prompt): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a real estate content expert. Output clean HTML content only.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 1500,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;
        }

        Log::error('OpenAI API error', ['response' => $response->body()]);
        return null;
    }

    /**
     * Call Gemini API
     */
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
                    'maxOutputTokens' => 1500,
                ],
            ]
        );

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        }

        Log::error('Gemini API error', ['response' => $response->body()]);
        return null;
    }

    /**
     * Get the 31 box definitions with titles and instructions
     */
    protected function getBoxDefinitions(): array
    {
        return [
            ['title' => '1. Property identity and source-normalized facts', 'instruction' => 'Write a source-normalized fact section. Extract the property identity, location, key advantages and amenities in a clean fact table or bullet list.'],
            ['title' => '2. Executive investment conclusion', 'instruction' => 'Write the executive investment conclusion. State clearly what type of asset it is, what the main thesis is, who it best suits and what must be verified before valuation.'],
            ['title' => '3. Source Data Consistency / Due Diligence Alert', 'instruction' => 'Create a Source Data Consistency / Due Diligence Alert. Highlight any potential discrepancies that buyers should verify and explain why this matters.'],
            ['title' => '4. Architecture and spatial program', 'instruction' => 'Write an architecture and spatial program analysis. Explain the building layout concept and what matters in a property layout for this type.'],
            ['title' => '5. Bedroom and privacy analysis', 'instruction' => 'Analyze bedrooms and privacy. Explain the value of the bedroom configuration, what to inspect about room hierarchy, view quality and privacy.'],
            ['title' => '6. Sea-view and location scarcity', 'instruction' => 'Write a location and view scarcity analysis. Explain what makes this location special and why scarcity can justify a premium only when verified.'],
            ['title' => '7. Outdoor living and amenities', 'instruction' => 'Write an outdoor living and amenities analysis. Cover outdoor spaces, pools, terraces, experiential value and maintenance considerations.'],
            ['title' => '8. Parking and access value', 'instruction' => 'Explain parking and access value. Mention parking availability and why legal status and practicality should be checked.'],
            ['title' => '9. Construction quality and technical specification', 'instruction' => 'Write a construction quality and technical specification section. Create a verification checklist covering envelope, HVAC, electrical, water, durability, energy and warranty factors.'],
            ['title' => '10. Asking-price valuation lens', 'instruction' => 'Write an asking-price valuation lens. Calculate implied price metrics and explain that valuations must be compared with true comparable properties.'],
            ['title' => '11. Local luxury-market positioning', 'instruction' => 'Position the property within the local luxury market. Explain how the location\'s identity affects the competitive set.'],
            ['title' => '12. National and regional market backdrop', 'instruction' => 'Write the national and regional market backdrop section. Use broader housing-price context, but clearly state that broad indices are only background context.'],
            ['title' => '13. Target buyer profile', 'instruction' => 'Create a buyer profile section. Distinguish lifestyle buyers, capital-preservation buyers, international rental operators, and yield investors.'],
            ['title' => '14. Rental investment thesis', 'instruction' => 'Write a rental investment thesis. Explain the rental strengths and weaknesses and the need for disciplined operating assumptions.'],
            ['title' => '15. Scenario model — not a forecast', 'instruction' => 'Create a scenario model section clearly labeled "not a forecast". Use illustrative scenarios to show sensitivity, and state explicitly that these are analytical scenarios only.'],
            ['title' => '16. Operating-cost sensitivity', 'instruction' => 'Write an operating-cost sensitivity section. List the major operating-cost categories and explain why gross yield is not the same as investor cash yield.'],
            ['title' => '17. Rental-market positioning', 'instruction' => 'Write a rental-market positioning section. Explain where this property sits in the local rental market and what drives rental demand.'],
            ['title' => '18. Seasonality and occupancy', 'instruction' => 'Analyze seasonality and occupancy patterns. Explain peak and off-peak periods and how this affects rental income projections.'],
            ['title' => '19. Legal ownership structure', 'instruction' => 'Write a legal ownership structure section. Cover ownership options, foreign buyer rules, and what legal verification is needed.'],
            ['title' => '20. Tax and fiscal considerations', 'instruction' => 'Write a tax and fiscal considerations section. Cover relevant taxes but clearly state this is not tax advice and professional consultation is required.'],
            ['title' => '21. Acquisition cost breakdown', 'instruction' => 'Create an acquisition cost breakdown. List typical purchase costs including taxes, fees, and other transaction costs.'],
            ['title' => '22. Financing considerations', 'instruction' => 'Write a financing considerations section. Explain mortgage availability, typical terms, and what affects financing options.'],
            ['title' => '23. Property management requirements', 'instruction' => 'Write a property management requirements section. Explain what management is needed and the options available.'],
            ['title' => '24. Maintenance and reserve planning', 'instruction' => 'Write a maintenance and reserve planning section. Explain typical maintenance needs and recommended reserve levels.'],
            ['title' => '25. Insurance requirements', 'instruction' => 'Write an insurance requirements section. Cover typical insurance needs and considerations for this property type.'],
            ['title' => '26. Exit strategy analysis', 'instruction' => 'Write an exit strategy analysis. Explain resale considerations, typical holding periods, and what affects exit value.'],
            ['title' => '27. Risk matrix', 'instruction' => 'Create a risk matrix. Identify key risks across categories (market, legal, operational, physical) with likelihood and impact assessment.'],
            ['title' => '28. Due diligence checklist', 'instruction' => 'Create a comprehensive due diligence checklist. List all items that should be verified before purchase.'],
            ['title' => '29. Professional team requirements', 'instruction' => 'Write a professional team requirements section. List the professionals needed (lawyer, surveyor, tax advisor, etc.) and their roles.'],
            ['title' => '30. Timeline to completion', 'instruction' => 'Write a timeline to completion section. Explain typical purchase timelines and key milestones.'],
            ['title' => '31. Final recommendation summary', 'instruction' => 'Write a final recommendation summary. Summarize the key points, who this property suits, and the critical next steps for interested buyers.'],
        ];
    }
}
