<?php

namespace neilherbertuk\celcatwebapi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Class CelcatWebAPIServiceProvider
 * @package neilherbertuk\CelcatWebAPI
 */
class CelcatWebAPIServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootInConsole();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/config.php',
            'celcat'
        );

        $this->app->singleton('CelcatWebAPI', function($app){
            return new CelcatWebAPI($app['config']);
        });
    }

    /**
     *
     */
    protected function bootInConsole()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/config.php' => config_path('celcat.php'),
            ], "config");
        }
    }
}
