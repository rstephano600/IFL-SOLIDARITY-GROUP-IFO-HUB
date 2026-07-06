<?php

use App\Services\UserDataService;

if (!function_exists('userHas')) {
    /**
     * Check permission from session (fast — no DB query).
     * Falls back to DB check if session is empty.
     */
    function userHas(string $slug): bool
    {
        if (!auth()->check()) {
            return false;
        }

        // ✅ Use cached session data first
        if (session()->has('auth_user.permissions')) {
            return UserDataService::hasPermission($slug);
        }

        // 🔄 Fallback: load from DB and cache
        UserDataService::load(auth()->user());
        return UserDataService::hasPermission($slug);
    }
}

if (!function_exists('userData')) {
    /**
     * Get any user session data anywhere.
     * Usage: userData('name'), userData('role'), userData('permissions')
     */
    function userData(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return UserDataService::all();
        }
        return UserDataService::get($key, $default);
    }
}