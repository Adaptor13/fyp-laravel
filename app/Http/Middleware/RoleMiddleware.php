<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Ensure the user and role relation exist
        if (!$user) {
            return redirect()->route('sign_in')
                ->withErrors(['auth' => 'Please sign in first.']);
        }

        // If you have a belongsTo Role relation on User: public function role() { return $this->belongsTo(Role::class); }
        $user->loadMissing('role');

        // Normalize roles passed from middleware declaration (e.g., admin or admin,social_worker)
        // $roles will be an array like ['admin'] or ['admin', 'social_worker']
        $allowed = collect($roles)
            ->flatMap(fn ($r) => explode(',', $r))   // handle 'admin,social_worker'
            ->map(fn ($r) => trim($r))
            ->filter()
            ->values();

        $userRole = optional($user->role)->name;

        if (!$userRole || !$allowed->contains($userRole)) {
            // For API/JSON requests you may want to return 403 instead
            if ($request->expectsJson()) {
                abort(403, 'You are not authorized to access that page.');
            }

            return redirect()->route('sign_in')
                ->withErrors(['auth' => 'You are not authorized to access that page.']);
        }

        return $next($request);
    }
}
