<?php

namespace App\Http\Controllers;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Role;
use App\Models\SocialWorkerProfile;
use App\Models\LawEnforcementProfile;
use App\Models\GovOfficialProfile;
use App\Models\PublicUserProfile;
use App\Models\HealthcareProfile;
use Carbon\Carbon;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function admins() 
    {
        $users = $this->getUsersByRole('admin');
        
        // Calculate dynamic statistics for Admin dashboard
        $adminRoleId = Role::where('name', 'admin')->value('id');
        
        // Total admins
        $totalUsers = User::where('role_id', $adminRoleId)->count();
        
        // Contactable users (with phone numbers)
        $contactableUsers = User::where('role_id', $adminRoleId)
            ->whereHas('profile', function($query) {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '');
            })->count();
        
        // Non-contactable users (without phone numbers)
        $nonContactableUsers = User::where('role_id', $adminRoleId)
            ->whereDoesntHave('profile', function($query) {
                $query->whereNotNull('phone')
                      ->where('phone', '!=', '');
            })->count();
        
        // New users this month
        $newUsers = User::where('role_id', $adminRoleId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.users.admins.index', compact(
            'users', 
            'totalUsers', 
            'contactableUsers', 
            'nonContactableUsers',
            'newUsers',
            'userPermissions'
        ));
    }

    public function adminData()
    {
        $adminRoleId = Role::where('name', 'admin')->value('id');

        $users = User::with(['profile', 'adminProfile'])
            ->where('role_id', $adminRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id'            => (string) $u->id,
                    'name'          => $u->name ?? '',
                    'email'         => $u->email ?? '',
                    'created_at'    => optional($u->created_at)->toIso8601String(),

                    // user_profiles (contact info)
                    'phone'         => optional($u->profile)->phone ?? '',
                    'address_line1' => optional($u->profile)->address_line1 ?? '',
                    'address_line2' => optional($u->profile)->address_line2 ?? '',
                    'city'          => optional($u->profile)->city ?? '',
                    'state_profile' => optional($u->profile)->state ?? '',
                    'postcode'      => optional($u->profile)->postcode ?? '',
                    'avatar_url'    => $u->getAvatarUrl(),
                    'avatar_initials' => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    'department'    => optional($u->adminProfile)->department ?? '',
                    'position'      => optional($u->adminProfile)->position ?? '',
                    'profile_created_at' =>
                        optional(optional($u->adminProfile)->created_at)->toIso8601String(),
                    'profile_updated_at' =>
                        optional(optional($u->adminProfile)->updated_at)->toIso8601String(),
                ];
            })
            ->values();

        return response()->json(['data' => $users]);
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            // account
            'name'        => 'required|string|max:255',
            'email'       => 'required|email:rfc,dns|unique:users,email',
            'password'    => 'required|min:8|confirmed',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // admin_profiles
            'department'  => 'required|string|max:150',
            'position'    => 'required|string|max:150',

            // user_profiles (contact info)
            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state'         => 'nullable|string|max:120',
        ]);

        $roleId = Role::where('name', 'admin')->value('id');
        if (!$roleId) {
            return back()
                ->withErrors(['role' => 'The "admin" role is not configured.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $validated, $roleId) {
                // create user
                $user = User::create([
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role_id'           => $roleId,
                    'email_verified_at' => now(),
                ]);

                // create admin profile
                $user->adminProfile()->create([
                    'display_name' => $validated['name'],
                    'department' => $validated['department'],
                    'position'   => $validated['position'],
                ]);

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profileData = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,
                ];

                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profileData['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });

        } catch (\Throwable $e) {
            \Log::error('Admin creation failed: ' . $e->getMessage());
            return back()
                ->withErrors(['general' => 'Failed to create admin user: ' . $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Admin user created.');
    }

    public function updateAdmin(Request $request, string $id)
    {
        $user = User::with(['adminProfile','profile'])->findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'department'    => 'required|string|max:150',
            'position'      => 'required|string|max:150',

            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state'         => 'nullable|string|max:120',

            'remove_avatar' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $user) {
                // user
                $user->update(['name' => $validated['name']]);

                // admin profile
                $ap = $user->adminProfile ?: $user->adminProfile()->make();
                $ap->display_name = $validated['name'];
                $ap->department = $validated['department'];
                $ap->position   = $validated['position'];
                $ap->save();

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profilePayload = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,
                ];

                $profile = $user->profile ?: $user->profile()->make();

                if ($request->boolean('remove_avatar') && !empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                    $profilePayload['avatar_path'] = null;
                }

                if ($request->hasFile('avatar')) {
                    if (!empty($profile->avatar_path)) {
                        Storage::disk('public')->delete($profile->avatar_path);
                    }
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profilePayload['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profilePayload
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to update admin user. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Admin user updated.');
    }

    public function destroyAdmin(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->profile && $user->profile->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }

            $user->adminProfile()?->delete();  // admin_profiles
            $user->profile()?->delete();       // user_profiles

            $user->delete();
        });

        return back()->with('success', 'Admin deleted.');
    }

    //Public User Start ----------------------------------------------------
    public function publicUsers()
    {
        $base = User::whereHas('role', fn ($q) => $q->where('name', 'public_user'));

        $totalPublic = (clone $base)->count();

        $contactablePublic = (clone $base)
            ->whereHas('publicUserProfile', fn ($q) => $q->where('allow_contact', 1))
            ->count();

        $nonContactableWithFlag = (clone $base)
            ->whereHas('publicUserProfile', function ($q) {
                $q->where('allow_contact', 0)->orWhereNull('allow_contact');
            })
            ->count();

        $noProfile = (clone $base)->doesntHave('publicUserProfile')->count();

        $nonContactablePublic = $nonContactableWithFlag + $noProfile;

        $newPublic = (clone $base)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $users = (clone $base)->with('publicUserProfile')->latest()->get();

        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];

        return view('admin.users.public.index', compact(
            'users', 'totalPublic', 'contactablePublic', 'nonContactablePublic', 'newPublic', 'userPermissions'
        ));
    }

    public function publicUsersData()
    {
        $publicRoleId = Role::where('name', 'public_user')->value('id');

        $users = User::with(['profile', 'publicUserProfile'])
            ->where('role_id', $publicRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'created_at' => $u->created_at,

                    // shared profile (user_profiles)
                    'phone' => optional($u->profile)->phone,
                    'address_line1' => optional($u->profile)->address_line1,
                    'address_line2' => optional($u->profile)->address_line2,
                    'city' => optional($u->profile)->city,
                    'state' => optional($u->profile)->state,
                    'postcode' => optional($u->profile)->postcode,
                    'avatar_url' => $u->getAvatarUrl(),
                    'avatar_initials' => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    // public profile (public_user_profiles)
                    'display_name' => optional($u->publicUserProfile)->display_name,
                    'allow_contact' => optional($u->publicUserProfile)->allow_contact,
                    'profile_updated_at' => optional($u->publicUserProfile)->updated_at,
                ];
            });

        return response()->json(['data' => $users]);
    }

    public function destroyPublicUser(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::table('public_user_profiles')->where('user_id', $id)->delete();
                DB::table('users')->where('id', $id)->delete();
            });

            return back()->with('success', 'Public user deleted.');
        } catch (\Throwable $e) {
            // report($e);
            return back()->with('error', 'Delete failed. Please try again.');
        }
    }

    public function storePublic(Request $request)
    {
        // $this->authorize('create-public-user'); // policy or Gate

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $roleId = Role::where('name', 'public_user')->value('id');

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $roleId,
             'email_verified_at' => now(),
        ]);

        return back()->with('success', 'Public user created.');
    }

    public function updatePublicUser(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'postcode' => 'nullable|string|max:10',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($id, $validated) {
                $user = User::with(['profile', 'publicUserProfile'])->findOrFail($id);

                // Always update name
                $user->update([
                    'name' => $validated['name'],
                ]);

                if (!$user->profile) {
                    $user->profile()->create([]);
                    $user->refresh(); // reload relations
                }

                $user->profile->update([
                    'phone' => $validated['phone'] ?? null,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'state' => $validated['state'] ?? null,
                    'postcode' => $validated['postcode'] ?? null,
                ]);

                // If no public user profile, create it
                if (!$user->publicUserProfile) {
                    $user->publicUserProfile()->create([]);
                    $user->refresh(); // reload relations
                }

                $user->publicUserProfile->update([
                    'display_name' => $validated['display_name'] ?? null,
                ]);
            });

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Update failed. Please try again.');
        }
    }

    //Public User End -------------------------------------------------------

    //Social Worker
    public function socialWorkers() 
    {
        $users = $this->getUsersByRole('social_worker');
        
        // Calculate dynamic statistics for Social Worker dashboard
        $socialRoleId = Role::where('name', 'social_worker')->value('id');
        
        // Total social workers
        $totalWorkers = User::where('role_id', $socialRoleId)->count();
        
        // Count unique agencies covered
        $agenciesCovered = DB::table('social_worker_profiles')
            ->join('users', 'social_worker_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', $socialRoleId)
            ->whereNotNull('social_worker_profiles.agency_name')
            ->where('social_worker_profiles.agency_name', '!=', '')
            ->distinct('social_worker_profiles.agency_name')
            ->count('social_worker_profiles.agency_name');
        
        // Count unique states covered
        $totalStates = DB::table('social_worker_profiles')
            ->join('users', 'social_worker_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', $socialRoleId)
            ->whereNotNull('social_worker_profiles.placement_state')
            ->where('social_worker_profiles.placement_state', '!=', '')
            ->distinct('social_worker_profiles.placement_state')
            ->count('social_worker_profiles.placement_state');
        
        // New users this month
        $newUsers = User::where('role_id', $socialRoleId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.users.social.index', compact(
            'users', 
            'totalWorkers', 
            'agenciesCovered', 
            'totalStates',
            'newUsers',
            'userPermissions'
        ));
    }

    public function socialWorkersData()
    {
        $socialRoleId = Role::where('name', 'social_worker')->value('id');

        $users = User::with(['profile', 'socialWorkerProfile'])
            ->where('role_id', $socialRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id'                  => (string) $u->id,
                    'name'                => $u->name ?? '',
                    'email'               => $u->email ?? '',
                    'created_at'          => optional($u->created_at)->toIso8601String(),

                    'phone'               => optional($u->profile)->phone ?? '',
                    'address_line1'       => optional($u->profile)->address_line1 ?? '',
                    'address_line2'       => optional($u->profile)->address_line2 ?? '',
                    'city'                => optional($u->profile)->city ?? '',
                    'state'               => optional($u->profile)->state ?? '',
                    'postcode'            => optional($u->profile)->postcode ?? '',
                    'avatar_url'          => $u->getAvatarUrl(),
                    'avatar_initials'     => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    'staff_id'            => optional($u->socialWorkerProfile)->staff_id ?? '',
                    'agency_name'         => optional($u->socialWorkerProfile)->agency_name ?? '',
                    'agency_code'         => optional($u->socialWorkerProfile)->agency_code ?? '',
                    'placement_state'     => optional($u->socialWorkerProfile)->placement_state ?? '',
                    'placement_district'  => optional($u->socialWorkerProfile)->placement_district ?? '',
                    'profile_updated_at'  => optional(optional($u->socialWorkerProfile)->updated_at)->toIso8601String(),
                ];
            })
            ->values(); // ensure a clean zero-based array

        return response()->json(['data' => $users]);
    }

    public function storeSocialWorker(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email:rfc,dns|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'avatar'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'staff_id'              => 'required|string|max:50',
            'agency_name'           => 'required|string|max:255',
            'agency_name_other'     => 'nullable|required_if:agency_name,Other|string|max:255',
            'agency_code'           => 'nullable|string|max:10', // keep short
            'placement_state'       => 'required|string|max:100',
            'placement_district'    => 'required|string|max:100',

            'phone'                 => 'nullable|string|max:30',
            'address_line1'         => 'nullable|string|max:255',
            'address_line2'         => 'nullable|string|max:255',
            'city'                  => 'nullable|string|max:120',
            'postcode'              => 'nullable|string|max:20',
            'state'                 => 'nullable|string|max:120',
        ], [
            'agency_name_other.required_if' => 'Please enter the agency name when "Other" is chosen.',
        ]);

        $agencyName = $validated['agency_name'] === 'Other'
            ? $validated['agency_name_other']
            : $validated['agency_name'];

        $roleId = Role::where('name', 'social_worker')->value('id');
        if (!$roleId) {
            return back()
                ->withErrors(['role' => 'The "social_worker" role is not configured.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $validated, $roleId, $agencyName) {

                $user = User::create([
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role_id'           => $roleId,
                    'email_verified_at' => now(),
                ]);

                $user->socialWorkerProfile()->create([
                    'staff_id'           => $validated['staff_id'],
                    'agency_name'        => $agencyName,
                    'agency_code'        => $validated['agency_code'] ?? null,
                    'placement_state'    => $validated['placement_state'] ?? null,
                    'placement_district' => $validated['placement_district'] ?? null,
                ]);

                 $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profileData = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,
                ];

                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profileData['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });

        } catch (\Throwable $e) {
            \Log::error('Social worker creation failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return back()
                ->withErrors(['general' => 'Failed to create social worker: ' . $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Social worker created.');
    }

    public function updateSocialWorker(Request $request, string $id)
    {
        // Fetch the user together with relations you’re about to update
        $user = User::with(['socialWorkerProfile', 'profile'])->findOrFail($id);
 
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',

            'avatar'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'staff_id'              => 'required|string|max:50',
            'agency_name'           => 'required|string|max:255',
            'agency_name_other'     => 'nullable|required_if:agency_name,Other|string|max:255',
            'agency_code'           => 'nullable|string|max:10',
            'placement_state'       => 'required|string|max:100',
            'placement_district'    => 'required|string|max:100',

            'phone'                 => 'nullable|string|max:30',
            'address_line1'         => 'nullable|string|max:255',
            'address_line2'         => 'nullable|string|max:255',
            'city'                  => 'nullable|string|max:120',
            'postcode'              => 'nullable|string|max:20',
            'state'                 => 'nullable|string|max:120',

            // If you add a “remove avatar” checkbox in the UI:
            'remove_avatar'         => 'nullable|boolean',
        ], [
            'agency_name_other.required_if' => 'Please enter the agency name when "Other" is chosen.',
        ]);

        $agencyName = $validated['agency_name'] === 'Other'
            ? ($validated['agency_name_other'] ?? null)
            : $validated['agency_name'];

        try {
            DB::transaction(function () use ($request, $validated, $user, $agencyName) {

                // Update basic user fields; email is intentionally not touched
                $user->update([
                    'name' => $validated['name'],
                ]);

                // Ensure socialWorkerProfile exists, then update
                $sw = $user->socialWorkerProfile ?: $user->socialWorkerProfile()->make();
                $sw->staff_id            = $validated['staff_id'];
                $sw->agency_name         = $agencyName;
                $sw->agency_code         = $validated['agency_code'] ?? null;
                $sw->placement_state     = $validated['placement_state'] ?? null;
                $sw->placement_district  = $validated['placement_district'] ?? null;
                $sw->save();

                // Normalize phone and prepare profile payload
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profilePayload = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,
                ];

                // Handle avatar removal or replacement
                $profile = $user->profile ?: $user->profile()->make();

                // If you added a remove-avatar checkbox in the form
                if ($request->boolean('remove_avatar') && !empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                    $profilePayload['avatar_path'] = null;
                }

                if ($request->hasFile('avatar')) {
                    // Delete old file if exists
                    if (!empty($profile->avatar_path)) {
                        Storage::disk('public')->delete($profile->avatar_path);
                    }
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profilePayload['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profilePayload
                );
            });

        } catch (\Throwable $e) {
            \Log::error('Update social worker failed: ' . $e->getMessage(), [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return back()
                ->withErrors(['general' => 'Failed to update social worker: ' . $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Social worker updated.');
    }

    public function destroySocialWorker(User $user)
    {
        // dd($user->id);
        DB::transaction(function () use ($user) {
            if ($user->profile && $user->profile->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }

            $user->socialWorkerProfile()?->delete();
            $user->profile()?->delete();

            $user->delete();   
        });

        return back()->with('success', 'Social worker deleted.');
    }

    //Social Wroerkr End

    public function lawEnforcement() 
    {
        $users = $this->getUsersByRole('law_enforcement');
        
        // Calculate dynamic statistics for Law Enforcement dashboard
        $lawRoleId = Role::where('name', 'law_enforcement')->value('id');
        
        // Total officers
        $totalOfficers = User::where('role_id', $lawRoleId)->count();
        
        // Count unique agencies represented
        $agenciesRepresented = LawEnforcementProfile::whereHas('user', function($query) use ($lawRoleId) {
            $query->where('role_id', $lawRoleId);
        })->whereNotNull('agency')
          ->where('agency', '!=', '')
          ->select('agency')
          ->distinct()
          ->count();
        
        // Count unique stations covered
        $stationsCovered = LawEnforcementProfile::whereHas('user', function($query) use ($lawRoleId) {
            $query->where('role_id', $lawRoleId);
        })->whereNotNull('station')
          ->where('station', '!=', '')
          ->select('station')
          ->distinct()
          ->count();
        
        // New officers this month
        $newOfficers = User::where('role_id', $lawRoleId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.users.law.index', compact(
            'users', 
            'totalOfficers', 
            'agenciesRepresented', 
            'stationsCovered',
            'newOfficers',
            'userPermissions'
        ));
    }

    public function lawEnforcementData()
    {
        // Adjust the role name if your seed uses something else (e.g., 'police')
        $lawRoleId = Role::where('name', 'law_enforcement')->value('id');

        $users = User::with(['profile', 'lawEnforcementProfile'])
            ->where('role_id', $lawRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id'            => (string) $u->id,
                    'name'          => $u->name ?? '',
                    'email'         => $u->email ?? '',
                    'created_at'    => optional($u->created_at)->toIso8601String(),

                    // user_profiles (contact info)
                    'phone'         => optional($u->profile)->phone ?? '',
                    'address_line1' => optional($u->profile)->address_line1 ?? '',
                    'address_line2' => optional($u->profile)->address_line2 ?? '',
                    'city'          => optional($u->profile)->city ?? '',
                    'state'         => optional($u->profile)->state ?? '',
                    'postcode'      => optional($u->profile)->postcode ?? '',
                    'avatar_url'    => $u->getAvatarUrl(),
                    'avatar_initials' => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    // law_enforcement_profiles
                    'agency'        => optional($u->lawEnforcementProfile)->agency ?? '',
                    'badge_number'  => optional($u->lawEnforcementProfile)->badge_number ?? '',
                    'rank'          => optional($u->lawEnforcementProfile)->rank ?? '',
                    'station'       => optional($u->lawEnforcementProfile)->station ?? '',
                    'le_state'      => optional($u->lawEnforcementProfile)->state ?? '',
                    'profile_updated_at' =>
                        optional(optional($u->lawEnforcementProfile)->updated_at)->toIso8601String(),
                ];
            })
            ->values();

        return response()->json(['data' => $users]);
    }

    public function storeLawEnforcement(Request $request)
    {
        $validated = $request->validate([
            // account
            'name'        => 'required|string|max:255',
            'email'       => 'required|email:rfc,dns|unique:users,email',
            'password'    => 'required|min:8|confirmed',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // LE profile (avoid collision with user_profiles.state)
            'agency'       => ['required','string','max:50', Rule::in(['PDRM','AADK'])],
            'badge_number' => [
                'required','string','max:50',
                Rule::unique('law_enforcement_profiles', 'badge_number')
                    ->where(fn($q) => $q->where('agency', $request->agency)),
            ],
            'rank'         => 'nullable|string|max:100',
            'station'      => 'nullable|string|max:150',
            'le_state'     => 'required|string|max:100',   // <-- renamed

            // user_profiles (contact info) — DB column is also "state"
            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state_profile' => 'nullable|string|max:120',  // <-- profile state
        ]);

        $roleId = Role::where('name', 'law_enforcement')->value('id');
        if (!$roleId) {
            return back()
                ->withErrors(['role' => 'The "law_enforcement" role is not configured.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $validated, $roleId) {

                // create user
                $user = User::create([
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role_id'           => $roleId,
                    'email_verified_at' => now(),
                ]);

                // create law enforcement profile
                $user->lawEnforcementProfile()->create([
                    'agency'       => $validated['agency'],
                    'badge_number' => $validated['badge_number'],
                    'rank'         => $validated['rank'] ?? null,
                    'station'      => $validated['station'] ?? null,
                    'state'        => $validated['le_state'], // <-- map from le_state
                ]);

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profileData = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state_profile'] ?? null,   // <-- user_profiles.state
                ];

                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profileData['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to create law enforcement user. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Law enforcement user created.');
    }

    public function updateLawEnforcement(Request $request, string $id)
    {
        $user = User::with(['lawEnforcementProfile','profile'])->findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'agency'        => ['required','string','max:50', Rule::in(['PDRM','AADK'])],
            'badge_number'  => [
                'required','string','max:50',
                Rule::unique('law_enforcement_profiles', 'badge_number')
                    ->where(fn($q) => $q->where('agency', $request->agency))
                    ->ignore($user->id, 'user_id'),
            ],
            'rank'          => 'nullable|string|max:100',
            'station'       => 'nullable|string|max:150',
            'le_state'      => 'required|string|max:100',

            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state'         => 'nullable|string|max:120',   // profile state

            'remove_avatar' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $user) {
                // user
                $user->update(['name' => $validated['name']]);

                // LE profile
                $lp = $user->lawEnforcementProfile ?: $user->lawEnforcementProfile()->make();
                $lp->agency       = $validated['agency'];
                $lp->badge_number = $validated['badge_number'];
                $lp->rank         = $validated['rank'] ?? null;
                $lp->station      = $validated['station'] ?? null;
                $lp->state        = $validated['le_state'];   // <-- from le_state
                $lp->save();

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profilePayload = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,   // <-- user_profiles.state
                ];

                $profile = $user->profile ?: $user->profile()->make();

                if ($request->boolean('remove_avatar') && !empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                    $profilePayload['avatar_path'] = null;
                }

                if ($request->hasFile('avatar')) {
                    if (!empty($profile->avatar_path)) {
                        Storage::disk('public')->delete($profile->avatar_path);
                    }
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profilePayload['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profilePayload
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to update law enforcement user. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Law enforcement user updated.');
    }

    public function destroyLawEnforcement(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->profile && $user->profile->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }

            // delete related rows
            $user->lawEnforcementProfile()?->delete(); 
            $user->profile()?->delete();           

            $user->delete();
        });

        return back()->with('success', 'Law Officer deleted.');
    }

    /**
     * Display CWO dashboard with dynamic statistics
     * 
     * @return \Illuminate\View\View
     */
    public function cwo()
    {
        $users = $this->getUsersByRole('gov_official');
        
        // Calculate dynamic statistics for CWO dashboard
        $cwoRoleId = Role::where('name', 'gov_official')->value('id');
        
        // Total CWO officers
        $totalCwo = User::where('role_id', $cwoRoleId)->count();
        
        // Count unique ministries
        $ministriesCount = DB::table('gov_official_profiles')
            ->join('users', 'gov_official_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', $cwoRoleId)
            ->whereNotNull('gov_official_profiles.ministry')
            ->where('gov_official_profiles.ministry', '!=', '')
            ->distinct('gov_official_profiles.ministry')
            ->count('gov_official_profiles.ministry');
        
        // Count unique states covered
        $statesCovered = DB::table('gov_official_profiles')
            ->join('users', 'gov_official_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', $cwoRoleId)
            ->whereNotNull('gov_official_profiles.state')
            ->where('gov_official_profiles.state', '!=', '')
            ->distinct('gov_official_profiles.state')
            ->count('gov_official_profiles.state');
        
        // Recently added CWO (last 30 days)
        $recentlyAddedCwo = User::where('role_id', $cwoRoleId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->count();
        
        // Additional statistics for better insights
        $activeThisMonth = User::where('role_id', $cwoRoleId)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        
        $topMinistries = DB::table('gov_official_profiles')
            ->join('users', 'gov_official_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', $cwoRoleId)
            ->whereNotNull('gov_official_profiles.ministry')
            ->where('gov_official_profiles.ministry', '!=', '')
            ->select('gov_official_profiles.ministry', DB::raw('count(*) as count'))
            ->groupBy('gov_official_profiles.ministry')
            ->orderBy('count', 'desc')
            ->limit(3)
            ->get();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.users.cwo.index', compact(
            'users', 
            'totalCwo', 
            'ministriesCount', 
            'statesCovered', 
            'recentlyAddedCwo',
            'activeThisMonth',
            'topMinistries',
            'userPermissions'
        ));
    }

    public function childWelfareOfficerData()
    {
        $cwoRoleId = Role::where('name', 'gov_official')->value('id');

        $users = User::with(['profile', 'govOfficialProfile'])
            ->where('role_id', $cwoRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id'            => (string) $u->id,
                    'name'          => $u->name ?? '',
                    'email'         => $u->email ?? '',
                    'created_at'    => optional($u->created_at)->toIso8601String(),

                    // user_profiles (contact info)
                    'phone'         => optional($u->profile)->phone ?? '',
                    'address_line1' => optional($u->profile)->address_line1 ?? '',
                    'address_line2' => optional($u->profile)->address_line2 ?? '',
                    'city'          => optional($u->profile)->city ?? '',
                    'state_profile' => optional($u->profile)->state ?? '',
                    'postcode'      => optional($u->profile)->postcode ?? '',
                    'avatar_url'    => $u->getAvatarUrl(),
                    'avatar_initials' => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    // gov_official_profiles (CWO-specific fields)
                    'ministry'        => optional($u->govOfficialProfile)->ministry ?? '',
                    'department'      => optional($u->govOfficialProfile)->department ?? '',
                    'service_scheme'  => optional($u->govOfficialProfile)->service_scheme ?? '',
                    'grade'           => optional($u->govOfficialProfile)->grade ?? '',
                    'cwo_state'       => optional($u->govOfficialProfile)->state ?? '',
                    'profile_updated_at' =>
                        optional(optional($u->govOfficialProfile)->updated_at)->toIso8601String(),
                ];
            })
            ->values();

        return response()->json(['data' => $users]);
    }

    public function storeCwo(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email:rfc,dns|unique:users,email',
            'password'    => 'required|min:8|confirmed',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'ministry'        => 'required|string|max:150',
            'department'      => 'required|string|max:150',
            'service_scheme'  => 'nullable|string|max:150',
            'grade'           => 'nullable|string|max:50',
            'cwo_state'       => 'required|string|max:100',  

            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state'         => 'nullable|string|max:120',   
        ]);

        $roleId = Role::where('name', 'gov_official')->value('id');
        if (!$roleId) {
            return back()
                ->withErrors(['role' => 'The "gov_official" role is not configured.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $validated, $roleId) {

                // create user
                $user = User::create([
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role_id'           => $roleId,
                    'email_verified_at' => now(),
                ]);

                $user->govOfficialProfile()->create([
                    'ministry'        => $validated['ministry'],
                    'department'      => $validated['department'],
                    'service_scheme'  => $validated['service_scheme'] ?? null,
                    'grade'           => $validated['grade'] ?? null,
                    'state'           => $validated['cwo_state'], // <-- map from cwo_state
                ]);

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profileData = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,   // <-- user_profiles.state
                ];

                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profileData['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to create Child Welfare Officer. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Child Welfare Officer created.');
    }

    public function updateCwo(Request $request, string $id)
    {
        $user = User::with(['govOfficialProfile','profile'])->findOrFail($id);

        $validated = $request->validate([
            // account
            'name'           => 'required|string|max:255',
            'avatar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // gov_official_profiles (CWO)
            'ministry'       => 'required|string|max:150',
            'department'     => 'required|string|max:150',
            'service_scheme' => 'nullable|string|max:150',
            'grade'          => 'nullable|string|max:50',
            'cwo_state'      => 'required|string|max:100',

            // user_profiles (contact info)
            'phone'          => 'nullable|string|max:30',
            'address_line1'  => 'nullable|string|max:255',
            'address_line2'  => 'nullable|string|max:255',
            'city'           => 'nullable|string|max:120',
            'postcode'       => 'nullable|string|max:20',
            'state'          => 'nullable|string|max:120',  // profile state

            'remove_avatar'  => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $user) {
                // user
                $user->update(['name' => $validated['name']]);

                // CWO profile
                $gp = $user->govOfficialProfile ?: $user->govOfficialProfile()->make();
                $gp->ministry       = $validated['ministry'];
                $gp->department     = $validated['department'];
                $gp->service_scheme = $validated['service_scheme'] ?? null;
                $gp->grade          = $validated['grade'] ?? null;
                $gp->state          = $validated['cwo_state'];   // map from cwo_state
                $gp->save();

                // contact profile
                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profilePayload = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state'] ?? null,  // user_profiles.state
                ];

                $profile = $user->profile ?: $user->profile()->make();

                if ($request->boolean('remove_avatar') && !empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                    $profilePayload['avatar_path'] = null;
                }

                if ($request->hasFile('avatar')) {
                    if (!empty($profile->avatar_path)) {
                        Storage::disk('public')->delete($profile->avatar_path);
                    }
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profilePayload['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profilePayload
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to update Child Welfare Officer. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Child Welfare Officer updated.');
    }

    public function destroyCwo(User $user)
    {
        DB::transaction(function () use ($user) {
            // remove stored avatar if any
            if ($user->profile && $user->profile->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }

            // delete related rows
            $user->govOfficialProfile()?->delete(); // gov_official_profiles
            $user->profile()?->delete();            // user_profiles

            // finally delete the user
            $user->delete();
        });

        return back()->with('success', 'Child Welfare Officer deleted.');
    }

    //Health care
    public function healthcare() 
    {
        $users = $this->getUsersByRole('healthcare');
        
        // Calculate statistics for healthcare professionals
        $healthcareRoleId = Role::where('name', 'healthcare')->value('id');
        
        $totalUsers = User::where('role_id', $healthcareRoleId)->count();
        
        $doctorsCount = User::where('role_id', $healthcareRoleId)
            ->whereHas('healthcareProfile', function($query) {
                $query->where('profession', 'Doctor');
            })->count();
            
        $nursesCount = User::where('role_id', $healthcareRoleId)
            ->whereHas('healthcareProfile', function($query) {
                $query->where('profession', 'Nurse');
            })->count();
            
        $recentlyAdded = User::where('role_id', $healthcareRoleId)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        // Get user permissions for frontend permission checking
        $user = auth()->user();
        $userPermissions = $user ? $user->getAllPermissions()->pluck('slug')->toArray() : [];
        
        return view('admin.users.healthcare.index', compact('users', 'totalUsers', 'doctorsCount', 'nursesCount', 'recentlyAdded', 'userPermissions'));
    }

    public function healthcareData()
    {
        $healthcareRoleId = Role::where('name', 'healthcare')->value('id');

        $users = User::with(['profile', 'healthcareProfile'])
            ->where('role_id', $healthcareRoleId)
            ->latest('users.created_at')
            ->get()
            ->map(function ($u) {
                return [
                    'id'            => (string) $u->id,
                    'name'          => $u->name ?? '',
                    'email'         => $u->email ?? '',
                    'created_at'    => optional($u->created_at)->toIso8601String(),

                    // user_profiles table (contact info)
                    'phone'         => optional($u->profile)->phone ?? '',
                    'address_line1' => optional($u->profile)->address_line1 ?? '',
                    'address_line2' => optional($u->profile)->address_line2 ?? '',
                    'city'          => optional($u->profile)->city ?? '',
                    'state'         => optional($u->profile)->state ?? '',
                    'postcode'      => optional($u->profile)->postcode ?? '',
                    'avatar_url'    => $u->getAvatarUrl(),
                    'avatar_initials' => $u->getInitials(),
                    'avatar_background_style' => $u->getAvatarBackgroundStyle(),

                    // healthcare_profiles table
                    'profession'    => optional($u->healthcareProfile)->profession ?? '',
                    'apc_expiry'    => optional($u->healthcareProfile)->apc_expiry 
                                        ? $u->healthcareProfile->apc_expiry->toDateString()
                                        : '',
                    'facility_name' => optional($u->healthcareProfile)->facility_name ?? '',
                    'hc_state'      => optional($u->healthcareProfile)->state ?? '',
                    'profile_updated_at' => optional(optional($u->healthcareProfile)->updated_at)->toIso8601String(),
                ];
            })
            ->values(); // clean zero-based array

        return response()->json(['data' => $users]);
    }

    public function storeHealthcare(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email:rfc,dns|unique:users,email',
            'password'    => 'required|min:8|confirmed',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'profession'    => ['required','string','max:100', Rule::in(['Doctor','Nurse'])],
            'apc_expiry'    => 'nullable|date',
            'facility_name' => 'required|string|max:255',
            'state'         => 'required|string|max:100',

            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state_profile' => 'nullable|string|max:120',
        ]);

        $roleId = Role::where('name','healthcare')->value('id');
        if (!$roleId) {
            return back()
                ->withErrors(['role' => 'The "healthcare" role is not configured.'])
                ->withInput();
        }

        try {
            DB::transaction(function () use ($request, $validated, $roleId) {

                $user = User::create([
                    'name'              => $validated['name'],
                    'email'             => $validated['email'],
                    'password'          => Hash::make($validated['password']),
                    'role_id'           => $roleId,
                    'email_verified_at' => now(),
                ]);

                $user->healthcareProfile()->create([
                    'profession'    => $validated['profession'],
                    'apc_expiry'    => $validated['apc_expiry'] ?? null,
                    'facility_name' => $validated['facility_name'],
                    'state'         => $validated['state'],
                ]);

                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profileData = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state_profile'] ?? null,
                ];

                if ($request->hasFile('avatar')) {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profileData['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profileData
                );
            });

        } catch (\Throwable $e) {
            \Log::error('Healthcare user creation failed: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return back()
                ->withErrors(['general' => 'Failed to create healthcare professional: ' . $e->getMessage()])
                ->withInput();
        }

        return back()->with('success', 'Healthcare professional created.');
    }

    public function updateHealthcare(Request $request, string $id)
    {
        $user = User::with(['healthcareProfile','profile'])->findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'avatar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'profession'    => ['required','string','max:100', Rule::in(['Doctor','Nurse'])],
            'apc_expiry'    => 'nullable|date',
            'facility_name' => 'required|string|max:255',
            'state'         => 'required|string|max:100',

            'phone'         => 'nullable|string|max:30',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:120',
            'postcode'      => 'nullable|string|max:20',
            'state_profile' => 'nullable|string|max:120',

            'remove_avatar' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $validated, $user) {

                $user->update(['name' => $validated['name']]);

                $hp = $user->healthcareProfile ?: $user->healthcareProfile()->make();
                $hp->profession    = $validated['profession'];
                $hp->apc_expiry    = $validated['apc_expiry'] ?? null;
                $hp->facility_name = $validated['facility_name'];
                $hp->state         = $validated['state'];
                $hp->save();

                $cleanPhone = !empty($validated['phone'])
                    ? preg_replace('/\D/', '', $validated['phone'])
                    : null;

                $profilePayload = [
                    'phone'         => $cleanPhone,
                    'address_line1' => $validated['address_line1'] ?? null,
                    'address_line2' => $validated['address_line2'] ?? null,
                    'city'          => $validated['city'] ?? null,
                    'postcode'      => $validated['postcode'] ?? null,
                    'state'         => $validated['state_profile'] ?? null,
                ];

                $profile = $user->profile ?: $user->profile()->make();

                if ($request->boolean('remove_avatar') && !empty($profile->avatar_path)) {
                    Storage::disk('public')->delete($profile->avatar_path);
                    $profilePayload['avatar_path'] = null;
                }

                if ($request->hasFile('avatar')) {
                    if (!empty($profile->avatar_path)) {
                        Storage::disk('public')->delete($profile->avatar_path);
                    }
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $profilePayload['avatar_path'] = $path;
                }

                $user->profile()->updateOrCreate(
                    ['user_id' => $user->id],
                    $profilePayload
                );
            });

        } catch (\Throwable $e) {
            return back()
                ->withErrors(['general' => 'Failed to update healthcare professional. Please try again.'])
                ->withInput();
        }

        return back()->with('success', 'Healthcare professional updated.');
    }

    public function destroyHealthcare(User $user)
    {
        DB::transaction(function () use ($user) {
            if ($user->profile && $user->profile->avatar_path) {
                Storage::disk('public')->delete($user->profile->avatar_path);
            }

            $user->healthcareProfile()?->delete();
            $user->profile()?->delete();

            $user->delete();   
        });

        return back()->with('success', 'Healthcare Professional deleted.');
    }

    private function getUsersByRole(string $roleName)
    {
        return User::with('role')
            ->whereHas('role', fn($q) => $q->where('name', $roleName))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    }
}
