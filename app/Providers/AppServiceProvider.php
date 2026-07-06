<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Services\UserDataService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::before(function ($user, string $ability) {

            // Admin has every permission
            if (UserDataService::isAdmin()) {
                return true;
            }

            // Check permission stored in session
            if (UserDataService::hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}