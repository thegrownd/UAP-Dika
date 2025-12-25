<?php

namespace Tests\Unit;

use App\Services\JwtService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Tests\TestCase;

class JwtServiceTest extends TestCase
{
    public function test_issue_token_contains_expected_claims(): void
    {
        config(['jwt.secret' => str_repeat('a', 64), 'jwt.ttl' => 60]);

        $svc = new JwtService();
        $token = $svc->issueToken(123, ['role' => 'user']);

        $decoded = JWT::decode($token, new Key(str_repeat('a', 64), 'HS256'));

        $this->assertSame(123, $decoded->sub);
        $this->assertSame('user', $decoded->role);
        $this->assertNotEmpty($decoded->exp);
    }
}

