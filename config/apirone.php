<?php

return [
    'id'    => env('APIRONE_ID', false),
    'key'   => env('APIRONE_KEY', false),
    'url'   => env('APIRONE_URL', 'https://apirone.com/api/v2/'),
    'fiat'  => env('APIRONE_FIAT', 'rub'),
    'coins' => ['btc'=>'Bitcoin', 'ltc'=>'Litecoin' ,'bch'=>'Bitcoin Cash', 'doge'=>'Dogecoin']
];