<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\RoleHelper;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Trim whitespace from role names
        $roles = array_map('trim', $roles);

        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if (!$user->role) {
            return redirect('/')->with('error', "No role assigned.");
        }

        $userRoleSlug = $user->role->slug;

        // Check if user's role is in the allowed roles for this route
        if (in_array($userRoleSlug, $roles)) {
            // Additional check: verify if the specific route is allowed for this role
            $currentRouteName = $request->route()->getName();

            if (RoleHelper::canAccessRoute($userRoleSlug, $currentRouteName)) {
                return $next($request);
            }
        }

        // Redirect to user's default page instead of home
        $redirectUrl = RoleHelper::getUnauthorizedRedirectUrl($user);
        return redirect($redirectUrl)->with('error', "You don't have permission to access this area.");
    }
}
