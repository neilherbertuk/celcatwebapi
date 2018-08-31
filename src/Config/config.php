<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Server Address
    |--------------------------------------------------------------------------
    |
    | This option specifies the server address for the Celcat Web API you are
    | trying to access. This must be https. If not present a trailing / will be added
    | automatically.
    |
    | Default: https://localhost:5000/api
    |
    */
    'ServerAddress' => env('CELCAT_WEB_API_SERVER_ADDRESS', 'https://localhost:5000/api'),

    /*
    |--------------------------------------------------------------------------
    | API Code
    |--------------------------------------------------------------------------
    |
    | This option specifies the API Code for the Celcat Web API you are
    | trying to access.
    |
    | Default: READONLYCODE
    |
    */
    'APICode' => env('CELCAT_WEB_API_APICODE', 'READONLYCODE'),

    /*
    |--------------------------------------------------------------------------
    | Verify SSL
    |--------------------------------------------------------------------------
    |
    | This option specifies whether to verify the SSL certificate used by the
    | Celcat Web API you are trying to access. If you are using a self-signed
    | cert, you will need to also provide the PEM file.
    |
    |          ----- Please do not set this to false on production. ----
    |
    | Default: true
    |
    */
    'VerifySSL' => env('CELCAT_WEB_API_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | PEM File
    |--------------------------------------------------------------------------
    |
    | This option specifies the location of the PEM certificate if using a self
    | signed SSL Cert for the Celcat Web API you are trying to access.
    |
    | Default: storage/CelcatWebAPI/cert.pem
    |
    */
    'PEM' => env('CELCAT_WEB_API_PEM', 'storage/CelcatWebAPI/cert.pem'),

    /*
    |--------------------------------------------------------------------------
    | Debug
    |--------------------------------------------------------------------------
    |
    | Enables detailed logging
    |
    | Default: false
    |
    */
    'DEBUG' => env('CELCAT_WEB_API_DEBUG', false),

];