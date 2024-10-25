<?php

namespace Enesisrl\LaravelNmeaParser\Providers;


use Enesisrl\LaravelNmeaParser\Classes\NmeaParser;
use Illuminate\Support\ServiceProvider;

class NmeaParserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind NmeaParser class for dependency injection
        // Bind NmeaParser class for dependency injection
        $this->app->singleton('NmeaParser', function ($app) {
            return new NmeaParser();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}