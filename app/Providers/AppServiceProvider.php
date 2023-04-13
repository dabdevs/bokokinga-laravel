<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;

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
        URL::macro('backWithSuccess', function ($message) {
            Session::flash('success', $message);
            return redirect()->back();
        });

        URL::macro('backWithError', function ($message) {
            Session::flash('error', $message);
            return redirect()->back();
        });
    }
}
