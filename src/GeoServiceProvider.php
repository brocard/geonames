<?php

namespace BrocardJr\Geo;

use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    protected $commands = [
        'BrocardJr\Geo\Commands\GeoLocation',
        'BrocardJr\Geo\Commands\ImportCountries',
        'BrocardJr\Geo\Commands\ImportStates',
        'BrocardJr\Geo\Commands\ImportCities',
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/geonames.php' => config_path('geonames.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }
}
