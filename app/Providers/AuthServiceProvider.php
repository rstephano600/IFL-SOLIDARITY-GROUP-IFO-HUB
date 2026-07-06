<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Dynamically register every permission slug as a Gate ability
        Gate::before(function ($user, string $ability) {
            // Super-admin bypass (optional)
            // if ($user->is_admin) return true;

            return $user->hasPermissionSlug($ability) ?: null;
            // Returning null means "keep checking other gates"
            // Returning false means "deny"
        });
    }
}