<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\SocialWorkerProfile;

class CwoProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create social_worker role if it doesn't exist
        Role::firstOrCreate(['name' => 'social_worker'], [
            'name' => 'social_worker',
            'pretty_name' => 'Child Welfare Officer',
            'description' => 'Child Welfare Officer'
        ]);
    }

    /** @test */
    public function cwo_can_access_profile_edit_page()
    {
        $cwo = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $response = $this->actingAs($cwo)
            ->get(route('cwo.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.cwo.profile.edit');
    }

    /** @test */
    public function non_cwo_cannot_access_cwo_profile_page()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', 'public_user')->first()->id ?? 1
        ]);

        $response = $this->actingAs($user)
            ->get(route('cwo.profile.edit'));

        $response->assertStatus(403);
    }

    /** @test */
    public function cwo_can_update_profile()
    {
        $cwo = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $profileData = [
            'name' => 'John Doe',
            'phone' => '0123456789',
            'address_line1' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur',
            'agency_name' => 'Child Welfare Department',
            'agency_code' => 'CWD001',
            'placement_state' => 'Selangor',
            'placement_district' => 'Petaling Jaya',
            'staff_id' => 'SW001'
        ];

        $response = $this->actingAs($cwo)
            ->put(route('cwo.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify data was saved
        $this->assertDatabaseHas('users', [
            'id' => $cwo->id,
            'name' => 'John Doe'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $cwo->id,
            'phone' => '0123456789',
            'address_line1' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur'
        ]);

        $this->assertDatabaseHas('social_worker_profiles', [
            'user_id' => $cwo->id,
            'agency_name' => 'Child Welfare Department',
            'agency_code' => 'CWD001',
            'placement_state' => 'Selangor',
            'placement_district' => 'Petaling Jaya',
            'staff_id' => 'SW001'
        ]);
    }

    /** @test */
    public function cwo_can_delete_account()
    {
        $cwo = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $response = $this->actingAs($cwo)
            ->delete(route('cwo.profile.destroy'));

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $cwo->id
        ]);
    }
}
