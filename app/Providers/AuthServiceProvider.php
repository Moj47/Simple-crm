<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Client' => 'App\Policies\ClientPolicy',
    ];
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

        Gate::policy(Client::class,ClientPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Task::class,TaskPolicy::class);

        Gate::define('update-project', function (User $user,Project $project) {
            return $user->type=='admin'||$project->user_id==$user->id;
        });

        Gate::define('delete-project', function (User $user, Project $project) {
            return $user->type == 'admin' || $project->user_id == $user->id;
        });
        Gate::define('force-delete-project', function (User $user, $project) {

            return $user->type == 'admin' || $project->user_id == $user->id;
        });
        Gate::define('create-project',function(User $user){
            return $user->type=='admin';
        });
        Gate::define('restore-project',function(User $user,Project $project){
            return $user->type=='admin'||$project->user_id==$user->id;
        });
    }
}
