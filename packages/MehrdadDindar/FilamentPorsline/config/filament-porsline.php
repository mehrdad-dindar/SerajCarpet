<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Porsline API Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the Porsline API settings.
    |
    */

    'api' => [
        'base_url' => env('PORSLINE_API_BASE_URL', 'https://survey.porsline.ir/api'),
        'api_key' => env('PORSLINE_API_KEY'),
        'timeout' => env('PORSLINE_API_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Survey Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for surveys.
    |
    */

    'survey' => [
        'default_language' => 2, // 1: English, 2: Persian, 3: Turkish, 4: Arabic
        'default_folder_id' => null,
        'auto_create_survey' => true,
        'survey_template' => [
            'name' => 'نظرسنجی رضایت مشتری',
            'description' => 'لطفاً نظرات خود را در مورد خدمات ما ارائه دهید',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Settings
    |--------------------------------------------------------------------------
    |
    | Settings for SMS notifications.
    |
    */

    'sms' => [
        'enabled' => env('PORSLINE_SMS_ENABLED', true),
        'pattern_code' => env('PORSLINE_SMS_PATTERN_CODE', 250000),
        'delay_days' => env('PORSLINE_SMS_DELAY_DAYS', 2),
        'message_template' => 'سلام {customer_name}، لطفاً در نظرسنجی ما شرکت کنید: {survey_url}',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Settings for notifications.
    |
    */

    'notifications' => [
        'enabled' => true,
        'email_enabled' => env('PORSLINE_EMAIL_ENABLED', false),
        'sms_enabled' => env('PORSLINE_SMS_ENABLED', true),
    ],
];
