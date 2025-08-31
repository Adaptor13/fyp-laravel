<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use App\Models\AdminProfile;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin role if it doesn't exist
        Role::firstOrCreate(['name' => 'admin'], [
            'name' => 'admin',
            'pretty_name' => 'Administrator',
            'description' => 'System Administrator'
        ]);
    }

    /** @test */
    public function admin_can_access_profile_edit_page()
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.admins.profile.edit');
    }

    /** @test */
    public function non_admin_cannot_access_admin_profile_page()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', 'public_user')->first()->id ?? 1
        ]);

        $response = $this->actingAs($user)
            ->get(route('admin.profile.edit'));

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_profile()
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        $profileData = [
            'name' => 'John Doe',
            'phone' => '0123456789',
            'address_line1' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur',
            'display_name' => 'John Admin',
            'department' => 'IT Department',
            'position' => 'Senior Admin'
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify data was saved
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'John Doe'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $admin->id,
            'phone' => '0123456789',
            'address_line1' => '123 Main Street',
            'city' => 'Kuala Lumpur',
            'postcode' => '50000',
            'state' => 'W.P. Kuala Lumpur'
        ]);

        $this->assertDatabaseHas('admin_profiles', [
            'user_id' => $admin->id,
            'display_name' => 'John Admin',
            'department' => 'IT Department',
            'position' => 'Senior Admin'
        ]);
    }

    /** @test */
    public function admin_can_delete_account()
    {
        $admin = User::factory()->create([
            'role_id' => Role::where('name', 'admin')->first()->id
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.profile.destroy'));

        $response->assertRedirect('/');
        $response->assertSessionHas('status');

        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $admin->id
        ]);
    }
}
