<?php

namespace BrocardJr\Geo;

use BrocardJr\Geo\Facades\Geoservices as GeoFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'BrocardJr\Geo\Commands\InstallCommand',
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
        $publishable = [
            'migrations' => [
                __DIR__.'/database/migrations/' => database_path('migrations'),
            ],
            'config' => [
                __DIR__.'/config/geonames.php' => config_path('geonames.php'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('GeoServices', GeoFacade::class);

        //Instance Facade
        //$this->app->bind('geoservices', 'BrocardJr\Geo\GeoServices');

        //Only in console
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    /**
     * Register all commands in actual service provider.
     */
    private function registerCommands()
    {
        $this->commands($this->commands);
    }
}
