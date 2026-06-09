<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI Configuration
    |--------------------------------------------------------------------------
    */
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Anthropic Configuration
    |--------------------------------------------------------------------------
    */
    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'default_model' => env('ANTHROPIC_DEFAULT_MODEL', 'claude-sonnet-4-20250514'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Gemini Configuration
    |--------------------------------------------------------------------------
    */
    'google' => [
        'api_key' => env('GOOGLE_AI_API_KEY'),
        'default_model' => env('GOOGLE_AI_DEFAULT_MODEL', 'gemini-pro'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Uniqueness Checking
    |--------------------------------------------------------------------------
    | Service used to verify AI-generated content is unique on the internet.
    | Options: copyscape, plagiarism_detector, internal
    */
    'uniqueness' => [
        'provider' => env('UNIQUENESS_CHECK_PROVIDER', 'copyscape'),
        'copyscape_api_key' => env('COPYSCAPE_API_KEY'),
        'copyscape_username' => env('COPYSCAPE_USERNAME'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Publish Rules (Hard-coded, not user-configurable)
    |--------------------------------------------------------------------------
    */
    'publish_rules' => [
        'uniqueness_check_before_publish' => 'always_required',
        'if_uniqueness_fails' => 'ai_rewrite_and_recheck',
        'may_publish_only_if_status' => 'passed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Affiliate / Reseller Settings
    |--------------------------------------------------------------------------
    */
    'affiliate' => [
        'cookie_duration_days' => env('AFFILIATE_COOKIE_DAYS', 180),
        'commission_percent' => env('AFFILIATE_COMMISSION_PERCENT', 10),
        'minimum_payout' => env('AFFILIATE_MINIMUM_PAYOUT', 10.00),
        'payout_day_of_month' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Usage Limits (Defaults, can be overridden per agency)
    |--------------------------------------------------------------------------
    */
    'usage_limits' => [
        'local_seo_pages' => env('USAGE_LIMIT_LOCAL_SEO_PAGES', 10),
        'competitor_scans' => env('USAGE_LIMIT_COMPETITOR_SCANS', 30),
        'ai_search_freshness_updates' => env('USAGE_LIMIT_AI_SEARCH_UPDATES', 4),
        'authority_review_updates' => env('USAGE_LIMIT_AUTHORITY_UPDATES', 1),
        'small_ai_content_actions' => env('USAGE_LIMIT_SMALL_AI_ACTIONS', 30),
    ],

];
