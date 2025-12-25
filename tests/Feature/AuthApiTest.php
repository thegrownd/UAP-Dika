<?php

namespace Tests\Feature;

use App\Services\JwtService;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_me_requires_valid_jwt(): void
    {
        $svc = new JwtService();
        config(['jwt.secret' => str_repeat('a', 64)]);
        $token = $svc->issueToken(1, ['name' => 'Tester', 'email' => 'tester@example.com', 'role' => 'user']);

        $response = $this->getJson('/api/auth/me', ['Authorization' => "Bearer {$token}"]);
        $response->assertOk()->assertJsonFragment(['name' => 'Tester', 'email' => 'tester@example.com']);
    }

    public function test_refresh_issues_new_token(): void
    {
        $svc = new JwtService();
        config(['jwt.secret' => str_repeat('a', 64)]);
        $token = $svc->issueToken(1, ['name' => 'Tester']);

        $response = $this->postJson('/api/auth/refresh', [], ['Authorization' => "Bearer {$token}"]);
        $response->assertOk()->assertJsonStructure(['token']);
    }
}

