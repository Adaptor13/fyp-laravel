<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\SocialWorkerProfile;

class CwoProfileController extends Controller
{
    /**
     * Show the CWO profile edit page
     */
    public function edit()
    {
        $user = Auth::user()->load(['role', 'profile', 'socialWorkerProfile']);
        
        // Ensure only CWO users can access this
        if ($user->role->name !== 'social_worker') {
            abort(403, 'Unauthorized access.');
        }

        return view('admin.users.cwo.profile.edit', compact('user'));
    }

    /**
     * Update the CWO profile
     */
    public function update(Request $request)
    {
        $user = Auth::user()->load('role');
        
        // Ensure only CWO users can access this
        if ($user->role->name !== 'social_worker') {
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
            'agency_name' => 'nullable|string|max:150',
            'agency_code' => 'nullable|string|max:50',
            'placement_state' => 'nullable|string|max:50',
            'placement_district' => 'nullable|string|max:100',
            'staff_id' => 'nullable|string|max:50',
        ];

        $validated = $request->validate($rules);

        // Clean phone number
        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);
        }

        // Update user basic info
        $user->update([
            'name' => $validated['name'],
        ]);

        // Update or create base profile
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'] ?? null,
                'address_line1' => $validated['address_line1'] ?? null,
                'address_line2' => $validated['address_line2'] ?? null,
                'city' => $validated['city'] ?? null,
                'postcode' => $validated['postcode'] ?? null,
                'state' => $validated['state'] ?? null,
            ]
        );

        // Update or create CWO profile
        $user->socialWorkerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'agency_name' => $validated['agency_name'] ?? null,
                'agency_code' => $validated['agency_code'] ?? null,
                'placement_state' => $validated['placement_state'] ?? null,
                'placement_district' => $validated['placement_district'] ?? null,
                'staff_id' => $validated['staff_id'] ?? null,
            ]
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Delete CWO account
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        
        // Ensure only CWO users can access this
        if ($user->role->name !== 'social_worker') {
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
