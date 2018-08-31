<?php

/*
 * This file is a part of leoleoasd/laravel-api.
 * Copyright (C) 2019 leoleoasd
 */

namespace Leoleoasd\LaravelApi;

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
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/api.php', 'api'
        );
    }
}
