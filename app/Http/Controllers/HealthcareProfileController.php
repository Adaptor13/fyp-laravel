<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\HealthcareProfile;

class HealthcareProfileController extends Controller
{
    /**
     * Show the healthcare profile edit page
     */
    public function edit()
    {
        $user = Auth::user()->load(['role', 'profile', 'healthcareProfile']);
        
        // Ensure only healthcare users can access this
        if ($user->role->name !== 'healthcare') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.healthcare.profile.edit', compact('user'));
    }

    /**
     * Update the healthcare profile
     */
    public function update(Request $request)
    {
        $user = Auth::user()->load('role');
        
        // Ensure only healthcare users can access this
        if ($user->role->name !== 'healthcare') {
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
            'hc_state' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:100',
            'apc_expiry' => 'nullable|date',
            'facility_name' => 'nullable|string|max:150',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        $validated = $request->validate($rules);

        // Debug: dump the validated data to check APC expiry


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
        
        if ($request->boolean('remove_avatar') && $existingProfile && !empty($existingProfile->avatar_path)) {
            // Remove existing avatar
            Storage::disk('public')->delete($existingProfile->avatar_path);
            $profileData['avatar_path'] = null;
        } elseif ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($existingProfile && !empty($existingProfile->avatar_path)) {
                Storage::disk('public')->delete($existingProfile->avatar_path);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar_path'] = $path;
        }

        // Update or create base profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Update or create healthcare profile
        $user->healthcareProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'profession' => $validated['profession'] ?? null,
                'apc_expiry' => $validated['apc_expiry'] ?? null,
                'facility_name' => $validated['facility_name'] ?? null,
                'state' => $validated['hc_state'] ?? null,
            ]
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete healthcare account
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        // Ensure only healthcare users can access this
        if ($user->role->name !== 'healthcare') {
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
