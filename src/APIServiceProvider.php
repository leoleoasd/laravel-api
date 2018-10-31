<?php

/*
 * This file is part of the leoleoasd/laravel-api.
 *
 * (c) Leo Lu <luyuxuanleo@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace Leoleoasd\LaravelApi;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/api.php' => config_path('api.php'),
        ]);
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            ErrorHandler::class
        );
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/api.php', 'api'
        );
        $kernel = $this->app[Kernel::class];
        $kernel->prependMiddleware(APIMiddleware::class);
    }
}
