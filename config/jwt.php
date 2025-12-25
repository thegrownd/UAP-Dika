<?php

return [
    'secret' => env('JWT_SECRET', config('app.key')),
    'ttl' => env('JWT_TTL', 60), // minutes
];

