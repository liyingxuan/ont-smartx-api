<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Hashing\BcryptHasher;

class HashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('hash', function () {
            return new \App\Foundation\SmartxSecurity();
        });

    }
}
