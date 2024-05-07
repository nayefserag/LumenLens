<?php

return [
    'providers' => [
        Tymon\JWTAuth\Providers\LumenServiceProvider::class,
    ],
    'aliases' => [
        'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
        'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
    ],
];
