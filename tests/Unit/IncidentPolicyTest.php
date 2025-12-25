<?php

namespace Tests\Unit;

use App\Models\Incident;
use App\Models\User;
use App\Policies\IncidentPolicy;
use Tests\TestCase;

class IncidentPolicyTest extends TestCase
{
    public function test_admin_can_manage_any_incident(): void
    {
        $admin = new User(['id' => 1, 'role' => 'admin']);
        $incident = new Incident(['user_id' => 2]);
        $policy = new IncidentPolicy();
        $this->assertTrue($policy->update($admin, $incident));
        $this->assertTrue($policy->delete($admin, $incident));
        $this->assertTrue($policy->view($admin, $incident));
    }

    public function test_user_can_manage_own_incident_only(): void
    {
        $user = new User(['role' => 'user']);
        $user->setAttribute('id', 2);
        $own = new Incident();
        $own->setAttribute('user_id', 2);
        $others = new Incident(['user_id' => 3]);
        $policy = new IncidentPolicy();
        $this->assertTrue($policy->update($user, $own));
        $this->assertFalse($policy->update($user, $others));
    }
}

