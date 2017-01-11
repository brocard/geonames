<?php

namespace BrocardJr\Geo;

<<<<<<< HEAD
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use BrocardJr\Geo\Facades\Geoservices as GeoFacade;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        'BrocardJr\Geo\Commands\InstallCommand',
=======
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    protected $commands = [
>>>>>>> e88bd0f6767e8c88b4256ac5189ca1ffa4266736
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
<<<<<<< HEAD
        $publishable = [
            'migrations' => [
                __DIR__ . "/database/migrations/" => database_path('migrations'),
            ],
            'config' => [
                __DIR__.'/config/geonames.php' => config_path('geonames.php')
            ]
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
=======
        $this->publishes([
            __DIR__.'/config/geonames.php' => config_path('geonames.php'),
        ], 'config');
>>>>>>> e88bd0f6767e8c88b4256ac5189ca1ffa4266736
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
<<<<<<< HEAD
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
     * Register all commands in actual service provider
     */
    private function registerCommands()
    {
=======
>>>>>>> e88bd0f6767e8c88b4256ac5189ca1ffa4266736
        $this->commands($this->commands);
    }
}
