<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    */

    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            // Prefer standard Laravel MAIL_* vars, but also support custom SMTP_* vars you provided
            'host' => env('MAIL_HOST', env('SMTP_HOST', 'smtp.mailtrap.io')),
            'port' => env('MAIL_PORT', env('SMTP_PORT', 2525)),
            // Keep encryption simple; set MAIL_ENCRYPTION in .env (e.g. "tls" for port 587 or "ssl" for 465)
            'encryption' => env('MAIL_ENCRYPTION'),
            'username' => env('MAIL_USERNAME', env('SMTP_USER')),
            'password' => env('MAIL_PASSWORD', env('SMTP_PASSWORD')),
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ],

        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    */

    'from' => [
        // Use EMAIL_FROM if set, otherwise fall back to standard MAIL_FROM_ADDRESS
        'address' => env('MAIL_FROM_ADDRESS', env('EMAIL_FROM', 'hello@example.com')),
        'name' => env('MAIL_FROM_NAME', env('APP_NAME', 'Example')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    */

    'markdown' => [
        'theme' => 'default',

        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];

