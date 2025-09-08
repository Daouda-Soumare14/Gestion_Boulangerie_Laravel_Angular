<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | On utilise la clé depuis env() si possible, sinon on met une clé fallback
    | pour éviter que config:cache casse l'accès à la clé.
    |
    */

    'api_key' => env('OPENAI_API_KEY', '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'),

    'organization' => env('OPENAI_ORGANIZATION', null),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Maximum number of seconds à attendre pour la réponse de l'API OpenAI
    |
    */
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),
];
