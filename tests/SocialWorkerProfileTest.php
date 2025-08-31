<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\SocialWorkerProfile;

class SocialWorkerProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create social_worker role if it doesn't exist
        Role::firstOrCreate(['name' => 'social_worker'], [
            'name' => 'social_worker',
            'pretty_name' => 'Social Worker',
            'description' => 'Social Worker'
        ]);
    }

    /** @test */
    public function social_worker_can_access_profile_edit_page()
    {
        $socialWorker = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $response = $this->actingAs($socialWorker)
            ->get(route('social.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.social.profile.edit');
    }

    /** @test */
    public function non_social_worker_cannot_access_social_worker_profile_page()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', 'public_user')->first()->id ?? 1
        ]);

        $response = $this->actingAs($user)
            ->get(route('social.profile.edit'));

        $response->assertStatus(403);
    }

    /** @test */
    public function social_worker_can_update_profile()
    {
        $socialWorker = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $profileData = [
            'name' => 'Sarah Johnson',
            'phone' => '0123456789',
            'address_line1' => '456 Social Work Center',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur',
            'agency_name' => 'JKM Daerah Petaling',
            'agency_code' => 'JKM001',
            'placement_state' => 'Selangor',
            'placement_district' => 'Petaling Jaya',
            'staff_id' => 'SW001'
        ];

        $response = $this->actingAs($socialWorker)
            ->put(route('social.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify data was saved
        $this->assertDatabaseHas('users', [
            'id' => $socialWorker->id,
            'name' => 'Sarah Johnson'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $socialWorker->id,
            'phone' => '0123456789',
            'address_line1' => '456 Social Work Center',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur'
        ]);

        $this->assertDatabaseHas('social_worker_profiles', [
            'user_id' => $socialWorker->id,
            'agency_name' => 'JKM Daerah Petaling',
            'agency_code' => 'JKM001',
            'placement_state' => 'Selangor',
            'placement_district' => 'Petaling Jaya',
            'staff_id' => 'SW001'
        ]);
    }

    /** @test */
    public function social_worker_can_delete_account()
    {
        $socialWorker = User::factory()->create([
            'role_id' => Role::where('name', 'social_worker')->first()->id
        ]);

        $response = $this->actingAs($socialWorker)
            ->delete(route('social.profile.destroy'));

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $socialWorker->id
        ]);
    }
}
