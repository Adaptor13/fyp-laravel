<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\LawEnforcementProfile;

class LawEnforcementProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create law_enforcement role if it doesn't exist
        Role::firstOrCreate(['name' => 'law_enforcement'], [
            'name' => 'law_enforcement',
            'pretty_name' => 'Law Enforcement Officer',
            'description' => 'Law Enforcement Officer'
        ]);
    }

    /** @test */
    public function law_enforcement_can_access_profile_edit_page()
    {
        $lawOfficer = User::factory()->create([
            'role_id' => Role::where('name', 'law_enforcement')->first()->id
        ]);

        $response = $this->actingAs($lawOfficer)
            ->get(route('law.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.law.profile.edit');
    }

    /** @test */
    public function non_law_enforcement_cannot_access_law_enforcement_profile_page()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', 'public_user')->first()->id ?? 1
        ]);

        $response = $this->actingAs($user)
            ->get(route('law.profile.edit'));

        $response->assertStatus(403);
    }

    /** @test */
    public function law_enforcement_can_update_profile()
    {
        $lawOfficer = User::factory()->create([
            'role_id' => Role::where('name', 'law_enforcement')->first()->id
        ]);

        $profileData = [
            'name' => 'Inspector Ahmad',
            'phone' => '0123456789',
            'address_line1' => '123 Police Station Road',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur',
            'agency' => 'PDRM',
            'badge_number' => 'PDRM001',
            'rank' => 'Inspector',
            'station' => 'IPD Petaling Jaya'
        ];

        $response = $this->actingAs($lawOfficer)
            ->put(route('law.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify data was saved
        $this->assertDatabaseHas('users', [
            'id' => $lawOfficer->id,
            'name' => 'Inspector Ahmad'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $lawOfficer->id,
            'phone' => '0123456789',
            'address_line1' => '123 Police Station Road',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur'
        ]);

        $this->assertDatabaseHas('law_enforcement_profiles', [
            'user_id' => $lawOfficer->id,
            'agency' => 'PDRM',
            'badge_number' => 'PDRM001',
            'rank' => 'Inspector',
            'station' => 'IPD Petaling Jaya'
        ]);
    }

    /** @test */
    public function law_enforcement_can_delete_account()
    {
        $lawOfficer = User::factory()->create([
            'role_id' => Role::where('name', 'law_enforcement')->first()->id
        ]);

        $response = $this->actingAs($lawOfficer)
            ->delete(route('law.profile.destroy'));

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $lawOfficer->id
        ]);
    }
}
