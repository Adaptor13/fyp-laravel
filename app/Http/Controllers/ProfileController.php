<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\User;
use App\Models\UserProfile;
use App\Models\PublicUserProfile;
use App\Models\SocialWorkerProfile;
use App\Models\HealthcareProfile;
use App\Models\LawEnforcementProfile;
use App\Models\GovOfficialProfile;

class ProfileController extends Controller
{
    /**
     * Show the edit page that matches the signed-in user's role.
     */
    public function edit()
    {
        $user = Auth::user()->load('role', 'profile'); // shared base profile

        $role = $user->role?->name ?? 'public_user';

        switch ($role) {
            case 'public_user':
                $user->loadMissing('publicUserProfile');
                $view = 'landing.profile.edit';
                break;

            case 'social_worker':
                $user->loadMissing('socialWorkerProfile');
                $view = 'social_worker.profile.edit';
                break;

            case 'healthcare':
                $user->loadMissing('healthcareProfile');
                $view = 'healthcare.profile.edit';
                break;

            case 'law_enforcement':
                $user->loadMissing('lawEnforcementProfile');
                $view = 'law_enforcement.profile.edit';
                break;

            case 'gov_official':
                $user->loadMissing('govOfficialProfile');
                $view = 'admin.users.cwo.profile.edit';
                break;

            default:
                $user->loadMissing('publicUserProfile');
                $view = 'public_user.profile.edit';
                break;
        }

        return view($view, compact('user'));
    }

    public function update(Request $request)
    {

        // dd($request->only(['name', 'phone']));
        $user = Auth::user()->load('role');
        $role = $user->role?->name ?? 'public_user';

        // Shared user fields
        $sharedRules = [
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $user->id . ',id',
        ];

        // Shared base profile fields (Malaysia-oriented)
        $baseRules = [
            'phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postcode' => 'nullable|digits:5',
            'state' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ];

        // Role-specific rules
        switch ($role) {
            case 'public_user':
                $roleRules = [
                    'display_name'  => 'nullable|string|max:150',
                    'allow_contact' => 'nullable|boolean',
                ];
                break;

            case 'social_worker':
                $roleRules = [
                    'agency_name' => 'nullable|string|max:150',
                    'agency_code' => 'nullable|string|max:50',
                    'placement_state'=> 'nullable|string|max:50',
                    'placement_district'=> 'nullable|string|max:100',
                    'staff_id' => 'nullable|string|max:50',
                ];
                break;

            case 'healthcare':
                $roleRules = [
                    'profession' => 'nullable|string|max:100',
                    'apc_expiry' => 'nullable|date',
                    'facility_name' => 'nullable|string|max:150',
                    'hc_state' => 'nullable|string|max:50',
                ];
                break;

            case 'law_enforcement':
                $roleRules = [
                    'agency'  => 'nullable|string|max:50',   // e.g. PDRM, AADK
                    'badge_number' => 'nullable|string|max:50',
                    'rank'  => 'nullable|string|max:50',
                    'station' => 'nullable|string|max:150',
                    'le_state'  => 'nullable|string|max:50',
                ];
                break;

            case 'gov_official':
                $roleRules = [
                    'ministry' => 'nullable|string|max:150',
                    'department' => 'nullable|string|max:150',
                    'service_scheme' => 'nullable|string|max:20', // M, N, FA, etc.
                    'grade' => 'nullable|string|max:10', // M41, N29
                    'cwo_state' => 'nullable|string|max:50',
                ];
                break;

            default:
                $roleRules = [];
        }

        // Validate all at once
        $validated = $request->validate(array_merge($sharedRules, $baseRules, $roleRules));

        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);
        }
        
        // Save shared user fields
        $user->fill([
            'name'  => $validated['name'],
            // 'email' => $validated['email'],
        ])->save();


        // Prepare profile data
        $profileData = [
            'phone' => $validated['phone'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city'  => $validated['city'] ?? null,
            'postcode'=> $validated['postcode']?? null,
            'state' => $validated['state']  ?? null,
        ];

        // Handle avatar upload/removal (skip for public users)
        if ($role !== 'public_user') {
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
        }

        // Save base profile (shared)
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        // Save role-specific profile
        switch ($role) {
            case 'public_user':
                $user->publicUserProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'display_name'  => $validated['display_name'] ?? null,
                        'allow_contact' => (bool) ($validated['allow_contact'] ?? false),
                    ]
                );
                break;

            case 'social_worker':
                $user->socialWorkerProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'agency_name'=> $validated['agency_name'] ?? null,
                        'agency_code' => $validated['agency_code']?? null,
                        'placement_state'=> $validated['placement_state'] ?? null,
                        'placement_district' => $validated['placement_district'] ?? null,
                        'staff_id' => $validated['staff_id'] ?? null,
                    ]
                );
                break;

            case 'healthcare':
                $user->healthcareProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'profession' => $validated['profession'] ?? null,
                        'apc_expiry' => $validated['apc_expiry'] ?? null,
                        'facility_name' => $validated['facility_name'] ?? null,
                        'state' => $validated['hc_state'] ?? null,
                    ]
                );
                break;

            case 'law_enforcement':
                $user->lawEnforcementProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'agency' => $validated['agency']  ?? null,
                        'badge_number' => $validated['badge_number'] ?? null,
                        'rank' => $validated['rank']  ?? null,
                        'station'=> $validated['station']  ?? null,
                        'le_state'  => $validated['le_state'] ?? null,
                    ]
                );
                break;

            case 'gov_official':
                $user->govOfficialProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'ministry'  => $validated['ministry']?? null,
                        'department'  => $validated['department'] ?? null,
                        'service_scheme' => $validated['service_scheme'] ?? null,
                        'grade' => $validated['grade'] ?? null,
                        'state' => $validated['cwo_state']  ?? null,
                    ]
                );
                break;
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Your account has been deleted successfully.');
    }


}
