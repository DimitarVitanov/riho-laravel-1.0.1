<?php

return [
    'discovery' => [
        /*
         * Default plus/minus tolerance applied to the wanted size and budget
         * when matching internet listings and other users' offers. A buyer
         * asking for 200 m² / €200k also sees offers from €170k to €230k at
         * 15% (up or down).
         */
        'tolerance_percent' => (float) env('EST8ADS_DISCOVERY_TOLERANCE', 15),

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

        /*
         * When true, the first property move automatically turns on open-web
         * discovery (AI query generation + multi-engine web search + scraper)
         * so a listing is analyzed the moment it is added — mirroring how Villa
         * Bit AI analyzes a listing on creation, with no manual setup. The
         * actual searching runs on the "internet-discovery" queue, so a queue
         * worker must be running. Disabled in the test suite.
         */
        'auto_enable_open_web' => (bool) env('EST8ADS_DISCOVERY_AUTO_ENABLE', true),
    ],

    'billing' => [
        /*
         * Individual EST8ADS users (Profile type "individual") are billed a
         * flat monthly fee for keeping their property move active. Agency
         * accounts have their own separate Villa Bit billing and are not
         * affected by this cycle.
         */
        'monthly_amount' => (float) env('EST8ADS_BILLING_MONTHLY_AMOUNT', 12.00),
        'currency' => env('EST8ADS_BILLING_CURRENCY', 'USD'),

        /*
         * Days after an invoice's due date before the account is locked out
         * of the workspace pending payment.
         */
        'grace_period_days' => (int) env('EST8ADS_BILLING_GRACE_DAYS', 10),

        /*
         * Manual PayPal checkout link. Payments are reconciled by an admin
         * marking the matching invoice as paid from the admin panel — there
         * is no PayPal webhook/IPN integration.
         */
        'paypal_link' => env('EST8ADS_BILLING_PAYPAL_LINK', 'https://www.paypal.com/ncp/payment/QTVBZLAPAD2MY'),
    ],
];
