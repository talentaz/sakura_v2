<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        if (!$user->role) {
            return redirect('/')->with('error', "No role assigned.");
        }

        // Get the role relationship
        $roleModel = $user->role;
        
        if (!$roleModel) {
            return redirect('/')->with('error', "Role not found.");
        }

        $userRoleSlug = $roleModel->slug;

        if (in_array($userRoleSlug, $roles)) {
            return $next($request);
        }

        return redirect('/')->with('error', "You don't have permission to access this area.");
    }
}
