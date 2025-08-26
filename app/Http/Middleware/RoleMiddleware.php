<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Guest → send to sign in (preserve intended URL)
        if (!$user) {
            return redirect()->guest(route('sign_in'))
                ->withErrors(['auth' => 'Please sign in first.']);
        }

        // Load role(s) only if missing
        $user->loadMissing('role');           // for belongsTo
        // $user->loadMissing('roles');       // uncomment if you move to many-to-many

        // Normalize allowed roles (support "admin,social_worker" or multiple args)
        $allowed = collect($roles)
            ->flatMap(fn ($r) => explode(',', $r))
            ->map(fn ($r) => strtolower(trim($r)))
            ->filter()
            ->values();

        // Current user role name(s)
        $userRole = optional($user->role)->name; // belongsTo
        $userRole = $userRole ? strtolower($userRole) : null;

        // If you later use many-to-many, switch to:
        // $userRoles = optional($user->roles)->pluck('name')->map(fn($n)=>strtolower($n));
        // $authorized = $userRoles?->intersect($allowed)->isNotEmpty();

        $authorized = $userRole && $allowed->contains($userRole);

        if (!$authorized) {
            // JSON/AJAX (e.g. DataTables) → 403 JSON; normal page → 403 view
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            abort(403, 'You are not authorized to access that page.');
        }

        return $next($request);
    }
}