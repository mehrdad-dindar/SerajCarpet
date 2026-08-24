<?php

return [
    'porsline' => [
        'api_key' => env('PORSLINE_API_KEY'),
        'base_url' => env('PORSLINE_API_BASE', 'https://survey.porsline.ir/api/'),
    ],
    'delay_days' => 2,
];
