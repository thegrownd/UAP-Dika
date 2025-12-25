<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;

class JwtService
{
    public function issueToken(int $userId, array $extraClaims = []): string
    {
        $ttl = (int) config('jwt.ttl');
        $now = Carbon::now();
        $payload = array_merge([
            'iss' => config('app.url'),
            'iat' => $now->timestamp,
            'nbf' => $now->timestamp,
            'exp' => $now->copy()->addMinutes($ttl)->timestamp,
            'sub' => $userId,
        ], $extraClaims);

        return JWT::encode($payload, config('jwt.secret'), 'HS256');
    }
}

