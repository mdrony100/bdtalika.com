<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'ai_provider' => env('AI_PROVIDER', 'openai'),
    'ai_max_prompt_length' => env('AI_MAX_PROMPT_LENGTH', 300),

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
        'model' => env('CLAUDE_MODEL', 'claude-haiku-4-5-20251001'),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],

    'openrouter' => [
        'key' => env('OPENROUTER_API_KEY'),
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
        'model' => env('OPENROUTER_MODEL', 'openai/gpt-4o-mini'),
        'referer' => env('OPENROUTER_HTTP_REFERER', env('APP_URL')),
        'title' => env('OPENROUTER_APP_TITLE', env('APP_NAME', 'BDTalika')),
    ],

    'bkash' => [
        'env' => env('BKASH_ENV', 'sandbox'),
        'base_url' => env('BKASH_BASE_URL', 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'),
        'username' => env('BKASH_USERNAME'),
        'password' => env('BKASH_PASSWORD'),
        'app_key' => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'callback_url' => env('BKASH_CALLBACK_URL', env('APP_URL') . '/user/bkash/callback'),
        'currency' => env('BKASH_CURRENCY', 'BDT'),
        'intent' => env('BKASH_INTENT', 'sale'),
        'mode' => env('BKASH_MODE', '0011'),
        'timeout' => (int) env('BKASH_TIMEOUT', 30),
    ],

    'meta_whatsapp' => [
        'enabled' => filter_var(env('META_WHATSAPP_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
        'access_token' => env('META_WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('META_WHATSAPP_PHONE_NUMBER_ID'),
        'waba_id' => env('META_WHATSAPP_WABA_ID'),
        'graph_version' => env('META_WHATSAPP_GRAPH_VERSION', 'v20.0'),
        'template_name' => env('META_WHATSAPP_TEMPLATE_NAME', 'payment_pending_reminder'),
        'template_language' => env('META_WHATSAPP_TEMPLATE_LANGUAGE', 'en'),
        'cron_token' => env('META_WHATSAPP_CRON_TOKEN'),
        'abandoned_start_at' => env('WHATSAPP_ABANDONED_START_AT', '2026-09-15 00:00:00'),
        'abandoned_min_age_minutes' => (int) env('WHATSAPP_ABANDONED_MIN_AGE_MINUTES', 30),
        'cron_limit' => (int) env('META_WHATSAPP_CRON_LIMIT', 25),
        'timeout' => (int) env('META_WHATSAPP_TIMEOUT', 20),
    ],

    'facebook' => [
        'client_id' => '',
        'client_secret' => '',
        'redirect' => '',
    ], 

    'google' => [
        'client_id'     => '745466123202-aht8f36olpoaaot6j4erch07p9r0a3nt.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect'      => 'https://bdtalika.com/user/login/google/callback',
    ],

    // local credentials
    // 'google' => [
    //     'client_id'     => '128974932089-jsjemuffqm9rsa1evvvturivmubvquao.apps.googleusercontent.com',
    //     'client_secret' => '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',
    //     'redirect'      => 'http://localhost/talika/public/user/login/google/callback',
    // ],

    'genius' => [
        'ocean' => 'https://geniusocean.com/verify/'
    ],

];
