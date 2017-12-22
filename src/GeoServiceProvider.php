<?php

namespace Shumex\Geocode;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot configuration
     *
     * @return void
     */
    public function boot()
    {
        $source = dirname(__DIR__) . 'config/geocode.php';

        $this->publishes([$source => config_path('geocode.php')]);

        $this->mergeConfigFrom($source, 'geocode');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('geocode', function () {
            return new Geocode();
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Geocode', 'Facades\Geocode');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
