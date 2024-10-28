<?php

namespace Appino\LaravelEureka;

use Appino\LaravelEureka\Console\Commands\DeregisterCommand;
use Appino\LaravelEureka\Console\Commands\EurekaHealthCheckCommand;
use Appino\LaravelEureka\Console\Commands\HeartBeatCommand;
use Appino\LaravelEureka\Console\Commands\RegisterCommand;
use Illuminate\Support\ServiceProvider;

class LaravelEurekaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        if ($this->app->runningInConsole()) {
            // Publishing the config.
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-eureka.php'),
            ], 'config');
            // Registering package commands.
            $this->commands([
                EurekaHealthCheckCommand::class,
                RegisterCommand::class,
                DeregisterCommand::class,
                HeartBeatCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-eureka');
    }
}
