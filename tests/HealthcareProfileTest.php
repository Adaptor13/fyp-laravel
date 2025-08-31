<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\HealthcareProfile;

class HealthcareProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create healthcare role if it doesn't exist
        Role::firstOrCreate(['name' => 'healthcare'], [
            'name' => 'healthcare',
            'pretty_name' => 'Healthcare Professional',
            'description' => 'Healthcare Professional'
        ]);
    }

    /** @test */
    public function healthcare_can_access_profile_edit_page()
    {
        $healthcare = User::factory()->create([
            'role_id' => Role::where('name', 'healthcare')->first()->id
        ]);

        $response = $this->actingAs($healthcare)
            ->get(route('healthcare.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.healthcare.profile.edit');
    }

    /** @test */
    public function non_healthcare_cannot_access_healthcare_profile_page()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', 'public_user')->first()->id ?? 1
        ]);

        $response = $this->actingAs($user)
            ->get(route('healthcare.profile.edit'));

        $response->assertStatus(403);
    }

    /** @test */
    public function healthcare_can_update_profile()
    {
        $healthcare = User::factory()->create([
            'role_id' => Role::where('name', 'healthcare')->first()->id
        ]);

        $profileData = [
            'name' => 'Dr. Sarah Johnson',
            'phone' => '0123456789',
            'address_line1' => '456 Medical Center',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur',
            'profession' => 'Pediatrician',
            'apc_expiry' => '2025-12-31',
            'facility_name' => 'Kuala Lumpur General Hospital'
        ];

        $response = $this->actingAs($healthcare)
            ->put(route('healthcare.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify data was saved
        $this->assertDatabaseHas('users', [
            'id' => $healthcare->id,
            'name' => 'Dr. Sarah Johnson'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $healthcare->id,
            'phone' => '0123456789',
            'address_line1' => '456 Medical Center',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur'
        ]);

        $this->assertDatabaseHas('healthcare_profiles', [
            'user_id' => $healthcare->id,
            'profession' => 'Pediatrician',
            'apc_expiry' => '2025-12-31',
            'facility_name' => 'Kuala Lumpur General Hospital'
        ]);
    }

    /** @test */
    public function healthcare_can_delete_account()
    {
        $healthcare = User::factory()->create([
            'role_id' => Role::where('name', 'healthcare')->first()->id
        ]);

        $response = $this->actingAs($healthcare)
            ->delete(route('healthcare.profile.destroy'));

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $healthcare->id
        ]);
    }
}
