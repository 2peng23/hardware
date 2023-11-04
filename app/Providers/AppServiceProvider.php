<?php

namespace App\Providers;

use App\Models\Transaction;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        // Using a class based composer
        View::composer('layouts.admin', function ($view) {
            // Retrieve the $pending value from your data source or assign it a value
            $pending = Transaction::where('status', 'pending')->count();

            // Pass the $pending variable to the view
            $view->with('pending', $pending);
        });
        Paginator::useBootstrap();
    }
}
