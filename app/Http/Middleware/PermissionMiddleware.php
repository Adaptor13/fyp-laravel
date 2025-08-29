<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = $request->user();

        // Guest → send to sign in (preserve intended URL)
        if (!$user) {
            return redirect()->guest(route('sign_in'))
                ->withErrors(['auth' => 'Please sign in first.']);
        }

        // Load role and permissions
        $user->loadMissing(['role.permissions']);

        // Check if user has any of the required permissions
        $hasPermission = false;
        
        if ($user->role) {
            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    $hasPermission = true;
                    break;
                }
            }
        }

        if (!$hasPermission) {
            // JSON/AJAX (e.g. DataTables) → 403 JSON; normal page → 403 view
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['message' => 'Insufficient permissions'], 403);
            }
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
