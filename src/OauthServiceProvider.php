<?php

namespace Slakbal\Oauth;

use Illuminate\Support\ServiceProvider;
use Slakbal\Oauth\Providers\Siv\Provider as SIVProvider;

class OauthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        $this->bootSivProvider();

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'oauth');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'oauth');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/oauth.php' => config_path('oauth.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/oauth'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/oauth'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/oauth'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }


    protected function bootSivProvider()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

        $socialite->extend(
            'siv',
            function ($app) use ($socialite) {
                $config = $app['config']['services.siv'];
                return $socialite->buildProvider(SIVProvider::class, $config);
            }
        );

    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/oauth.php', 'oauth');

        // Register the main class to use with the facade
        $this->app->singleton('oauth', function () {
            return new Oauth;
        });
    }

}
