<?php

namespace BrocardJr\Geo\Commands;

use Illuminate\Console\Command;
use BrocardJr\Geo\GeoServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the geo package and migrations';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Publishing the Geo database and config files');
        $this->call('vendor:publish', ['--provider' => GeoServiceProvider::class]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');
    }
}
