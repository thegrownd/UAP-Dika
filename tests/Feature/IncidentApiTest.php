<?php

namespace Tests\Feature;

use Tests\TestCase;

class IncidentApiTest extends TestCase
{
    public function test_incidents_requires_auth(): void
    {
        $this->getJson('/api/incidents')->assertStatus(401);
    }
}

