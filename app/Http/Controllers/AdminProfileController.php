<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'display_name' => 'nullable|string|max:150',
            'department' => 'nullable|string|max:150',
            'position' => 'nullable|string|max:100',
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

        // Update or create admin profile
        $user->adminProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'display_name' => $validated['display_name'] ?? null,
                'department' => $validated['department'] ?? null,
                'position' => $validated['position'] ?? null,
            ]
        );

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
