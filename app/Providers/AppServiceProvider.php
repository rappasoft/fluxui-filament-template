<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ScheduledTaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTask;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(MonitoredScheduledTask::class, ScheduledTaskPolicy::class);
        Gate::policy(MonitoredScheduledTaskLogItem::class, ScheduledTaskPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user) {
            return $user->isSuperAdmin() ? true : null;
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->isSuperAdmin();
        });
    }
}
