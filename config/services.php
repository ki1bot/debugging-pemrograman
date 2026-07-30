<?php

return [
    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'judge0' => [
        'base_url' => env(
            'JUDGE0_BASE_URL',
            'https://ce.judge0.com',
        ),
        'api_key' => env('JUDGE0_API_KEY'),
        'rapidapi_host' => env('JUDGE0_RAPIDAPI_HOST'),
        'auth_token' => env('JUDGE0_AUTH_TOKEN'),
        'languages' => [
            'c' => (int) env('JUDGE0_LANGUAGE_C', 103),
            'cpp' => (int) env('JUDGE0_LANGUAGE_CPP', 105),
            'go' => (int) env('JUDGE0_LANGUAGE_GO', 107),
            'java' => (int) env('JUDGE0_LANGUAGE_JAVA', 91),
            'javascript' => (int) env(
                'JUDGE0_LANGUAGE_JAVASCRIPT',
                102,
            ),
            'php' => (int) env('JUDGE0_LANGUAGE_PHP', 98),
            'python' => (int) env('JUDGE0_LANGUAGE_PYTHON', 100),
            'sql' => (int) env('JUDGE0_LANGUAGE_SQL', 82),
        ],
    ],
];
