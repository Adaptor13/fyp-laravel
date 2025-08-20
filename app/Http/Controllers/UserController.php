<?php

namespace App\Http\Controllers;
use App\Models\UserProfile;
use App\Models\User;
use App\Models\Role;
use App\Models\SocialWorkerProfile;
use App\Models\LawEnforcementProfile;
use App\Models\PublicUserProfile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function admins() 
    {
        $users = $this->getUsersByRole('admin');
        return view('admin.users.admins.index', compact('users'));
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

        return view('admin.users.public.index', compact(
            'users', 'totalPublic', 'contactablePublic', 'nonContactablePublic', 'newPublic'
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
                $avatarPath = optional($u->profile)->avatar_path;
                $avatarUrl = $avatarPath
                    ? asset('storage/'.$avatarPath)
                    : asset('assets/images/icons/logo14.png');

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
                    'avatar_url' => $avatarUrl,

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



    public function socialWorkers() 
    {
        $users = $this->getUsersByRole('social_worker');
        return view('admin.users.social.index', compact('users'));
    }

    public function lawEnforcement() 
    {
        $users = $this->getUsersByRole('law_enforcement');
        return view('admin.users.law.index', compact('users'));
    }

    public function govOfficials() 
    {
        $users = $this->getUsersByRole('gov_official');
        return view('admin.users.gov.index', compact('users'));
    }

    public function healthcare() 
    {
        $users = $this->getUsersByRole('healthcare');
        return view('admin.users.healthcare.index', compact('users'));
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
