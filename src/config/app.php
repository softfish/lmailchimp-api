<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 12:27 AM
 */

return [
    'mailchimp' => [
        'api_key' => env('LMAILCHIMP_API_KEY')?? '',
        'api_base_uri' => env('LMAILCHIMP_API_URL')?? 'api.mailchimp.com/3.0/',
    ]
];