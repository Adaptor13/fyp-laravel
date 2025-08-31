<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'profession' => 'nullable|string|max:100',
            'apc_expiry' => 'nullable|date',
            'facility_name' => 'nullable|string|max:150',
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

        // Update or create healthcare profile
        $user->healthcareProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'profession' => $validated['profession'] ?? null,
                'apc_expiry' => $validated['apc_expiry'] ?? null,
                'facility_name' => $validated['facility_name'] ?? null,
                'state' => $validated['state'] ?? null,
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
