<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class RoleHelper
{
    /**
     * Get the default URL for a role
     */
    public static function getDefaultUrl($roleSlug)
    {
        $permissions = config('role_permissions.' . $roleSlug);
        return $permissions['default_url'] ?? 'front.home';
    }

    /**
     * Check if a role can access a specific route
     */
    public static function canAccessRoute($roleSlug, $routeName)
    {
        $permissions = config('role_permissions.' . $roleSlug);
        
        if (!$permissions) {
            return false;
        }

        $allowedRoutes = $permissions['allowed_routes'] ?? [];

        foreach ($allowedRoutes as $allowedRoute) {
            // Check for exact match
            if ($allowedRoute === $routeName) {
                return true;
            }
            
            // Check for wildcard match (e.g., admin.inquiry.* matches admin.inquiry.index)
            if (str_ends_with($allowedRoute, '*')) {
                $prefix = str_replace('*', '', $allowedRoute);
                if (str_starts_with($routeName, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get allowed menu items for a role
     */
    public static function getAllowedMenuItems($roleSlug)
    {
        $permissions = config('role_permissions.' . $roleSlug);
        return $permissions['menu_items'] ?? [];
    }

    /**
     * Check if a menu item should be shown for a role
     */
    public static function canShowMenuItem($roleSlug, $menuItem)
    {
        $allowedItems = self::getAllowedMenuItems($roleSlug);
        return in_array($menuItem, $allowedItems);
    }

    /**
     * Get redirect URL for unauthorized access
     */
    public static function getUnauthorizedRedirectUrl($user)
    {
        if (!$user || !$user->role) {
            return route('front.home');
        }

        $defaultRoute = self::getDefaultUrl($user->role->slug);
        
        try {
            return route($defaultRoute);
        } catch (\Exception $e) {
            return route('front.home');
        }
    }
}
