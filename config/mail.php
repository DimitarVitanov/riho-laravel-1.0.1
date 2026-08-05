<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */

    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */

    'mailers' => [

        'smtp' => [
            'transport' => 'smtp',
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
            // 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'resend' => [
            'transport' => 'resend',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        /*
         * EST8ADS is a separate brand from Villa Bit AI and sends its
         * transactional emails (verification, welcome, payment request/
         * confirmation) from its own Proton Mail address instead of the
         * default "from" address above.
         *
         * EST8ADS is connected to Proton Mail (real@est8ads.com). Proton
         * only allows outbound SMTP submission for paid plans with a custom
         * domain: generate a dedicated SMTP token from
         * Proton Mail → Settings → All settings → IMAP/SMTP → SMTP tokens,
         * pair it with real@est8ads.com, then set EST8ADS_MAIL_USERNAME to
         * that address and EST8ADS_MAIL_PASSWORD to the generated token
         * (not the normal account password).
         */
        'est8ads' => [
            'transport' => 'smtp',
            'host' => env('EST8ADS_MAIL_HOST', 'smtp.protonmail.ch'),
            'port' => env('EST8ADS_MAIL_PORT', 587),
            'encryption' => env('EST8ADS_MAIL_ENCRYPTION', 'tls'),
            'username' => env('EST8ADS_MAIL_USERNAME', 'real@est8ads.com'),
            'password' => env('EST8ADS_MAIL_PASSWORD'),
            'timeout' => null,
        ],

        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],

    'admin_address' => env('MAIL_ADMIN_ADDRESS', 'inbox@villabit.ai'),

    /*
     * "From" address used by EST8ADS-only notifications (verification,
     * welcome and payment emails). Sent through the "est8ads" mailer above
     * so they go out via EST8ADS's own Proton Mail inbox instead of Villa
     * Bit AI's.
     */
    'est8ads_from' => [
        'address' => env('EST8ADS_MAIL_FROM_ADDRESS', 'real@est8ads.com'),
        'name' => env('EST8ADS_MAIL_FROM_NAME', 'EST8ADS'),
    ],

];
