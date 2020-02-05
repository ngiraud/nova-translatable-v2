<?php

namespace NGiraud\NovaTranslatable\Tests;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
