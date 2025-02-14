<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        URL::forceScheme('https');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            try {
                $current_team_id = getPermissionsTeamId();
                setPermissionsTeamId(null);
                $user->unsetRelation('roles')->unsetRelation('permissions');

                return $user->hasRole($ability) ? true : null;
            } catch (\Exception $e) {
                throw $e;
            } finally {
                setPermissionsTeamId($current_team_id);
                $user->unsetRelation('roles')->unsetRelation('permissions');
            }
        });

    }
}
