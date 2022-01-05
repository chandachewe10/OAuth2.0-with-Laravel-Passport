<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Ocp-Apim-Subscription-Key, providerCallbackHost and X-Callback-Url
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for MoMo API from the .env file
    | These credentials are Ocp-Apim-Subscription-Key, providerCallbackHost and
     X-Callback-Url.
*/


'subscription_key' => env('SUBSCRIPTION_KEY'),

'callback_host' => env('PROVIDER_CALLBACK_HOST'),

'callback_url' => env('PROVIDER_CALLBACK_URL'),


];