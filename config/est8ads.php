<?php

return [
    'discovery' => [
        /*
         * Default plus/minus tolerance applied to the wanted size and budget
         * when matching internet listings. A buyer asking for 200 m² also sees
         * 190 m² and 220 m² at 10%.
         */
        'tolerance_percent' => (float) env('EST8ADS_DISCOVERY_TOLERANCE', 10),

        /*
         * Provider used by the AI web-search adapter when the internet source
         * does not override it in its own configuration.
         */
        'ai_provider' => env('EST8ADS_DISCOVERY_AI_PROVIDER', 'openai'),

        /*
         * Models queried for open-web discovery. Every model is asked for
         * candidate listings and the combined results are de-duplicated before
         * our scraper verifies each page.
         */
        'web_search_providers' => array_values(array_filter(explode(',', (string) env('EST8ADS_DISCOVERY_WEB_PROVIDERS', 'openai,gemini')))),

        /*
         * Provider used for the AI re-ranking pass that fills the semantic
         * score and the human-readable explanation on each match.
         */
        'rank_provider' => env('EST8ADS_DISCOVERY_RANK_PROVIDER', 'gemini'),

        /*
         * Weight of the AI semantic score in the final score. The remainder is
         * the deterministic rule score, so AI can reorder near-ties but never
         * override a hard rule conflict.
         */
        'semantic_weight' => (float) env('EST8ADS_DISCOVERY_SEMANTIC_WEIGHT', 0.25),
    ],
];
