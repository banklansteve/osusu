<?php

namespace App\Providers;

use App\Models\Saving;
use App\Policies\SavingPolicy;
use App\Policies\SavingDetailPolicy;
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
        //
    }

    protected $policies  = [
        // Saving::class => SavingDetailPolicy::class,
        Saving::class => SavingPolicy::class,
    ];
}
