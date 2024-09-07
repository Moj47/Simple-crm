<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::define('update-project', function (User $user) {
            return $user->type=='admin';
        });

        Gate::define('delete', function (User $user) {
            return $user->type==='admin';
        });
        Gate::define('create-project',function(User $user){
            return $user->type=='admin';
        });
    }
}
