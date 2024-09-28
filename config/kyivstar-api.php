<?php

return [
    /**
     * The default Kyivstar API server to use: mock | sandbox | production.
     */
    'server' => env('KYIVSTAR_API_SERVER', 'mock'),

    /**
     * The default Kyivstar API version to use.
     */
    'version' => env('KYIVSTAR_API_VERSION', 'v1beta'),

    /**
     * Your Kyivstar API Client ID.
     */
    'client_id' => env('KYIVSTAR_API_CLIENT_ID', ''),

    /**
     * Your Kyivstar API Client Secret.
     */
    'client_secret' => env('KYIVSTAR_API_CLIENT_SECRET', ''),

    /**
     * Your Kyivstar API Alpha Name.
     */
    'alpha_name' => env('KYIVSTAR_API_ALPHA_NAME', 'messagedesk'),
];