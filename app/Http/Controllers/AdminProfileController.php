<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\AdminProfile;

class AdminProfileController extends Controller
{
    /**
     * Show the admin profile edit page
     */
    public function edit()
    {
        $user = Auth::user()->load(['role', 'profile', 'adminProfile']);
        
        // Ensure only admin users can access this
        if ($user->role->name !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.admins.profile.edit', compact('user'));
    }

    /**
     * Update the admin profile
     */
    public function update(Request $request)
    {
        $user = Auth::user()->load('role');
        
        // Ensure only admin users can access this
        if ($user->role->name !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|digits:5',
            'state' => 'nullable|string|max:50',
            'display_name' => 'required|string|max:150',
            'department' => 'nullable|string|max:150',
            'position' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        // Debug: Log all request data
        \Log::info('Request data received', [
            'all_files' => $request->allFiles(),
            'has_avatar_file' => $request->hasFile('avatar'),
            'avatar_file' => $request->file('avatar'),
            'all_input' => $request->all(),
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method(),
            'is_multipart' => str_contains($request->header('Content-Type', ''), 'multipart/form-data')
        ]);

        $validated = $request->validate($rules);

        // Clean phone number
        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);
        }

        // Update user basic info
        $user->update([
            'name' => $validated['name'],
        ]);

        // Prepare profile data
        $profileData = [
            'phone' => $validated['phone'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'postcode' => $validated['postcode'] ?? null,
            'state' => $validated['state'] ?? null,
        ];

        // Handle avatar upload/removal
        $existingProfile = $user->profile;
        
        // Debug: Log existing profile
        \Log::info('Existing profile check', [
            'user_id' => $user->id,
            'has_profile' => $existingProfile ? 'yes' : 'no',
            'existing_avatar_path' => $existingProfile ? $existingProfile->avatar_path : 'none'
        ]);
        
        if ($request->boolean('remove_avatar') && $existingProfile && !empty($existingProfile->avatar_path)) {
            // Remove existing avatar
            Storage::disk('public')->delete($existingProfile->avatar_path);
            $profileData['avatar_path'] = null;
            \Log::info('Avatar removal requested');
        } elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($existingProfile && !empty($existingProfile->avatar_path)) {
                Storage::disk('public')->delete($existingProfile->avatar_path);
                \Log::info('Old avatar deleted', ['old_path' => $existingProfile->avatar_path]);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar_path'] = $path;
            
            // Debug: Log the avatar upload
            \Log::info('Avatar uploaded', [
                'user_id' => $user->id,
                'avatar_path' => $path,
                'file_size' => $request->file('avatar')->getSize(),
                'file_name' => $request->file('avatar')->getClientOriginalName()
            ]);
        } else {
            \Log::info('No avatar file or removal requested');
        }

        // Debug: Log profile data before update
        \Log::info('Profile data before update', [
            'user_id' => $user->id,
            'profile_data' => $profileData
        ]);
        
        // Update or create base profile
        $updatedProfile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
        
        // Debug: Log the profile update
        \Log::info('Profile updated', [
            'user_id' => $user->id,
            'profile_data' => $profileData,
            'updated_profile' => $updatedProfile->toArray()
        ]);

        // Update or create admin profile
        $user->adminProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $validated['display_name'],
                'department' => $validated['department'] ?? null,
                'position' => $validated['position'] ?? null,
            ]
        );

        // Debug: Final check - reload the profile and check avatar_path
        $user->refresh();
        $finalProfile = $user->profile;
        \Log::info('Final profile check', [
            'user_id' => $user->id,
            'final_avatar_path' => $finalProfile ? $finalProfile->avatar_path : 'no profile',
            'final_profile_exists' => $finalProfile ? 'yes' : 'no'
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete admin account
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        // Ensure only admin users can access this
        if ($user->role->name !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        Auth::logout();

        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted successfully.');
    }
}
