<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Aws\S3\S3Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(S3Client::class, function ($app) {
            return new S3Client([
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY')
                ]
            ]);
        });
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
