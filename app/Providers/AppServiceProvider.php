<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\Job;
use App\Observers\CompanyObserver;
use App\Observers\JobObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Company::observe(CompanyObserver::class);
        Job::observe(JobObserver::class);

        view()->composer('*', function ($view) {
            $siteAdmin = Schema::hasTable('admins')
                ? Admin::query()->first()
                : null;

            $view->with('siteAdmin', $siteAdmin);
        });
    }
}
