<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDataService
{
    /**
     * Load and cache all user data into session on login.
     */
    public static function load(User $user): void
    {
        $user->loadMissing(['permissionUsers.permission']);

        $activePermissions = $user->permissionUsers
            ->filter(fn($pu) => $pu->isActive())
            ->map(fn($pu)    => $pu->permission?->slug)
            ->filter()
            ->values()
            ->toArray();

        session([
            'auth_user' => [
                'id'           => $user->id,
                'name'         => $user->name,
                'username'     => $user->username,
                'email'        => $user->email,
                'role'         => $user->Role,
                'status'       => $user->Status,
                'last_login'   => $user->last_login_at?->toDateTimeString(),
                'permissions'  => $activePermissions,
                'is_admin'     => $user->Role === 'Admin',
            ],
        ]);
    }

    /**
     * Refresh session data (call after profile/permission updates).
     */
    public static function refresh(): void
    {
        $user = Auth::user();
        if ($user) {
            static::load($user->fresh(['permissionUsers.permission']));
        }
    }

    /**
     * Clear user session data on logout.
     */
    public static function clear(): void
    {
        session()->forget('auth_user');
        session()->forget('last_activity_time');
    }

    /**
     * Get a specific key from session user data.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return session("auth_user.{$key}", $default);
    }

    /**
     * Get all session user data.
     */
    public static function all(): array
    {
        return session('auth_user', []);
    }

    /**
     * Check if user has a specific permission slug (from session).
     */
    public static function hasPermission(string $slug): bool
    {
        return in_array($slug, session('auth_user.permissions', []));
    }

    /**
     * Check if user is Admin.
     */
    public static function isAdmin(): bool
    {
        return session('auth_user.is_admin', false);
    }
}